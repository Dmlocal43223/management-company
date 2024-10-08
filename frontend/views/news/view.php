<?php

/* @var $this yii\web\View */
/* @var $model src\news\entities\News */

use yii\helpers\Html;

$this->title = 'Новость';

$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;
?>

<article>
    <h1><?= Html::encode($model->title) ?></h1>

    <p class="text-muted small"> <?= Yii::$app->formatter->asDatetime($model->created_at); ?></p>

<!--    --><?php //if ($post->photo): ?>
<!--        <p><img src="--><?php //= Html::encode($post->getThumbFileUrl('photo', 'origin')) ?><!--" alt="" class="img-responsive" /></p>-->
<!--    --><?php //endif; ?>

    <?= Yii::$app->formatter->asHtml($model->content, [
        'Attr.AllowedRel' => array('nofollow'),
        'HTML.SafeObject' => true,
        'Output.FlashCompat' => true,
        'HTML.SafeIframe' => true,
        'URI.SafeIframeRegexp'=>'%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%',
    ]) ?>
</article>



