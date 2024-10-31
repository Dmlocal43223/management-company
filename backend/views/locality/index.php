<?php

use src\location\entities\Locality;
use src\location\entities\Region;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var backend\forms\search\LocalitySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var array $regions */

$this->title = 'Населенные пункты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="locality-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать населенный пункт', ['create'], ['class' => 'btn btn-success']) ?>
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
            'name',
            [
                'label' => 'Регион',
                'value' => function ($model) {
                    return $model->region->name;
                },
                'filter' => Html::activeDropDownList($searchModel, 'region_id',
                    $regions,
                    ['prompt' => 'Выберите регион', 'class' => 'form-control']
                ),
            ],
            'deleted:boolean',
        ],
    ]); ?>


</div>
