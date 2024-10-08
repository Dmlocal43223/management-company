<?php

declare(strict_types=1);

namespace backend\forms;

class NewsForm
{
    public $title;
    public $content;
    public $previewImage;
    public $photos;
    public $documents;

    public function rules(): array
    {
        return [
            [['title', 'content'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['content'], 'string'],
            [['previewImage'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 2],
            [['photos'], 'file', 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 5, 'maxSize' => 1024 * 1024 * 2],
            [['documents'], 'file', 'extensions' => 'pdf, doc, docx', 'maxFiles' => 5, 'maxSize' => 1024 * 1024 * 5],
        ];
    }
}