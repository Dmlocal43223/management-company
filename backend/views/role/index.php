<?php
/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Список ролей';
$this->params['breadcrumbs'][] = $this->title;
?>
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать роль', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'name',
            'label' => 'Название',
            'format' => 'raw',
            'value' => function($model) {
                return Html::a(Html::encode($model->name), ['role/view', 'name' => $model->name]);
            },
        ],
        [
            'attribute' => 'description',
            'label' => 'Описание',
        ],
    ],
]); ?>