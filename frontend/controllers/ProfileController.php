<?php

namespace frontend\controllers;

use common\forms\UserForm;
use common\forms\UserInformationForm;
use Exception;
use frontend\forms\PasswordForm;
use src\file\repositories\FileRepository;
use src\user\repositories\UserInformationRepository;
use src\user\repositories\UserRepository;
use src\user\services\UserService;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

class ProfileController extends Controller
{
    private UserRepository $userRepository;
    private UserService $userService;

    public function __construct($id, $module, $config = [])
    {
        $this->userRepository = new UserRepository();
        $this->userService = new UserService($this->userRepository, new UserInformationRepository(), new FileRepository());

        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'only' => ['view', 'update', 'change-password'],
                    'rules' => [
                        [
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ],
            ]
        );
    }

    public function actionView(): Response|string
    {
        try {
            $model = $this->userRepository->getUserWithDetails(Yii::$app->user->id);
            return $this->render('view', ['model' => $model]);
        } catch (Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(['site/index']);
        }
    }

    public function actionUpdate(): Response|string
    {
        try {
            $model = $this->userRepository->getUserWithDetails(Yii::$app->user->id);
        } catch (Exception $exception) {
            Yii::$app->session->setFlash('error', 'Ошибка при получении данных пользователя: ' . $exception->getMessage());
            return $this->redirect(['site/index']);
        }

        $userForm = new UserForm();
        $userInformationForm = new UserInformationForm();
        $userForm->setAttributes($model->getAttributes());
        $userInformationForm->loadFromUser($model);

        if ($userForm->load(Yii::$app->request->post()) && $userInformationForm->load(Yii::$app->request->post())) {
            if ($userForm->validate() && $userInformationForm->validate()) {
                $userInformationForm->setUploadedFile();
                try {
                    $this->userService->edit($model, $userForm, $userInformationForm);
                    return $this->redirect(['view', 'id' => $model->id]);
                } catch (Exception $exception) {
                    Yii::$app->session->setFlash('error', 'Ошибка при сохранении изменений: ' . $exception->getMessage());
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'userForm' => $userForm,
            'userInformationForm' => $userInformationForm,
        ]);
    }

    public function actionChangePassword(): Response|string
    {
        $model = $this->userRepository->findById(Yii::$app->user->id);

        if (!$model) {
            Yii::$app->session->setFlash('error', "Пользователь не найден");
            return $this->redirect(['index']);
        }

        $form = new PasswordForm();
        $form->user = $model;

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
}