<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var src\ticket\entities\TicketHistory $model */

$this->title = 'Create Ticket History';
$this->params['breadcrumbs'][] = ['label' => 'Ticket Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
