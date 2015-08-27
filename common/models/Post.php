<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use amnah\yii2\user\models\User;

use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Image\Color;
use Imagine\Image\ManipulatorInterface;

/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $content
 * @property string $created
 * @property string $updated
 * @property boolean $status
 * @property file $company_status
 *
 * @property User $user
 */
class Post extends ActiveRecord
{
    /**
     * @var File The main image
     */
    public $mainImageFile;
    public $mainImageRemove;
    public $mainImageCropData;
    /**
     * @var array File[] Array of slider images
     */
    public $sliderImageFiles;
    public $sliderImageKeys;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['title', 'content'], 'required'],
            [['content'], 'string'],
            [['created', 'updated', 'mainImageRemove', 'mainImageCropData', 'sliderImageKeys'], 'safe'],
            [['title'], 'string', 'max' => 150],
            [['mainImageFile'], 'file', 'extensions' => 'jpeg, jpg, gif, png'],
            [['sliderImageFiles'], 'file', 'maxFiles' => 10, 'extensions' => 'jpeg, jpg, gif, png']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'PID',
            'user_id' => 'UID',
            'title'   => 'Заголовок',
            'content' => 'Содержимое',
            'created' => 'Создано',
            'updated' => 'Обновлено',
            'status'  => 'Опубликовано',
            'mainImageFile'  => 'Изображение в главный слайдер',
            'sliderImageFiles'  => 'Изображения',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class'      => 'yii\behaviors\TimestampBehavior',
                'value'      => function () { return date("Y-m-d H:i:s"); },
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated',
                ],
            ],
        ];
    }

    /**
     * Get an array of Asset
     * 
     * @return array 
     */
    public function getSliderImages()
    {
        return Asset::getAssets($this->id, Asset::ASSETABLE_POST, Asset::THUMBNAIL_CONTENT);
    }

    /**
     * Get single asset
     *
     * @return Asset
     */
    public function getMainImage()
    {
        return Asset::getAssets($this->id, Asset::ASSETABLE_POST, NULL, true);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

}
