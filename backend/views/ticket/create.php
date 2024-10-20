<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\forms\TicketForm $ticketForm */
/** @var common\forms\TicketFileForm $fileForm */
/** @var array $types */
/** @var array $houses */

$this->title = 'Создать заявку';
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'ticketForm' => $ticketForm,
        'fileForm' => $fileForm,
        'types' => $types,
        'houses' => $houses
    ]) ?>

</div>
