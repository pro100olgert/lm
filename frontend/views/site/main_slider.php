<?php
/**
 * @var $this yii\web\View
 * @var $sliderPosts array Array of common\models\Post
**/
?>
<div class="bigSlider">
    <div class="arrows-preload"></div>
    <ul>
    <?php foreach ($sliderPosts as $post) {
        $image = $post->getMainImage();
        if(!$image->getFileUrl()) continue;

    ?>
        <li>
            <img src="<?= $image->getFileUrl() ?>">
            <div class="slide-wrapper">             
                <div class="intro">
                    <div class="title">
                        <?= $post->title ?>
                    </div>
                    <div class="link" data-target="content-<?= $post->id ?>">
                        Читати
                        <span class="arrow"></span>
                    </div>
                </div>
            </div>
        </li>
    <?php } ?>
    </ul>
</div>