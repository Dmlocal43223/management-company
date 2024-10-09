<?php

use backend\forms\search\NewsSearchForm;
use kartik\grid\GridView;
use src\news\entities\News;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var NewsSearchForm $searchModel */
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
            'content:ntext',
            'author_id',
            'deleted:boolean',
            'created_at',
            'updated_at',
        ],
    ]); ?>


</div>
