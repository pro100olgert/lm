<?php

namespace common\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\Color;
use Imagine\Image\ManipulatorInterface;


/**
 * This is the model class for table "assets".
 *
 * @property integer $id
 * @property string $filename
 * @property string $thumbnail
 * @property integer $assetable_id
 * @property string $assetable_type
 *
 * @property Users $user
 */
class Asset extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile uploaded file
     */
    public $uploadedFile;

    /**
     * @var string Coordinates data for crop image
     */
    public $cropData;

    /**
     * @var string The psysical path to storing images
     */
    private $basePath;
    /**
     * @var string The url path to storing images
     */
    private $baseUrl;

    /**
     * @var string assetable types
     */
    const ASSETABLE_POST = 'post';

    /**
     * @var string assets thumbnail types
     */
    const THUMBNAIL_CONTENT = 'content';

    /**
     * @inheritdoc
     */
    public function __construct( $config = [] ) {
        // Init pathes
        $this->basePath = Yii::getAlias('@frontend').'/web/images/store';
        $this->baseUrl = 'http://'.$_SERVER['HTTP_HOST'].'/images/store';
        if (!file_exists($this->basePath)) {
            mkdir($this->basePath, 0777, true);
        }
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'asset';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assetable_id'], 'integer'],
            [['filename', 'thumbnail'], 'string', 'max' => 255],
            [['assetable_type'], 'string', 'max' => 20],

            //required
            [['type', 'filename'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'filename' => 'Filename',
            'thumbnail' => 'Thumbnail',
            'assetable_id' => 'Assetable ID',
            'assetable_type' => 'Assetable Type',
        ];
    }

    /**
     * Save cropped asset record and attached file
     *
     * @return boolean
     */
    public function saveCroppedAsset()
    {
        if(!empty($this->uploadedFile))
        {
            // If file is exist -> remove him
            if(file_exists($this->getFilePath()))
            {
                unlink($this->getFilePath());
            }

            $this->genFilename();
            $imagine = Image::getImagine()->open($this->uploadedFile->tempName);
        }
        else
        {
            if(file_exists($this->getFilePath())) {
                $imagine = Image::getImagine()->open($this->getFilePath());
            } else return false;
        }

        $size = $imagine->getSize();
        $width = $size->getWidth();
        $height = $size->getHeight();

        $cropData = explode(';', $this->cropData);

        if(count($cropData) == 4)
        {
            $point = new Point($cropData[0]*$width, $cropData[1]*$height);
            $box = new Box($cropData[2]*$width, $cropData[3]*$height);
            $imagine->crop($point, $box);
            // $imageBox = $this->getImageBox($size);
            // $imagine->resize($imageBox);
        }
        $imagine->save($this->getFilePath());

        return $this->save(false);
    }

     /**
     * Save usual asset record and attached file
     *
     * @return boolean
     */
    public function saveAsset()
    {
        if(!empty($this->uploadedFile))
        {
            // If file is exist -> remove him
            if(file_exists($this->getFilePath()))
            {
                unlink($this->getFilePath());
            }
            $this->genFilename();
            $imagine = Image::getImagine()->open($this->uploadedFile->tempName);
        }
        else
        {
            if(file_exists($this->getFilePath())) {
                $imagine = Image::getImagine()->open($this->getFilePath());
            } else return false;
        }

        $size = $imagine->getSize();
        $box = $this->getImageBox($size);

        if (($size->getWidth() <= $box->getWidth() && $size->getHeight() <= $box->getHeight()) || (!$box->getWidth() && !$box->getHeight())) {
            $widthDiff = abs($size->getWidth() - $box->getWidth()) / $size->getWidth();
            $heightDiff = abs($size->getHeight() - $box->getHeight()) / $size->getHeight();
            if($widthDiff > $heightDiff) {
                $resizeBox = new Box($box->getWidth(), $size->getHeight() * $box->getWidth()/$size->getWidth());
            } else {
                $resizeBox = new Box($size->getWidth() * $box->getHeight()/$size->getHeight(), $box->getHeight());
            }
            $imagine->resize($resizeBox);

            // var_dump($width);
            // var_dump($height);
            // die;
            // // $imagine->crop($point, $box);
            // $imagine->save($this->getFilePath());
            // return $this->save(false);
        }

        $imagine = $imagine->thumbnail($box, ManipulatorInterface::THUMBNAIL_OUTBOUND);
        $imagine->save($this->getFilePath());

        // create empty image to preserve aspect ratio of thumbnail
        // $thumb = Image::getImagine()->create($box, new Color('FFF', 100));

        // // calculate points
        // $startX = 0;
        // $startY = 0;
        // if ($size->getWidth() < $box->getWidth()) {
        //     $startX = ceil($box->getWidth() - $size->getWidth()) / 2;
        // }
        // if ($size->getHeight() < $box->getHeight()) {
        //     $startY = ceil($box->getHeight() - $size->getHeight()) / 2;
        // }

        // $thumb->paste($img, new Point($startX, $startY));
        // $thumb->save($this->getFilePath());

        return $this->save(false);
    }

    /**
     * Get file path
     *
     * @return string
     */
    public function getFilePath()
    {
        if(empty($this->filename)) return false;
        return $this->basePath.'/'.$this->filename;
    }

    /**
     * Get file url
     *
     * @return mixed
     */
    public function getFileUrl()
    {
        if(empty($this->filename)){
            return false;
        }
        if(!file_exists($this->getFilePath())) {
            return $this->getDefaultFileUrl();
        }
        return $this->baseUrl.'/'.$this->filename;
    }

    /**
     * Get default file url
     *
     * @return string
     */
    public function getDefaultFileUrl()
    {   
        return $this->baseUrl.'/default_image.png';
    }

    /**
     * Get assetable type
     *
     * @return string
     */
    public function getAssetableType()
    {
        return $this->assetable_type;
    }

    /**
     * Get thumbnail type
     *
     * @return string
     */
    public function getThumbnailType()
    {
        return $this->thumbnail;
    }

    /**
     * Generate unique file name
     *
     * @return void
     */
    public function genFilename()
    {
        $filename = $this->getAssetableType().$this->assetable_id.'_';
        $filename .= uniqid();
        if(!empty($this->getThumbnailType())) $filename .= '_'.$this->getThumbnailType();
        $filename .= '.'.$this->uploadedFile->extension;
        $this->filename = $filename;
    }

    /**
     * Get possible thumbnail names by assetable type
     *
     * @return array Array of possible thumbnail names
     */
    public function getThumbnails($assetableType)
    {
        switch ($assetableType) {
            default: return [];
        }
    }

    /**
     * Get all assets file for assetable entity
     *
     * @param int $assetableId
     * @param string $assetableType
     * @param string $thumbnail
     * @param boolean $single
     *
     * @return array[Asset] or Asset if $single == true
     */
    public static function getAssets($assetableId, $assetableType, $thumbnail, $single = false)
    {
        $query = Asset::find()
            ->where([
            'assetable_id' => $assetableId,
            'assetable_type' => $assetableType,
            'thumbnail' => $thumbnail,
        ]);

        if(!$single) {
            $models = $query->all();
            return $models;
        } 
        $model = $query->one();
        return $model == null ? new Asset : $model;
    }

    /**
     * @return Imagine\Image\Box Return size of image
     */
    public function getImageBox($size)
    {
        switch ($this->getAssetableType())
        {
            case self::ASSETABLE_POST:
                switch ($this->getThumbnailType())
                {
                    // Content slider
                    case self::THUMBNAIL_CONTENT: return new Box(565,355);
                    default: break;
                }
            default: break;
        }
        return new Box($size->getWidth(),$size->getHeight());
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            // If file is exist -> remove it
            if(file_exists($this->getFilePath()))
            {
                unlink($this->getFilePath());
            }
            return true;
        } else {
            return false;
        }
    }
}
