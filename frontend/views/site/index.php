<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Главная';
?>
<div class="site-index">

    <header>
        <h1><?= Html::encode($this->title) ?></h1>
    </header>

    <main>
        <section>
            <h2>Добро пожаловать в нашу управляющую компанию!</h2>
            <p>Мы предоставляем услуги по управлению недвижимостью, техническому обслуживанию и поддержке клиентов.</p>
            <p>Наша команда профессионалов готова помочь вам в любых вопросах, связанных с вашим имуществом.</p>
        </section>

        <section>
            <h2>Наши преимущества</h2>
            <ul>
                <li>Высокое качество обслуживания</li>
                <li>Индивидуальный подход к каждому клиенту</li>
                <li>Современные технологии управления</li>
                <li>Прозрачность и честность в работе</li>
            </ul>
        </section>
    </main>

    <footer>
        <p>&copy; Управляющая компания <?= date('Y') ?></p>
    </footer>

</div>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f5f5f5;
    }

    .site-index {
        max-width: 800px;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    header, footer {
        text-align: center;
        background-color: #f8f8f8;
        padding: 10px 0;
    }

    h2 {
        color: #333;
    }

    p {
        line-height: 1.5;
        color: #555;
    }

    ul {
        padding-left: 20px;
    }
</style>