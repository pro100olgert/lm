<?php
/**
 * @var $this yii\web\View
 * @var $model common\models\Post
**/
?>

<div class="news-element">
    <div class="news-element-wrapper">
        <div class="news-element-title">
            <?= $model->title ?>
        </div>
        <div id="content-<?= $model->id ?>" class="news-element-text">
            <div class="full-content">
                <?= $model->content ?>
                <?php 
                    $images = $model->getSliderImages();
                    if(count($images) > 0){
                        echo '<div class="newsSlider"><ul>';
                        foreach ($images as $image) {
                            if($image->getFileUrl()) echo '<img src="'.$image->getFileUrl().'" />';
                        }
                        echo '</ul></div>';
                    }
                ?>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="navigation-block">
            <div class="date"><?= date('d.m.Y', strtotime($model->created)) ?></div>
            <div class="open-close toggle-button toggle-show" data-target="content-<?= $model->id ?>">
                <span>Читати</span>
                <div class="arrow"></div>
            </div>
        </div>              
    </div>          
    <div class="border-bottom"></div>   
</div>