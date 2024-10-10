<?php

/* @var $this yii\web\View */
/* @var $model src\news\entities\News */

use yii\helpers\Html;

$this->title = 'Новость';

$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;
?>

<article class="container my-5 p-4 border rounded shadow-sm bg-light">
    <h1 class="mb-4"><?= Html::encode($model->title) ?></h1>
    <p class="text-muted small mb-3"><?= Yii::$app->formatter->asDatetime($model->created_at); ?></p>

    <div id="imageCarousel" class="carousel slide border" data-ride="carousel">
        <div class="carousel-inner">
            <?php
            $images = [
                'http://localhost:21080/uploads/previews/file_6706fc6c162cc7.04947104.png',
                'http://localhost:21080/uploads/photos/file_670701622b58b0.38233216.png'
            ];
            foreach ($images as $index => $image): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <img src="<?= Html::encode($image) ?>" class="d-block w-100" alt="Image <?= $index + 1 ?>">
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Стрелочки -->
        <a class="carousel-control-prev" href="#imageCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#imageCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>

        <!-- Индикаторы -->
        <?php if (count($images) > 1): ?>
            <ol class="carousel-indicators">
                <?php foreach ($images as $index => $image): ?>
                    <li data-target="#imageCarousel" data-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>"></li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>
    </div>

    <div class="mt-4" style="white-space: pre-wrap;">
        <?= Yii::$app->formatter->asHtml(Html::encode(trim($model->content)), [
            'Attr.AllowedRel' => ['nofollow'],
            'HTML.SafeObject' => true,
            'Output.FlashCompat' => true,
            'HTML.SafeIframe' => true,
            'URI.SafeIframeRegexp' => '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%'
        ]) ?>
    </div>

    <p class="mt-4"><strong>Документы:</strong></p>
    <ul class="list-unstyled">
        <?php
        $documents = [
            ['name' => 'Документ 1', 'link' => 'http://localhost:21080/uploads/docs/doc1.pdf'],
            ['name' => 'Документ 2', 'link' => 'http://localhost:21080/uploads/docs/doc2.pdf'],
            // Добавьте больше документов по необходимости
        ];
        foreach ($documents as $document): ?>
            <li>
                <a href="<?= Html::encode($document['link']) ?>" target="_blank"><?= Html::encode($document['name']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</article>

<!-- Подключение Bootstrap (если еще не подключено) -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Добавление стилей для рамки и стрелочек -->
<style>
    body {
        background-color: #f8f9fa; /* Светлый фон для всего тела */
    }

    .carousel-indicators {
        position: absolute; /* Позволяет разместить индикаторы внутри контейнера карусели */
        bottom: 20px; /* Немного поднять индикаторы выше */
        left: 50%; /* Сдвигаем влево на 50% ширины контейнера */
        transform: translateX(-50%); /* Центрируем индикаторы по горизонтали */
        z-index: 15;
        display: flex; /* Используем flex для центрирования */
        justify-content: center; /* Центрируем содержимое по горизонтали */
        width: 100%; /* Ширина 100% для правильного центрирования */
        margin: 0; /* Убираем отступы */
    }

    .carousel-indicators li {
        background-color: rgba(255, 255, 255, 0.7); /* Убираем полупрозрачность для неактивных кружочков */
        border-radius: 50%; /* Круглая форма */
        width: 12px; /* Размер индикаторов */
        height: 12px;
        margin: 3px; /* Расстояние между кружочками */
    }

    .carousel-indicators .active {
        background-color: rgba(230, 230, 230, 1); /* Активный индикатор черный */
    }

    .carousel-inner img {
        max-width: 100%; /* Ограничивает ширину изображения до 100% родительского контейнера */
        max-height: 500px; /* Устанавливает максимальную высоту в 500 пикселей */
        height: auto; /* Высота будет изменяться пропорционально ширине */
        object-fit: contain; /* Сохраняет пропорции, не обрезая изображение */
        border-radius: 5px; /* Радиус для изображений */
    }

    #imageCarousel {
        width: 80%; /* Задайте ширину карусели (например, 80% от родительского контейнера) */
        max-width: 800px; /* Установите максимальную ширину, если нужно */
        height: auto; /* Авто высота для адаптации */
        overflow: hidden; /* Скрыть лишнее содержимое */
        position: relative; /* Для правильного позиционирования стрелок и индикаторов */
        margin: 0 auto; /* Центрирование карусели */
    }
</style>


