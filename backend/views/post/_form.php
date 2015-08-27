<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\checkbox\CheckboxX;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model backend\models\Post */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php 
    echo $form->field($model, 'content')->widget(\vova07\imperavi\Widget::className(), [
        'settings' => [
            'lang' => 'ru',
            'minHeight' => 200,
            'plugins' => [
                'fullscreen',
                'table',
                'video',
                'fontcolor',
            ]
        ]
    ]);
    ?>

    <?php
    $pluginOptions = [
        'showUpload' => false,
        'showRemove' => false,
        'showCaption' => true,
        'overwriteInitial' => true,
        'initialPreviewShowDelete' => true,
        'dropZoneEnabled' => false,
        'browseLabel' => "Обзор...",
        'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png'],
        'uploadUrl' => "/admin/post/image-delete",
        'layoutTemplates' => [
            'actions' => "<div class='file-actions'>\n" .
            "    <div class='file-footer-buttons'>\n" .
            "        {delete}" .
            "    </div>\n" .
            "    <div class='clearfix'></div>\n" .
            "</div>",
        ],
        'previewSettings' => [
            'image' => ['width' => 'auto', 'height' => 'auto'],
        ],
    ];
    if ($mainImage->getFileUrl())
    {
        $pluginOptions['initialPreview'][] = Html::img($mainImage->getFileUrl());
        $pluginOptions['initialPreviewConfig'][] = [
            'caption' => '',
            'url' => '/admin/post/image-delete',
            'key' => $mainImage->id,
        ];
    }
    echo $form->field($model, 'mainImageFile')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
            'multiple' => false,
            'class' => 'jcrop',
            'data-crop-ratio' => 5/2,
        ],
        'pluginOptions' => $pluginOptions,
    ]);
    echo $form->field($model, 'mainImageCropData')
        ->hiddenInput(['id' => 'crop-data'])
        ->label(false);
    echo $form->field($model, 'mainImageRemove')
        ->hiddenInput()->label(false);
    ?>

    <?php
    $pluginOptions = [
        'showUpload' => false,
        'showRemove' => false,
        'showCaption' => true,
        'overwriteInitial' => false,
        'initialPreviewShowDelete' => true,
        'dropZoneEnabled' => false,
        'browseLabel' => "Обзор...",
        'allowedFileExtensions' => ['jpg', 'jpeg', 'gif', 'png'],
        // 'uploadUrl' => "/admin/post/image-delete",
        'layoutTemplates' => [
            'actions' => "<div class='file-actions'>\n" .
            "    <div class='file-footer-buttons'>\n" .
            "        {delete}" .
            "    </div>\n" .
            "    <div class='clearfix'></div>\n" .
            "</div>",
        ],
        'previewSettings' => [
            'image' => ['width' => 'auto', 'height' => '160px'],
        ],
    ];
    foreach ($sliderImages as $image) {
        if ($image->getFileUrl())
        {
            $pluginOptions['initialPreview'][] = Html::img($image->getFileUrl());
            $pluginOptions['initialPreviewConfig'][] = [
                'caption' => '',
                'url' => '/admin/post/image-delete',
                'key' => $image->id,
            ];
        }
    }
    
    echo $form->field($model, 'sliderImageFiles[]')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
            'multiple' => true,
        ],
        'pluginOptions' => $pluginOptions,
    ]);
    echo $form->field($model, 'sliderImageKeys')->hiddenInput()->label(false);
    ?>

    <?= $form->field($model, 'status')->widget(CheckboxX::classname(), ['pluginOptions'=>['threeState' => false]]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Изменить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
