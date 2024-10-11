<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var src\location\entities\Locality $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Населенные пункты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="locality-view">

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
            'id',
            'name',
            [
                'label' => 'Регион',
                'value' => $model->region->name
            ],
            'deleted:boolean',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
