<?php

namespace backend\forms;

use yii\base\Model;
use yii\web\UploadedFile;

class NewsFileForm extends Model
{
    public $previewImage;
    public $photos;
    public $documents;

    public function rules(): array
    {
        return [
            [['previewImage'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 2],
            [['photos'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 5, 'maxSize' => 1024 * 1024 * 2],
            [['documents'], 'file', 'extensions' => 'pdf, doc, docx', 'maxFiles' => 5, 'maxSize' => 1024 * 1024 * 5],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'previewImage' => 'Превью',
            'photos' => 'Фотографии',
            'documents' => 'Документы',
        ];
    }

    public function beforeValidate(): bool
    {
        if (is_array($this->documents)) {
            $this->documents = array_filter($this->documents, function ($file) {
                return $file instanceof UploadedFile && !empty($file->name);
            });
        }

        if (is_array($this->photos)) {
            $this->photos = array_filter($this->photos, function ($file) {
                return $file instanceof UploadedFile && !empty($file->name);
            });
        }

        return parent::beforeValidate();
    }

    public function setUploadedFiles(): void
    {
        $this->previewImage = UploadedFile::getInstance($this, 'previewImage');
        $this->photos = UploadedFile::getInstances($this, 'photos');
        $this->documents = UploadedFile::getInstances($this, 'documents');

    }
}