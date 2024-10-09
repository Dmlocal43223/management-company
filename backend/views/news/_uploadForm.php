<?php

/** @var yii\web\View $this */
/** @var backend\forms\NewsFileForm $fileModel */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="upload-form">
    <?= $form->field($fileModel, 'previewImage')->fileInput(['id' => 'previewImageInput', 'accept' => 'image/jpeg, image/png']) ?>
    <div id="previewImageContainer"></div>

    <?= $form->field($fileModel, 'photos[]')->fileInput(['id' => 'photosInput', 'multiple' => true, 'accept' => 'image/jpeg, image/png']) ?>
    <div id="photosPreviewContainer"></div>

    <?= $form->field($fileModel, 'documents[]')->fileInput(['multiple' => true, 'id' => 'documentsInput', 'accept' => '.pdf, .doc, .docx, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document']) ?>
    <div id="documentsContainer"></div>
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