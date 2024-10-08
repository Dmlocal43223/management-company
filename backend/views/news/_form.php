<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var src\news\entities\News $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="news-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['rows' => 6]) ?>

    <?php if (Yii::$app->controller->action->id === 'update'): ?>
        <?= $form->field($model, 'deleted')->checkbox() ?>
    <?php endif ?>

    <?= $form->field($model, 'preview_image')->fileInput(['id' => 'previewImageInput']) ?> <!-- Для изображения превью -->
    <div id="previewImageContainer"></div> <!-- Контейнер для отображения превью изображений -->

    <?= $form->field($model, 'documents[]')->fileInput(['multiple' => true, 'id' => 'documentsInput']) ?> <!-- Для других документов -->
    <div id="documentsContainer"></div> <!-- Контейнер для отображения выбранных документов -->

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<< JS
$(document).ready(function() {
    // Отображение выбранного изображения превью
    $('#previewImageInput').change(function(event) {
        $('#previewImageContainer').empty(); // Очищаем контейнер
        var files = event.target.files;
        if (files.length > 0) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImageContainer').append('<img src="' + e.target.result + '" alt="Preview Image" style="max-width: 200px; margin-top: 10px;"/>');
            }
            reader.readAsDataURL(files[0]); // Читаем первое изображение
        }
    });

    // Отображение выбранных документов
    $('#documentsInput').change(function(event) {
        $('#documentsContainer').empty(); // Очищаем контейнер
        var files = event.target.files;
        if (files.length > 0) {
            for (var i = 0; i < files.length; i++) {
                $('#documentsContainer').append('<div>' + files[i].name + '</div>'); // Отображаем имя документа
            }
        }
    });
});
JS;
$this->registerJs($script);
?>
