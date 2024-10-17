<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\forms\UserForm $userForm */
/** @var common\forms\UserInformationForm $userInformationForm */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="news-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($userForm, 'email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($userInformationForm, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($userInformationForm, 'surname')->textInput(['maxlength' => true]) ?>
    <?= $form->field($userInformationForm, 'telegram_id')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <label>
            <input type="checkbox" id="showAvatarField"> Загрузить аватар
        </label>
    </div>

    <div class="form-group" id="avatarUploadField" style="display:none;">
        <?= $form->field($userInformationForm, 'avatar')->fileInput(['id' => 'avatarInput', 'accept' => 'image/jpeg, image/png']) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$script = <<< JS
    $('#showAvatarField').change(function() {
        if ($(this).is(':checked')) {
            $('#avatarUploadField').show();
        } else {
            $('#avatarUploadField').hide();
        }
    });
JS;
$this->registerJs($script);
?>