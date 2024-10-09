<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var src\news\entities\News $news */
/** @var backend\forms\NewsForm $newsModel */

$this->title = 'Обновить новость: ' . $news->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $news->title, 'url' => ['view', 'id' => $news->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="news-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'newsModel' => $newsModel,
    ]) ?>

</div>
