<?php

/* @var $this yii\web\View */
/* @var $model src\news\entities\News */

use yii\helpers\Html;
use yii\helpers\Url;

$url = Url::to(['view', 'id' => $model->id]);
?>

<div class="news-item">
    <div class="news-preview">
        <img src="<?= Html::encode('http://localhost:21080/uploads/previews/file_6706fc6c162cc7.04947104.png') ?>" alt="Preview" class="news-preview-image">
    </div>
    <div class="news-title">
        <h2><a href="<?= Html::encode($url) ?>"><?= Html::encode($model->title) ?></a></h2>
        <p class="text-muted small"><?= yii::$app->formatter->asDatetime($model->created_at); ?></p>
    </div>
</div>

