<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

use common\models\Asset;
use common\models\Post;
use common\models\Menu;
use common\models\Block;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            // 'error' => [
            //     'class' => 'yii\web\ErrorAction',
            // ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $postTable = Post::tableName();
        $assetTable = Asset::tableName();
        
        // sliderPosts
        $sliderPosts = Post::find()
            ->innerJoin($assetTable, "{$assetTable}.assetable_id = {$postTable}.id")
            ->where([
                'status' => 1, 
                "{$assetTable}.assetable_type" => Asset::ASSETABLE_POST,
                "{$assetTable}.thumbnail" => NULL,
            ])
            ->orderBy(['created' => SORT_DESC])
            ->limit(3)
            ->all();

        $newsQuery = Post::find()->orderBy(['created' => SORT_DESC]);
        $newsDataProvider = new ActiveDataProvider([
            'query' => $newsQuery,
            'pagination' => [
                'pageSize' => 1,
            ],
        ]);

        // Bottom blocks
        $blocks = Block::find()
            ->where([
                'area' => Block::AREA_BOTTOM,
            ])->orderBy(['weight' => SORT_ASC])->all();

        return $this->render('index', compact('sliderPosts', 'blocks', 'newsDataProvider'));
    }

    public function actionError(){
        $exception = Yii::$app->ErrorHandler->exception;
        if($exception) {
            $message = Yii::$app->ErrorHandler->convertExceptionToString($exception);
            // $message = $exception->getMessage();
            $code = $exception->statusCode;
            if($code == 404) $name = 'Сторінка не знайдена';
            else {
                $parts = explode(':', $message);
                $name = $parts[0];
            }
            return $this->render('error', [
                'code' => $code,
                'name' => $name,
            ]);
        }
    }

    
}
