<?php

use kartik\grid\GridView;
use yii\bootstrap5\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var src\news\entities\News $model */
/** @var backend\forms\NewsFileForm $fileModel */
/** @var ArrayDataProvider $fileDataProvider */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="news-view">
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
            'title',
            'content:ntext',
            [
                'label' => 'Автор',
                'value' => function ($model) {
                    return $model->author->username ?? 'Неизвестный автор';
                },
            ],
            'deleted:boolean',
            'created_at',
            'updated_at',
        ],
    ]) ?>

    <h2>Файлы</h2>

    <div class="form-group">
        <?= Html::button('Загрузить', [
            'class' => 'btn btn-primary',
            'data-bs-toggle' => 'modal',
            'data-bs-target' => '#uploadModal'
        ]) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $fileDataProvider,
        'summary' => 'Итого: ' . $fileDataProvider->getTotalCount(),
        'columns' => [
            [
                'label' => 'Ссылка',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a($model->file->source, $model->file->source, ['target' => '_blank']);
                },
            ],
            [
                'label' => 'Тип файла',
                'attribute' => 'type',
                'value' => function($model) {
                    return $model->file->type->name;
                },
            ],
            [
                'label' => 'Удалено',
                'attribute' => 'deleted',
                'format' => 'boolean',
                'value' => function($model) {
                    return $model->file->deleted;
                },
            ],
            [
                'label' => 'Действия',
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a('Удалить', ['file/delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите удалить этот файл?',
                            'method' => 'post',
                        ],
                    ]);
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
                    <?php $form = ActiveForm::begin(['action' => ['news/upload', 'id' => $model->id], 'options' => ['enctype' => 'multipart/form-data']]); ?>
                    <div class="upload-form">
                        <?= $this->render('_uploadForm', ['form' => $form, 'fileModel' => $fileModel]) ?>
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
</div>

<?php
$script = <<< JS
$(document).ready(function() {
    $('#previewImageInput').change(function(event) {
        $('#previewImageContainer').empty();
        var files = event.target.files;
        if (files.length > 0) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImageContainer').append('<img src="' + e.target.result + '" alt="Preview Image" style="max-width: 200px; margin-top: 10px;"/>');
            }
            reader.readAsDataURL(files[0]);
        }
    });

    $('#photosInput').change(function(event) {
        $('#photosPreviewContainer').empty();
        var files = event.target.files;
        if (files.length > 0) {
            for (var i = 0; i < files.length; i++) {
                var reader = new FileReader();
                reader.onload = (function(file) {
                    return function(e) {
                        $('#photosPreviewContainer').append('<img src="' + e.target.result + '" alt="' + file.name + '" style="max-width: 100px; margin: 10px;"/>');
                    };
                })(files[i]);
                reader.readAsDataURL(files[i]);
            }
        }
    });

    $('#documentsInput').change(function(event) {
        $('#documentsContainer').empty();
        var files = event.target.files;
        if (files.length > 0) {
            for (var i = 0; i < files.length; i++) {
                $('#documentsContainer').append('<div>' + files[i].name + '</div>');
            }
        }
    });
});
JS;
$this->registerJs($script);
?>