<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\ForgotPasswordForm;
use app\models\ChangePasswordForm;

class AuthController extends Controller
{
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

    public function actions()
    {
        return [
            'error' => ['class' => 'yii\web\ErrorAction'],
        ];
    }

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
        return $this->render('login', ['model' => $model]);
    }

    public function actionLogout()
    {
        $language = Yii::$app->language;
        Yii::$app->user->logout();
        Yii::$app->session->set('language', $language);
        return $this->goHome();
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $user = $model->signup()) {
            Yii::$app->user->login($user);
            Yii::$app->session->setFlash('success', Yii::t('app', 'Registration successful. Welcome!'));
            Yii::$app->mailer->compose()
                ->setFrom(['aristakesyandav@yandex.com' => 'Yii2 Basic Application'])
                ->setTo($model->email)
                ->setSubject(Yii::t('app', 'Registration Confirmation'))
                ->setTextBody(Yii::t('app', "Hello {username},\n\nThank you for registering.", ['username' => $model->username]))
                ->setHtmlBody(Yii::t('app', "<p>Hello <strong>{username}</strong>,</p><p>Thank you for registering.</p>", ['username' => $model->username]))
                ->send();
            return $this->goHome();
        }

        return $this->render('signup', ['model' => $model]);
    }

    public function actionForgotPassword()
    {
        $model = new ForgotPasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->sendResetEmail()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Password reset email sent.'));
            return $this->redirect(['auth/enter-auth-key']);
        }

        return $this->render('forgot-password', ['model' => $model]);
    }

    public function actionEnterAuthKey()
    {
        $model = new ForgotPasswordForm();
        $model->email = Yii::$app->session->get('reset_email');

        if ($model->load(Yii::$app->request->post()) && $model->verifyAuthKey()) {
            $user = $model->getUser();
            Yii::$app->session->remove('reset_email');
            Yii::$app->session->setFlash('success', Yii::t('app', 'Auth key verified.'));
            return $this->redirect(['auth/change-password', 'email' => $user->email, 'auth_key' => $user->auth_key]);
        }

        return $this->render('enter-auth-key', ['model' => $model]);
    }

    public function actionChangePassword($email, $auth_key)
    {
        $model = new ChangePasswordForm(['email' => $email, 'auth_key' => $auth_key]);

        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Password changed.'));
            return $this->redirect(['auth/login']);
        }

        return $this->render('change-password', ['model' => $model]);
    }
}
