<?php

namespace backend\controllers;

use backend\forms\RoleForm;
use Exception;
use src\role\repositories\RoleRepository;
use src\role\services\RoleService;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;
use yii\web\Response;

class RoleController extends Controller
{
    private RoleRepository $roleRepository;
    private RoleService $roleService;

    public function __construct($id, $module, $config = [])
    {
        $this->roleRepository = new RoleRepository(Yii::$app->authManager);
        $this->roleService = new RoleService($this->roleRepository);

        parent::__construct($id, $module, $config);
    }

    /**
     * Просмотр всех ролей
     */
    public function actionIndex(): string
    {
        $query = $this->roleRepository->getAll();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView(string $name): Response|string
    {
        try {
            return $this->render('view', [
                'model' => $this->roleRepository->getByName($name),
            ]);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
            return $this->redirect(['index']);
        }
    }

    /**
     * Создание новой роли
     */
    public function actionCreate(): Response|string
    {
        $form = new RoleForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $role = $this->roleService->create($form);

                return $this->redirect(['view', 'name' => $role->name]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', $exception->getMessage());
            }
        }

        return $this->render('create', [
            'roleForm' => $form,
        ]);
    }

    public function actionUpdate(string $name): Response|string
    {
        try {
            $model = $this->roleRepository->getByName($name);
            $form = new RoleForm();
            $form->setAttributes($model->getAttributes());

            if ($form->load(Yii::$app->request->post())) {
                if ($form->validate()) {
                    $this->roleService->edit($model, $form);

                    return $this->redirect(['view', 'name' => $name]);
                } else {
                    Yii::$app->session->setFlash('error', implode(', ', $form->getErrorSummary(true)));
                    return $this->redirect(['view', 'name' => $name]);
                }
            }

            return $this->render('update', [
                'model' => $model,
                'roleForm' => $form,
            ]);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
            return $this->redirect(['view', 'name' => $name]);
        }
    }

    public function actionDelete(string $name): Response
    {
        try {
            $model = $this->roleRepository->getByName($name);
            $this->roleService->removeRole($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionAssign(): Response
    {
        $roleName = Yii::$app->request->post('roleName');
        $userId = Yii::$app->request->post('userId');

        try {
            $this->roleService->assignRoleToUser($roleName, $userId);
            return $this->asJson(['success' => true]);
        } catch (Exception $e) {
            return $this->asJson(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function actionRevoke(): Response
    {
        $roleName = Yii::$app->request->post('roleName');
        $userId = Yii::$app->request->post('userId');

        try {
            $this->roleService->revokeRoleFromUser($roleName, $userId);
            return $this->asJson(['success' => true]);
        } catch (Exception $e) {
            return $this->asJson(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}