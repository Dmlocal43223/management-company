<?php

/* @var $this yii\web\View */
/* @var $model src\location\entities\House */

use yii\helpers\Html;
?>

<div class="house-item">
    <div class="house-title">
        <h2><?= Html::encode($model->getAddress()) ?></h2>
    </div>
</div>

