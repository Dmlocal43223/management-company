<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var common\forms\LoginForm $loginForm */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Войти';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста, заполните следующие поля для входа:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($loginForm, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($loginForm, 'password')->passwordInput() ?>

            <?= $form->field($loginForm, 'rememberMe')->checkbox() ?>

            <div class="form-group">
                <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                <?php if (Yii::$app->user->isGuest): ?>
                    <?= Html::a('Регистрация', ['/site/signup'], ['class' => 'btn btn-secondary ml-2']) ?>
                <?php endif; ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
