<?php

use src\user\entities\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var src\user\entities\User $model */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?php if (!$model->isActive()): ?>
            <?= Html::a('Восстановить', ['restore', 'id' => $model->id], [
                'class' => 'btn btn-success',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите восстановить этот элемент?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php else: ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif ?>

        <?= Html::a('Изменить пароль', ['change-password', 'id' => $model->id], ['class' => 'btn btn-secondary']) ?>
        <?= Html::a('Роли', ['roles', 'user_id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Квартиры(Жилое)', ['apartments', 'user_id' => $model->id], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Объекты(Работа)', ['houses', 'user_id' => $model->id], ['class' => 'btn btn-warning']) ?>
    </p>

    <div class="user-avatar" style="margin-bottom: 15px;">
        <?= Html::img($model?->userInformation?->avatar?->source ?? Yii::$app->request->hostInfo . '/images/default_avatar.png', [
            'alt' => 'Аватар пользователя',
            'class' => 'img-thumbnail',
            'style' => 'width:200px;height:200px;'
        ]) ?>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'username',
            'email:email',
            [
                'attribute' => 'userInformation.name',
                'label' => 'Имя',
            ],
            [
                'attribute' => 'userInformation.surname',
                'label' => 'Фамилия',
            ],
            [
                'attribute' => 'userInformation.telegram_id',
                'label' => 'Телеграм',
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->status === User::STATUS_ACTIVE  ? 'Активен' : 'Удален';
                },
            ],
            [
                'attribute' => 'created_at',
                'format' => ['datetime', 'php:Y-m-d H:i:s'],
            ],
            [
                'attribute' => 'updated_at',
                'format' => ['datetime', 'php:Y-m-d H:i:s'],
            ],
        ],
    ]) ?>

</div>
