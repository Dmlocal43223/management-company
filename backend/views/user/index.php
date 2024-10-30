<?php

use src\user\entities\User;
use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\search\UserSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::a($model->id, ['view', 'id' => $model->id]);
                },
            ],
            'username',
            'email:email',
            [
                'attribute' => 'status',
                'format' => 'text',
                'value' => function ($model) {
                    return $model->status == User::STATUS_ACTIVE ? 'Да' : 'Нет';
                },
                'filter' => [
                    User::STATUS_ACTIVE => 'Да',
                    User::STATUS_INACTIVE => 'Нет',
                ],
            ]
        ],
    ]); ?>


</div>
