<?php

/* @var $this yii\web\View */
/* @var $model src\user\entities\User */

use yii\helpers\Html;

$this->title = 'Профиль пользователя: ' . $model->username;
?>

<div class="profile-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать профиль', ['update'], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Изменить пароль', ['change-password'], ['class' => 'btn btn-secondary']) ?>
    </p>

    <div class="profile-info" style="border: 1px solid #ddd; border-radius: 5px; padding: 20px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
        <div class="profile-avatar" style="margin-bottom: 15px;">
            <?= Html::img($model?->userInformation?->avatar?->source ?? Yii::$app->request->hostInfo . '/images/default_avatar.png', [
                'alt' => 'Аватар пользователя',
                'class' => 'img-thumbnail',
                'style' => 'width:150px;height:150px;'
            ]) ?>
        </div>
        <div class="profile-details">
            <p><strong>Имя:</strong> <?= Html::encode($model->userInformation->name) ?></p>
            <p><strong>Фамилия:</strong> <?= Html::encode($model->userInformation->surname) ?></p>
            <p><strong>Email:</strong> <?= Html::encode($model->email) ?></p>
            <p><strong>Телеграм:</strong> <?= Html::encode($model->userInformation->telegram_id ?? 'Отсутствует') ?></p>
            <p><strong>Дата регистрации:</strong> <?= Yii::$app->formatter->asDate($model->created_at) ?></p>
        </div>
    </div>
</div>

