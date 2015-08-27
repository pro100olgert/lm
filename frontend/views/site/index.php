<?php
use yii\helpers\Url;

/**
 * @var $this yii\web\View
 * @var $sliderPosts array Array of common\models\Post
 * @var $newsDataProvider array Array of yii\data\ActiveDataProvider
 * @var $blocks array Array of common\models\Block
**/

$this->title = 'Люди майбутнього';

echo $this->render('@frontend/views/site/main_slider', compact('sliderPosts'));
echo $this->render('@frontend/views/site/news_list', compact('newsDataProvider'));
echo $this->render('@frontend/views/site/bottom', compact('blocks'));

?>
