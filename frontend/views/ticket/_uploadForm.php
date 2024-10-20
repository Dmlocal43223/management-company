<?php

use frontend\forms\TicketFileForm;

/** @var yii\web\View $this */
/** @var TicketFileForm $fileForm */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="upload-files-form">
    <h3>Загрузка файлов в заявку</h3>

    <?= $form->field($fileForm, 'photos[]')->fileInput(['id' => 'photosInput', 'multiple' => true, 'accept' => 'image/*'])->label('Загрузите фотографии (до 5 файлов, формат png, jpg, jpeg)') ?>
    <div id="photosPreviewContainer"></div>

    <?= $form->field($fileForm, 'documents[]')->fileInput(['id' => 'documentsInput', 'multiple' => true, 'accept' => '.pdf,.doc,.docx'])->label('Загрузите документы (до 5 файлов, формат pdf, doc, docx)') ?>
    <div id="documentsContainer"></div>
</div>

<?php
$script = <<< JS
$(document).ready(function() {
    var reader = new FileReader();
    
    $('#photosInput').change(function(event) {
        $('#photosPreviewContainer').empty();
        var files = event.target.files;
        if (files.length > 0) {
            reader.onload = function(e) {};
            for (var i = 0; i < files.length; i++) {
                (function(file) {
                    reader.onload = function(e) {
                        $('#photosPreviewContainer').append('<img src="' + e.target.result + '" alt="' + file.name + '" style="max-width: 100px; margin: 10px;"/>');
                    };
                    reader.readAsDataURL(file);
                })(files[i]);
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