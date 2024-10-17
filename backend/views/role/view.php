<?php
/** @var yii\web\View $this */
/** @var src\role\entities\Role $model */

use yii\bootstrap5\Html;
use yii\widgets\DetailView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Роли', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="locality-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Обновить', ['update', 'name' => $model->name], ['class' => 'btn btn-primary']) ?>

        <?= Html::a('Удалить', ['delete', 'name' => $model->name], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'method' => 'post',
            ],
        ]) ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Имя',
                'value' => $model->name,
            ],
            [
                'label' => 'Описание',
                'value' => $model->description,
            ],
        ],
    ]) ?>

</div>
