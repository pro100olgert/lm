<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;

use common\models\Settings;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>
    <div id="page">
        <header>
            <div class="header-wrapper">
                <div class="logo"></div>
                <ul class="menu">
                <?php 
                    $menuItems = \common\models\Menu::find()->orderBy(['weight' => SORT_DESC])->all();
                    foreach ($menuItems as $item) {
                        echo '<li><a href="' . $item->url . '">' . $item->title . '</a></li>';
                    }
                ?>
                </ul>
            </div>
        </header>

        <?php // echo Alert::widget(); ?>
        <?= $content ?>


        <?php 
            // Settings
            $email = Settings::findOne(1);
            $address = Settings::findOne(2);
            $fbLink = Settings::findOne(3);
            $vkLink = Settings::findOne(4);
            $utubeLink = Settings::findOne(5);
        ?>
        <footer class="site-footer">
            <div class="footer-wrapper">
            <div class="social-buttons-preload"></div>
                <div class="logo"></div>
                <div class="contacts">
                    <div class="social-buttons">
                        <div class="social-button twitter">
                            <a href="<?= $fbLink->getValue() ?>" target="_blank"></a>
                        </div>
                        <div class="social-button vk">
                            <a href="<?= $vkLink->getValue() ?>" target="_blank"></a>
                        </div>
                        <div class="social-button youtube">
                            <a href="<?= $utubeLink->getValue() ?>" target="_blank"></a>
                        </div>                   
                    </div>
                    <div class="adress">
                        <?php 
                            $email = Settings::findOne(1);
                            $address = Settings::findOne(2);
                        ?>
                        <div class="location">
                            <?= $address->getValue() ?>
                        </div>
                        <a class="email" href="mailto:<?= $email->getValue() ?>">
                            <?= $email->getValue() ?>
                        </a>
                    </div>
                </div>      
            </div>
        </footer><!-- .site-footer -->
    </div>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
