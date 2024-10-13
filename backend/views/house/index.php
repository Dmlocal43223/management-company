<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\search\HouseSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $streets */

$this->title = 'Объекты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="house-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать объект', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


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
            'number',
            [
                'label' => 'Улица',
                'value' => function ($model) {
                    return $model?->street?->name;
                },
                'filter' => Html::activeDropDownList($searchModel, 'street_id',
                    $streets,
                    ['prompt' => 'Выберите улицу', 'class' => 'form-control']
                ),
            ],
            'deleted:boolean',
            'created_at',
            'updated_at',
        ],
    ]); ?>


</div>
