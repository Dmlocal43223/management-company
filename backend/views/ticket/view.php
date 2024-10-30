<?php

use kartik\grid\GridView;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var src\ticket\entities\Ticket $model */
/** @var yii\data\ArrayDataProvider $historyDataProvider */
/** @var yii\data\ArrayDataProvider $fileDataProvider */
/** @var common\forms\TicketFileForm $fileForm */
/** @var backend\forms\TicketCloseForm $ticketCloseForm */
/** @var backend\forms\TicketAssignForm $ticketAssignForm */
/** @var array $closingStatuses */
/** @var array $workers */

    $this->title = $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Заявки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ticket-view">

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

        <?= Html::button('Назначить', [
            'class' => 'btn btn-secondary',
            'data-bs-toggle' => 'modal',
            'data-bs-target' => '#assignTicketModal'
        ]) ?>
        <?= Html::button('Закрыть', [
            'class' => 'btn btn-success',
            'data-bs-toggle' => 'modal',
            'data-bs-target' => '#closeTicketModal'
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'number',
            [
                'attribute' => 'status_id',
                'label' => 'Статус',
                'value' => function ($model) {
                    return $model->status->name;
                },
            ],
            [
                'label' => 'Работник',
                'value' => function ($model) {
                    return $model?->worker?->getFullName();
                },
            ],
            'description:ntext',
            [
                'attribute' => 'house_id',
                'label' => 'Объект',
                'value' => function ($model) {
                    return $model->house->getAddress();
                },
            ],
            [
                'attribute' => 'apartment_id',
                'label' => 'Квартира',
                'value' => function ($model) {
                    return $model?->apartment?->number;
                },
            ],
            [
                'attribute' => 'type_id',
                'label' => 'Тип',
                'value' => function ($model) {
                    return $model->type->name;
                },
            ],
            'closed_at',
            'created_at',
        ],
    ]) ?>

    <h2>История заявки</h2>
    <?= GridView::widget([
        'dataProvider' => $historyDataProvider,
        'columns' => [
            [
                'attribute' => 'status_id',
                'label' => 'Статус',
                'value' => function ($model) {
                    return $model->status->name;
                },
            ],
            [
                'attribute' => 'reason',
                'label' => 'Комментарий',
            ],
            [
                'attribute' => 'created_at',
                'label' => 'Дата',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],
        ],
    ]); ?>

    <h2>Документы заявки</h2>

    <div class="form-group">
        <?= Html::button('Загрузить', [
            'class' => 'btn btn-primary',
            'data-bs-toggle' => 'modal',
            'data-bs-target' => '#uploadModal'
        ]) ?>
    </div>

    <br>

    <?= GridView::widget([
        'dataProvider' => $fileDataProvider,
        'summary' => 'Итого: ' . $fileDataProvider->getTotalCount(),
        'columns' => [
            [
                'label' => 'Ссылка',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a($model->source, $model->source, ['target' => '_blank']);
                },
            ],
            [
                'label' => 'Тип файла',
                'attribute' => 'type',
                'value' => function($model) {
                    return $model->type->name ?? 'Тип неизвестен';
                },
            ],
            [
                'label' => 'Удалено',
                'attribute' => 'deleted',
                'format' => 'boolean',
                'value' => function($model) {
                    return $model->deleted;
                },
            ],
            [
                'label' => 'Действия',
                'format' => 'raw',
                'value' => function($model) {
                    if ($model->isDeleted()) {
                        return Html::a('Восстановить', ['file/restore', 'id' => $model->id], [
                            'class' => 'btn btn-success',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите восстановить этот файл?',
                                'method' => 'post',
                            ],
                        ]);
                    } else {
                        return Html::a('Удалить', ['file/delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить этот файл?',
                                'method' => 'post',
                            ],
                        ]);
                    }
                },
            ],
        ],
    ]); ?>

    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Загрузка файлов</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <?php $form = ActiveForm::begin(['action' => ['ticket/upload', 'id' => $model->id], 'options' => ['enctype' => 'multipart/form-data']]); ?>
                    <div class="upload-form">
                        <?= $this->render('_uploadForm', ['form' => $form, 'fileForm' => $fileForm]) ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    <?= Html::submitButton('Загрузить', ['class' => 'btn btn-primary']) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="closeTicketModal" tabindex="-1" aria-labelledby="closeTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="closeTicketModalLabel">Закрытие заявки</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <?= $this->render('_closeForm', [
                        'model' => $model,
                        'ticketCloseForm' => $ticketCloseForm,
                        'closingStatuses' => $closingStatuses
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="assignTicketModal" tabindex="-1" aria-labelledby="assignTicketModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignTicketModalLabel">Закрытие заявки</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <?= $this->render('_assignForm', [
                        'model' => $model,
                        'ticketAssignForm' => $ticketAssignForm,
                        'workers' => $workers
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

</div>

<?php
$script = <<< JS
function previewFiles(inputSelector, containerSelector, isImage = false) {
    $(inputSelector).off('change').on('change', function(event) { 
        $(containerSelector).empty();
        var files = event.target.files;
        if (files.length > 0) {
            for (var i = 0; i < files.length; i++) {
                if (isImage) {
                    var reader = new FileReader();
                    reader.onload = (function(file) {
                        return function(e) {
                            $(containerSelector).append('<img src="' + e.target.result + '" alt="' + file.name + '" style="max-width: 100px; margin: 10px;"/>');
                        };
                    })(files[i]);
                    reader.readAsDataURL(files[i]);
                } else {
                    $(containerSelector).append('<div>' + files[i].name + '</div>');
                }
            }
        }
    });
}

function toggleSubmitButton() {
    const submitButton = $('#uploadModal .btn-primary');
    const photosInput = $('#photosInput')[0];
    const documentsInput = $('#documentsInput')[0];
    
    const hasFiles = photosInput.files.length > 0 || documentsInput.files.length > 0;
    submitButton.prop('disabled', !hasFiles);
}

$(document).ready(function() {
    previewFiles('#photosInput', '#photosPreviewContainer', true);
    previewFiles('#documentsInput', '#documentsContainer', false);
    
    $('#photosInput').change(toggleSubmitButton);
    $('#documentsInput').change(toggleSubmitButton);

    toggleSubmitButton();
});
JS;
$this->registerJs($script);
?>
