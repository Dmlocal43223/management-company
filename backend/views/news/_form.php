<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\NewsForm $newsForm */
/** @var backend\forms\NewsFileForm $fileForm */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="news-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($newsForm, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($newsForm, 'content')->textarea(['rows' => 6]) ?>

    <?php if (Yii::$app->controller->action->id === 'create'): ?>
        <?= $this->render('_uploadForm', ['form' => $form, 'fileForm' => $fileForm]) ?>
    <?php endif ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>