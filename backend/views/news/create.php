<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\NewsForm $newsForm */
/** @var backend\forms\NewsFileForm $fileForm */

$this->title = 'Создать новость';
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'newsForm' => $newsForm,
        'fileForm' => $fileForm,
    ]) ?>

</div>
