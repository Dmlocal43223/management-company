<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var src\role\entities\Role $model */
/** @var backend\forms\RoleForm $roleForm */

$this->title = 'Обновить роль: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Роли', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'name' => $model->name]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="role-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'roleForm' => $roleForm
    ]) ?>

</div>
