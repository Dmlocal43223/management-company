<?php

/* @var $this yii\web\View */
/* @var $model src\news\entities\News */

use yii\helpers\Html;
use yii\helpers\Url;

$url = Url::to(['view', 'id' => $model->id]);
?>

<div class="blog-news-item">
<!--    --><?php //if ($model->photo): ?>
<!--        <div>-->
<!--            <a href="--><?php //= Html::encode($url) ?><!--">-->
<!--                <img src="--><?php //= Html::encode($model->getThumbFileUrl('photo', 'blog_list')) ?><!--" alt=""-->
<!--                     class="img-responsive"/>-->
<!--            </a>-->
<!--        </div>-->
<!--    --><?php //endif; ?>
    <div class="h2"><a href="<?= Html::encode($url) ?>"><?= Html::encode($model->title) ?></a></div>
    <p class="text-muted small"><?= yii::$app->formatter->asDatetime($model->created_at); ?></p>

</div>


