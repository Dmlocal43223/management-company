<?php

use backend\forms\search\NewsSearch;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\StringHelper;

/** @var yii\web\View $this */
/** @var NewsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Новости';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать новость', ['create'], ['class' => 'btn btn-success']) ?>
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
            'title',
            [
                'attribute' => 'content',
                'format' => 'raw',
                'value' => function ($model) {
                    return StringHelper::truncate(Html::encode($model->content), 250, '...');
                },
            ],
            'author_id',
            'deleted:boolean',
            'created_at',
            'updated_at',
        ],
    ]); ?>


</div>
