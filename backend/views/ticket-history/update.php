<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var src\ticket\entities\TicketHistory $model */

$this->title = 'Update Ticket History: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Ticket Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="ticket-history-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
