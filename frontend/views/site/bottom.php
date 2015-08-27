<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $blocks array Array of common\models\Block
**/
?>
<div id="about" class="anchor"></div>
<div class="who-we-are">
    <div class="who-we-are-wrapper">
        <?php foreach ($blocks as $key => $block){ 
            $class = $key % 2 ? 'big' : 'small';
            ?>
        <div>
            <div class="point <?= $class ?>">
                <div class="title">
                    <?= $block->name ?>
                </div>
                <div class="text">
                    <?= $block->content ?>
                </div>              
            </div>
        </div>
        <?php if ($key % 2) echo '<div class="clearfix"></div>'; ?>
        <?php } ?>
    </div>
</div>