<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var src\location\entities\Locality $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $regions */
?>

<div class="locality-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'region_id')->dropDownList($regions, ['prompt' => 'Выберите регион']) ?>

    <?php if (Yii::$app->controller->action->id === 'update'): ?>
        <?= $form->field($model, 'deleted')->checkbox() ?>
    <?php endif ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
