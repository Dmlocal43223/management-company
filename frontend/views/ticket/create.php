<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\forms\TicketForm $ticketForm */
/** @var backend\forms\NewsFileForm $fileForm */
/** @var array $apartments */
/** @var array $types */

$this->title = 'Создать заявку';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="news-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'ticketForm' => $ticketForm,
        'fileForm' => $fileForm,
        'types' => $types,
        'apartments' => $apartments,
    ]) ?>

</div>
