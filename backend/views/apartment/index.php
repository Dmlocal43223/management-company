<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\search\ApartmentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $houses */

$this->title = 'Квартиры';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="apartment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать квартиру', ['create'], ['class' => 'btn btn-success']) ?>
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
                'label' => 'Объект',
                'value' => function ($model) {
                    return $model?->house?->number;
                },
                'filter' => Html::activeDropDownList($searchModel, 'house_id',
                    $houses,
                    ['prompt' => 'Выберите объект', 'class' => 'form-control']
                ),
            ],
            'deleted:boolean',
            'created_at',
            'updated_at',
        ],
    ]); ?>


</div>
