<?php

namespace frontend\controllers;

use common\forms\LoginForm;
use common\forms\UserInformationForm;
use DomainException;
use frontend\forms\SignupForm;
use src\file\repositories\FileRepository;
use src\user\repositories\UserInformationRepository;
use src\user\repositories\UserRepository;
use src\user\services\UserService;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    private UserRepository $userRepository;
    private UserService $userService;

    public function __construct($id, $module, $config = [])
    {
        $this->userRepository = new UserRepository();
        $this->userService = new UserService($this->userRepository, new UserInformationRepository(), new FileRepository());

        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
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
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
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
     * Logs in a user.
     *
     * @return string|Response
     */
    public function actionLogin(): string|Response
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

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
     * Logs out the current user.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout(): string
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return Response|string
     */
    public function actionSignup(): Response|string
    {
        $signupForm = new SignupForm();
        $userInformationForm = new UserInformationForm();

        if ($signupForm->load(Yii::$app->request->post()) && $userInformationForm->load(Yii::$app->request->post())) {
            if ($signupForm->validate() && $userInformationForm->validate()) {
                try {
                    $user = $this->userService->create($signupForm, $userInformationForm);

                    if (Yii::$app->getUser()->login($user, 3600 * 24 * 30)) {
                        return $this->goHome();
                    }
                } catch (DomainException $exception) {
                    Yii::$app->session->setFlash('error', $exception->getMessage());
                }
            } else {
                $errors = array_merge($signupForm->getErrorSummary(true), $userInformationForm->getErrorSummary(true));
                Yii::$app->session->setFlash('error', 'Проверьте правильность заполнения форм. ' . implode(', ', $errors));
            }
        }

        return $this->render('signup', [
            'signupForm' => $signupForm,
            'userInformationForm' => $userInformationForm
        ]);
    }

    public function actionProfile(): string
    {
        $user = Yii::$app->user->identity;

        return $this->render('profile', [
            'user' => $user,
        ]);
    }
}
