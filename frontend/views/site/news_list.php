<?php
use yii\web\JsExpression;

/**
 * @var $this yii\web\View
 * @var $news array Array of common\models\Post
**/
?>

<div id="news" class="anchor"></div>
<div class="news">
    <div class="news-wrapper">
        <div class="title">
            Останні новини
        </div>
        
        <?php
        echo \yii\widgets\ListView::widget([
            'dataProvider' => $newsDataProvider,
            'itemOptions' => ['class' => 'item'],
            'itemView' => '@frontend/views/site/news_item',
            'pager' => [
                'class' => \kop\y2sp\ScrollPager::className(),
                'delay' => 0,
                'noneLeftText' => 'Больше нет новостей',
                'triggerOffset' => 0,
                'triggerText' => 'Більше новин...',
                'triggerTemplate' => '<div class="title more-news">{text}</div>',
                'eventOnRendered' => new JsExpression('function(items) { 
                    var $items = $(items);
                    $items.ready(function(){
                        $items.find(".newsSlider ul").bxSlider({
                            slideHeight: 355,
                            mode: "fade"
                        });
                        $items.find(".news-element").each(function(index, el) {
                            var fullHeight = $(this).find(".full-content").first().innerHeight();
                            if(fullHeight <= 216) $(this).find(".open-close").hide();
                        });
                        $(".bigSlider .link").each(function(index, el) {
                            var $target = $("#" + $(this).attr("data-target"));
                            if($target.length !== 0) {
                                $(this).text("Читати");
                            }
                        });
                    });
                }'),
                'eventOnPageChange' => new JsExpression('function(a,b,c) { 
                    // $(document).ready(function(){
                    //     if (window.history.replaceState) {
                    //        //prevents browser from storing history with each change:
                    //        window.history.replaceState({foo: "bat"}, "", "?asd");
                    //     }
                    // });
                    // console.log(a);
                    // console.log(b);
                    // console.log(c);
                    // console.log(window.history.state);
                    // var path = "?asd";
                    // window.history.replaceState({foo: "bat"}, "New Title");
                    // window.history.pushState({path:""},"","");
                }'),
                // 'spinnerTemplate' => '<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>',
             ],
             'summary' => '', 
        ]);
        ?>
    </div>
</div>
