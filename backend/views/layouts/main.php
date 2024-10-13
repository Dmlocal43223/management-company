<?php

/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
\yii\bootstrap5\BootstrapAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    $menuItems = [
        [
            'label' => 'Пользователи',
            'dropDownOptions' => ['class' => 'dropdown-menu'],
            'items' => [
                [
                    'label' => 'Пользователи',
                    'url' => ['/user/index'],
                ],
                [
                    'label' => 'Роли',
                    'url' => ['/role/index'],
                ],
                [
                    'label' => 'Расписание',
                    'url' => ['/user-schedule/index'],
                ]
            ]
        ],
        [
            'label' => 'Заявки',
            'dropDownOptions' => ['class' => 'dropdown-menu'],
            'items' => [
                [
                    'label' => 'Заявки',
                    'url' => ['/ticket/index'],
                ],
                [
                    'label' => 'Статусы',
                    'url' => ['/ticket-status/index'],
                ],
                [
                    'label' => 'Типы',
                    'url' => ['/ticket-type/index'],
                ],
            ],
        ],
        ['label' => 'Новости', 'url' => ['/news/index']],
        [
            'label' => 'Локация',
            'dropDownOptions' => ['class' => 'dropdown-menu'],
            'items' => [
                [
                    'label' => 'Регионы',
                    'url' => ['/region/index'],
                ],
                [
                    'label' => 'Населенные пункты',
                    'url' => ['/locality/index'],
                ],
                [
                    'label' => 'Улицы',
                    'url' => ['/street/index'],
                ],
                [
                    'label' => 'Объекты',
                    'url' => ['/house/index'],
                ],
                [
                    'label' => 'Квартиры',
                    'url' => ['/apartment/index'],
                ]
            ],
        ],
        [
            'label' => 'Настройки',
            'dropDownOptions' => ['class' => 'dropdown-menu'],
            'items' => [
                [
                    'label' => 'Типы файлов',
                    'url' => ['/file-type/index'],
                ],
                [
                    'label' => 'Типы нотификаций',
                    'url' => ['/notification-type/index'],
                ]
            ],
        ],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Войти', 'url' => ['/site/login']];
    }     
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => $menuItems,
    ]);
    if (Yii::$app->user->isGuest) {
        echo Html::tag('div',Html::a('Login',['/site/login'],['class' => ['btn btn-link login text-decoration-none']]),['class' => ['d-flex']]);
    } else {
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton(
                'Выйти (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout text-decoration-none']
            )
            . Html::endForm();
    }
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
