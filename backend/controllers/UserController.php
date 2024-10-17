<?php

namespace backend\controllers;

use common\forms\PasswordForm;
use backend\forms\search\UserSearch;
use common\forms\UserForm;
use common\forms\UserInformationForm;
use Exception;
use src\file\repositories\FileRepository;
use src\role\repositories\RoleRepository;
use src\role\services\RoleService;
use src\user\entities\User;
use src\user\repositories\UserInformationRepository;
use src\user\repositories\UserRepository;
use src\user\services\UserService;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    private UserRepository $userRepository;
    private RoleRepository $roleRepository;
    private UserService $userService;
    private RoleService $roleService;
    public function __construct($id, $module, $config = [])
    {
        $this->userRepository = new UserRepository();
        $this->roleRepository = new RoleRepository(Yii::$app->authManager);
        $this->roleService = new RoleService($this->roleRepository);
        $this->userService = new UserService($this->userRepository, new UserInformationRepository(), new FileRepository());

        parent::__construct($id, $module, $config);
    }

    /**
     * @inheritDoc
     */
    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::class,
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $searchModel = new UserSearch();
        $searchModel->load($this->request->queryParams);

        if (!$searchModel->validate()) {
            $query = $this->userRepository->getNoResultsQuery();
        } else {
            $query = $this->userRepository->getFilteredQuery($searchModel);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id): string
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id): Response|string
    {
        $model = $this->findModel($id);
        $userForm = new UserForm();
        $userInformationForm = new UserInformationForm();
        $userInformationForm->loadFromUser($model);
        $userForm->setAttributes($model->getAttributes());

        if ($userForm->load(Yii::$app->request->post()) && $userInformationForm->load(Yii::$app->request->post())) {
            if ($userForm->validate() && $userInformationForm->validate()) {
                try {
                    $this->userService->edit($model, $userForm, $userInformationForm);
                    return $this->redirect(['view', 'id' => $model->id]);
                } catch (Exception $exception) {
                    Yii::$app->session->setFlash('error', $exception->getMessage());
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'userForm' => $userForm,
            'userInformationForm' => $userInformationForm,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->userService->remove($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['index']);
    }

    public function actionRestore(int $id): Response
    {
        $model = $this->findModel($id);

        try {
            $this->userService->restore($model);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', $exception->getMessage());
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    public function actionChangePassword(int $id): Response|string
    {
        $model = $this->findModel($id);

        $form = new PasswordForm();

        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->userService->changePassword($model, $form);
                Yii::$app->session->setFlash('success', 'Пароль успешно изменен.');
                return $this->redirect(['view', 'id' => $model->id]);
            } catch (Exception $exception) {
                Yii::$app->session->setFlash('error', 'Ошибка при изменении пароля: ' . $exception->getMessage());
            }
        }

        return $this->render('change-password', [
            'passwordForm' => $form,
        ]);
    }

    /**
     * Получение ролей пользователя
     */
    public function actionRoles(int $user_id): string
    {
        $model = $this->findModel($user_id);
        $roles = $this->roleRepository->getAll();
        $assignedRoles = $this->roleRepository->getUserRoles($model->id);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $roles,
            'pagination' => false,
        ]);

        return $this->render('roles', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'assignedRoles' => array_keys($assignedRoles)
        ]);
    }

    public function actionAssignRole(): Response
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

    public function actionRevokeRole(): Response
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

    public function actionHouses(int $user_id): string
    {
        $model = $this->findModel($user_id);
        $houses = $this->roleRepository->getAll();
        $assignedHouses = $this->roleRepository->getUserRoles($model->id);

        $dataProvider = new ArrayDataProvider([
            'allModels' => $houses,
            'pagination' => false,
        ]);

        return $this->render('houses', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'assignedHouses' => array_keys($assignedHouses)
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): User
    {
        $model = $this->userRepository->findById($id);

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $model;
    }
}
