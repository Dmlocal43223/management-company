<?php

/** @var yii\web\View $this */
/** @var backend\forms\NewsFileForm $fileForm */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="upload-form">
    <?= $form->field($fileForm, 'previewImage')->fileInput(['id' => 'previewImageInput', 'accept' => 'image/jpeg, image/png']) ?>
    <div id="previewImageContainer"></div>

    <?= $form->field($fileForm, 'photos[]')->fileInput(['id' => 'photosInput', 'multiple' => true, 'accept' => 'image/jpeg, image/png']) ?>
    <div id="photosPreviewContainer"></div>

    <?= $form->field($fileForm, 'documents[]')->fileInput(['multiple' => true, 'id' => 'documentsInput', 'accept' => '.pdf, .doc, .docx, application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document']) ?>
    <div id="documentsContainer"></div>
</div>

<?php
$script = <<< JS
$(document).ready(function() {
    var reader = new FileReader();

    $('#previewImageInput').change(function(event) {
        $('#previewImageContainer').empty();
        var files = event.target.files;
        if (files.length > 0) {
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