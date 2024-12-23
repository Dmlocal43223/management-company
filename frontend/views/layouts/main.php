<?php

/** @var \yii\web\View $this */

/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use src\notification\repositories\NotificationRepository;
use src\role\entities\Role;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>

        <script async src="https://www.googletagmanager.com/gtag/js?id=G-EHESJNH6ZM"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
        </script>

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
                'class' => 'navbar navbar-expand-md custom-navbar fixed-top',
            ],
        ]);
        $menuItems = [
            ['label' => 'Объекты', 'url' => ['/house/index']],
            ['label' => 'Новости', 'url' => ['/news/index']],
            ['label' => 'Информация', 'url' => ['/site/about']],
        ];

        if (Yii::$app->user->can(Role::TENANT)) {
            $menuItems[] = ['label' => 'Отправить обращение', 'url' => ['/ticket/create']];
        }

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
            'items' => $menuItems,
        ]);
        if (Yii::$app->user->isGuest) {
            echo Html::tag('div', Html::a('Войти', ['/site/login'], ['class' => ['btn btn-link login text-decoration-none']]), ['class' => ['d-flex']]);
        } else {
            $unreadCount = (new NotificationRepository())->getUnReadNotificationCountByUser(Yii::$app->user->id);
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav'],
                'items' => [
                    [
                        'label' => Yii::$app->user->identity->username,
                        'items' => [
                            ['label' => 'Профиль', 'url' => ['/profile/view']],
                            ['label' => 'Заявки', 'url' => ['/ticket/index']],
                            ['label' => 'Оповещения' . ($unreadCount > 0 ? " ({$unreadCount})" : ''), 'url' => ['/notification/index']],
                            [
                                'label' => 'Выйти',
                                'url' => '#',
                                'linkOptions' => [
                                    'onclick' => 'event.preventDefault(); $.post("' . Url::to(['/site/logout']) . '", {}, function() { window.location.href = "' . Yii::$app->homeUrl . '"; });',
                                ],
                            ],
                        ],
                    ]
                ],
            ]);
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
