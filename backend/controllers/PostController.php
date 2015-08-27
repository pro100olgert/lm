<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use common\models\Post;
use common\models\PostSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

use common\models\Asset;

/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Post models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Post model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $mainImage = $model->getMainImage();
        $sliderImages = $model->getSliderImages();
        return $this->render('view', compact('model', 'mainImage', 'sliderImages'));
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Post();
        $model->status = true;
        $user = Yii::$app->user->identity;
        $mainImage = $model->getMainImage();
        $sliderImages = $model->getSliderImages();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->user_id = $user->id;
            $model->save();

            // Main Image 
            $mainImage->uploadedFile = UploadedFile::getInstance($model, 'mainImageFile');
            $mainImage->cropData = $model->mainImageCropData;
            $mainImage->assetable_type = $mainImage::ASSETABLE_POST;
            $mainImage->assetable_id = $model->id;
            $mainImage->saveCroppedAsset();

            // Save images
            $newSliderImageFiles = UploadedFile::getInstances($model, 'sliderImageFiles');
            foreach ($newSliderImageFiles as $file)
            {
                $asset = new Asset;
                $asset->uploadedFile = $file;
                $asset->assetable_type = $asset::ASSETABLE_POST;
                $asset->thumbnail = $asset::THUMBNAIL_CONTENT;
                $asset->assetable_id = $model->id;
                $asset->saveAsset();
            }

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', compact('model', 'mainImage', 'sliderImages'));

        }
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $mainImage = $model->getMainImage();
        $sliderImages = $model->getSliderImages();

        $assetKeys = [];
        foreach ($sliderImages as $asset) {
            $assetKeys[] = $asset->id;
        }
        $model->sliderImageKeys = implode(';', $assetKeys);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            // Main Image 
            if($model->mainImageRemove) {
                $mainImage->delete();
            }
            else {
                $mainImage->uploadedFile = UploadedFile::getInstance($model, 'mainImageFile');
                $mainImage->cropData = $model->mainImageCropData;
                $mainImage->assetable_type = $mainImage::ASSETABLE_POST;
                $mainImage->assetable_id = $model->id;
                $mainImage->saveCroppedAsset();
            }

            // Slider Images
            // Remove selected images
            $currentAssetKeys = explode(';', $model->sliderImageKeys);
            if(count($currentAssetKeys) > 0)
            {
                foreach ($sliderImages as $asset) {
                    if(!in_array($asset->id, $currentAssetKeys))
                    {
                        $asset->delete();
                    }
                }
            }
            
            // Remove not existing images
            foreach($sliderImages as $asset)
            {
                if(!file_exists($asset->getFilePath()))
                {
                    $asset->delete();
                }   
            }

            // Save images
            $newSliderImageFiles = UploadedFile::getInstances($model, 'sliderImageFiles');

            // var_dump($currentAssetKeys);
            // var_dump($newSliderImageFiles);
            // die;
            
            foreach ($newSliderImageFiles as $file)
            {
                $asset = new Asset;
                $asset->uploadedFile = $file;
                $asset->assetable_type = $asset::ASSETABLE_POST;
                $asset->thumbnail = $asset::THUMBNAIL_CONTENT;
                $asset->assetable_id = $model->id;
                $asset->saveAsset();
            }
            
            $model->save();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', compact('model', 'mainImage', 'sliderImages'));
        }
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $post = $this->findModel($id);

        $assets = Asset::find()->where(['assetable_type' => Asset::ASSETABLE_POST ,'assetable_id' => $id])->all();
        foreach ($assets as $asset) {
            $asset->delete();
        }
        $post->delete();

        return $this->redirect(['index']);
    }

    /**
     * Fake function for delete image
     * 
     * @return mixed
     */
    public function actionImageDelete()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [];
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
