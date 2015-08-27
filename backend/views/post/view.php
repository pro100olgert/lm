<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Post */
/* @var $mainImage common\models\Asset */
/* @var $sliderImages array of common\models\Asset */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="post-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить новость?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user.username',
            'title',
            'content:html',
            'created',
            'updated',
            [
                'attribute' => 'mainImageFile',
                'value' => Html::img($mainImage->getFileUrl()),
                'format' => 'html',
            ],
        ],
    ]) ?>

    <div class="panel panel-default">
        <div class="panel-heading"><strong>Изображения</strong></div>
        <div class="panel-body">
            <?php
            foreach($sliderImages as $image)
            {
                if(!empty($image->getFileUrl())) {
                    $img = Html::img($image->getFileUrl(), ['style' => 'height: 160px; margin: 0 10px 10px 0']);
                    echo Html::a($img, $image->getFileUrl(), ['target' => '_blank']);
                }
            }
            ?>
        </div>
    </div>

</div>
