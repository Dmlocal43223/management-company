<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var src\location\entities\House $model */
/** @var yii\data\ArrayDataProvider $tenantDataProvider */
/** @var yii\data\ArrayDataProvider $workerDataProvider */

$this->title = $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Объекты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="house-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>

        <?php if ($model->isDeleted()): ?>
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
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'number',
            [
                'attribute' => 'street_id',
                'value' => function($model) {
                    return $model?->street?->name;
                },
                'label' => 'Улица',
            ],
            'deleted:boolean',
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <h2>Жители</h2>
    <?= GridView::widget([
        'dataProvider' => $tenantDataProvider,
        'columns' => [
            'username',
            'age',
            'gender',
        ],
    ]); ?>

    <h2>Работники</h2>
    <?= GridView::widget([
        'dataProvider' => $workerDataProvider,
        'columns' => [
            'username',
            'position',
            'contact',
        ],
    ]); ?>

</div>
