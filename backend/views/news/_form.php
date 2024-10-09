<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\NewsForm $newsModel */
/** @var backend\forms\NewsFileForm $fileModel */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="news-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($newsModel, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($newsModel, 'content')->textarea(['rows' => 6]) ?>

    <?php if (Yii::$app->controller->action->id === 'create'): ?>
        <?= $this->render('_uploadForm', ['form' => $form, 'fileModel' => $fileModel]) ?>
    <?php endif ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>