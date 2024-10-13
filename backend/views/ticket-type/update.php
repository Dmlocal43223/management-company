<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var src\ticket\entities\TicketType $model */
/** @var backend\forms\TicketTypeForm $ticketTypeForm */

$this->title = 'Обновить тип заявки: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Типы заявок', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="ticket-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'ticketTypeForm' => $ticketTypeForm,
    ]) ?>

</div>
