<?php

/* @var $this yii\web\View */
/* @var $model src\news\entities\News */
/* @var $photos array */
/* @var $documents array */

use yii\helpers\Html;

$this->title = 'Новость';
$this->params['breadcrumbs'][] = ['label' => 'Новости', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->title;
?>

<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<article class="container my-5 p-4 border rounded shadow-sm bg-light">
    <h1 class="mb-2"><?= Html::encode($model->title) ?></h1>
    <p class="text-muted small mb-2"><?= $model->author->getFullName() ?>, <?= Yii::$app->formatter->asDatetime($model->created_at); ?></p>

    <?php if (!empty($photos)): ?>
        <div id="imageCarousel" class="carousel slide border" data-ride="carousel">
            <div class="carousel-inner">
                <?php foreach ($photos as $index => $photo): ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <img src="<?= Html::encode($photo) ?>" class="d-block w-100" alt="Image <?= $index + 1 ?>">
                    </div>
                <?php endforeach; ?>
            </div>

            <a class="carousel-control-prev" href="#imageCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#imageCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>

            <?php if (count($photos) > 1): ?>
                <ol class="carousel-indicators">
                    <?php foreach ($photos as $index => $photo): ?>
                        <li data-target="#imageCarousel" data-slide-to="<?= $index ?>" class="<?= $index === 0 ? 'active' : '' ?>"></li>
                    <?php endforeach; ?>
                </ol>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="mt-2" style="white-space:pre-line">
        <?= Yii::$app->formatter->asHtml(Html::encode(trim($model->content)), [
            'Attr.AllowedRel' => ['nofollow'],
            'HTML.SafeObject' => true,
            'Output.FlashCompat' => true,
            'HTML.SafeIframe' => true,
            'URI.SafeIframeRegexp' => '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%'
        ]) ?>
    </div>

    <?php if (!empty($documents)): ?>
        <p class="mt-2"><strong>Документы:</strong></p>
        <ol class="list-unstyled">
            <?php foreach ($documents as $document): ?>
                <li>
                    <a href="<?= Html::encode($document) ?>" target="_blank"><?= Html::encode(basename($document)) ?></a>
                </li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>
</article>

<style>
    body {
        background-color: #f8f9fa;
    }

    .carousel-indicators {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 15;
        display: flex;
        justify-content: center;
        width: 100%;
        margin: 0;
    }

    .carousel-indicators li {
        background-color: rgba(255, 255, 255, 0.7);
        border-radius: 50%;
        width: 12px;
        height: 12px;
        margin: 3px;
    }

    .carousel-indicators .active {
        background-color: rgba(230, 230, 230, 1);
    }

    .carousel-inner img {
        max-width: 100%;
        max-height: 500px;
        height: auto;
        object-fit: contain;
        border-radius: 5px;
    }

    #imageCarousel {
        width: 80%;
        max-width: 800px;
        height: auto;
        overflow: hidden;
        position: relative;
        margin: 0 auto;
    }
</style>
