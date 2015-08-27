<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $code string */

$this->title = $name;
?>
<div class="site-error">
    
    <table>
        <tr>
            <td>
                <div class="error-title">Помилка: <?= $code ?> </div>

                <div class="error-message"><?= Html::encode($name) ?></div>

                <a href="/" class="back">Повернутися на головну</a>
            </td>
        </tr>
    </table>

</div>
