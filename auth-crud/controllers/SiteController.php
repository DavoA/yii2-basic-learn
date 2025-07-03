<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\SignupForm;
use app\models\LoginForm;
use app\models\ForgotPasswordForm;
use app\models\ChangePasswordForm;
use app\models\ContactForm;
use app\models\EntryForm;

class SiteController extends Controller
{
    public function actionSay($message = 'Hello')
    {
        return $this->render('say', ['message' => $message]);
    }

    public function actionEntry()
    {
        $model = new EntryForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()){
            return $this->render('entry-confirm', ['model' => $model]);
        } else{
            return $this->render('entry', ['model' => $model]);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
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
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionForgotPassword()
    {
        $model = new ForgotPasswordForm();
        $model->scenario = 'request';
        if($model->load(Yii::$app->request->post()) && $model->sendResetEmail()){
            Yii::$app->session->setFlash('success', 'Password reset email sent. Please check your inbox.');
            return $this->redirect(['site/enter-token']);
        }

        return $this->render('forgot-password', ['model' => $model]);
    }

    public function actionEnterToken()
    {
        $model = new ForgotPasswordForm();
        $model->scenario = 'enter-token';
        $model->email = Yii::$app->session->get('reset_email');

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = $model->getUser();
            Yii::$app->session->remove('reset_email');
            return $this->redirect(['site/change-password', 'email' => $user->email, 'key' => $model->auth_key]);
        }

        return $this->render('enter-token', ['model' => $model]);
    }

    public function actionChangePassword($email, $key)
    {
        $model = new ForgotPasswordForm();
        $model->scenario = 'change-password';
        $model->email = $email;
        $model->auth_key = $key;

        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            Yii::$app->session->setFlash('success', 'Password changed successfully.');
            return $this->redirect(['site/login']);
        }
        return $this->render('change-password', ['model' => $model]);
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())){
            if ($user = $model->signup()){
                Yii::$app->user->login($user);
                Yii::$app->session->setFlash('success', 'Thank you for registration.');
                Yii::$app->mailer->compose()
                    ->setFrom(['aristakesyandav@yandex.com' => 'Yii2 Basic Application'])
                    ->setTo($model->email)
                    ->setSubject('Registration Confirmation')
                    ->setTextBody("Hello {$model->username},\n\nThank you for registering.")
                    ->setHtmlBody("<p>Hello <strong>{$model->username}</strong>,</p><p>Thank you for registering.</p>")
                    ->send();
                return $this->goHome();
            }
        } 
        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    
}
