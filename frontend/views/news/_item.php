<?php

/* @var $this yii\web\View */
/* @var $model src\news\entities\News */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="news-item">
    <div class="news-preview">
        <?php if ($model->previewFile): ?>
            <img src="<?= Html::encode($model->previewFile->source) ?>" alt="Preview" class="news-preview-image">
        <?php else: ?>
            <img src="<?= Html::encode(Yii::$app->request->hostInfo . '/images/default_preview.jpg') ?>" alt="Default Preview" class="news-preview-image">
        <?php endif; ?>
    </div>
    <div class="news-title">
        <h2><a href="<?= Html::encode(Url::to(['view', 'id' => $model->id])) ?>"><?= Html::encode($model->title) ?></a></h2>
        <p class="text-muted small"><?= yii::$app->formatter->asDatetime($model->created_at); ?></p>
    </div>
</div>

