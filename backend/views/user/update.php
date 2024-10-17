<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var src\user\entities\User $model */
/** @var common\forms\UserForm $userForm */
/** @var common\forms\UserInformationForm $userInformationForm */

$this->title = 'Обновить пользователя: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'userForm' => $userForm,
        'userInformationForm' => $userInformationForm,
    ]) ?>

</div>
