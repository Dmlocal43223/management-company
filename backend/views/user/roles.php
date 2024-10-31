<?php

use kartik\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var src\user\entities\User $model */
/** @var array $assignedRoles */

$this->title = "Роли пользователя {$model->username}";
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="roles-index">
        <h1><?= Html::encode($this->title) ?></h1>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'name',
                    'label' => 'Название роли',
                ],
                [
                    'attribute' => 'description',
                    'label' => 'Описание',
                ],
                [
                    'class' => 'yii\grid\CheckboxColumn',
                    'name' => 'roles[]',
                    'header' => 'Назначить роль',
                    'checkboxOptions' => function ($role) use ($assignedRoles, $model) {
                        return [
                            'value' => $role->name,
                            'checked' => in_array($role->name, $assignedRoles),
                            'class' => 'role-checkbox',
                            'data-role-name' => $role->name,
                            'data-user-id' => $model->id,
                        ];
                    },
                ],
            ],
        ]); ?>
    </div>

<?php
$js = <<<JS
$('.role-checkbox').on('change', function() {
    var checkbox = $(this);
    var roleName = checkbox.data('role-name');
    var userId = checkbox.data('user-id');
    var isChecked = checkbox.is(':checked');
    
    var action = isChecked ? 'assign' : 'revoke';
    var confirmMessage = isChecked 
        ? 'Вы уверены, что хотите назначить роль "' + roleName + '" этому пользователю?' 
        : 'Вы уверены, что хотите снять роль "' + roleName + '" с этого пользователя?';

    if (confirm(confirmMessage)) {
        $.ajax({
            url: action === 'assign' ? '/role/assign' : '/role/revoke',
            type: 'POST',
            data: {
                roleName: roleName,
                userId: userId,
                _csrf: yii.getCsrfToken()
            },
            success: function(response) {
                if (response.success) {
                    alert('Роль успешно обновлена.');
                } else {
                    alert('Ошибка: ' + response.message);
                    checkbox.prop('checked', !isChecked);
                }
            },
            error: function(jqXHR) {
                if (jqXHR.status === 403) {
                    alert('У вас нет прав для выполнения этого действия.');
                } else {
                    alert('Произошла ошибка. Попробуйте еще раз.');
                }
                checkbox.prop('checked', !isChecked);
            }
        });
    } else {
        checkbox.prop('checked', !isChecked);
    }
});
JS;

$this->registerJs($js);
?>