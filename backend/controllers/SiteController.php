<?php

namespace backend\controllers;

use common\forms\LoginForm;
use src\user\repositories\UserRepository;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    private UserRepository $userRepository;
    public function __construct($id, $module, $config = [])
    {
        $this->userRepository = new UserRepository();

        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions(): array
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin(): Response|string
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $form = new LoginForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            Yii::$app->user->login($this->userRepository->findByUsername($form->username), $form->rememberMe ? 3600 * 24 * 30 : 0);
            return $this->goBack();
        }

        $form->password = '';

        return $this->render('login', [
            'loginForm' => $form,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
