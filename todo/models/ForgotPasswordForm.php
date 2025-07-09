<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

class ForgotPasswordForm extends Model
{
    public $email;
    public $auth_key;

    private ?User $_user = null;

    public function rules()
    {
        return [
            ['email', 'required', 'when' => fn($model) => empty($model->auth_key), 'message' => Yii::t('app', 'This field is required.')],
            ['email', 'email', 'when' => fn($model) => empty($model->auth_key), 'message' => Yii::t('app', 'Invalid email address.')],
            ['email', 'exist', 'targetClass' => User::class, 'when' => fn($model) => empty($model->auth_key), 'message' => Yii::t('app', 'No user with this email address.')],

            ['auth_key', 'required', 'when' => fn($model) => empty($model->email), 'message' => Yii::t('app', 'This field is required.')],
            ['auth_key', 'validateAuthKey', 'when' => fn($model) => !empty($model->auth_key)],
        ];
    }

    public function sendResetEmail(): bool
    {
        if (!$this->validate() || empty($this->email)) {
            Yii::error('Validation failed: ' . print_r($this->errors, true), __METHOD__);
            return false;
        }

        $user = $this->getUser();
        if (!$user || empty($user->auth_key)) {
            Yii::error('User or auth key not found: ' . $this->email, __METHOD__);
            return false;
        }

        $this->sendEmail($user);
        Yii::$app->session->set('reset_email', $this->email);
        return true;
    }

    public function validateAuthKey($attribute)
    {
        $query = User::find()->where(['auth_key' => $this->$attribute]);
        if (!empty($this->email)) {
            $query->andWhere(['email' => $this->email]);
        }
        $user = $query->one();
        if (!$user) {
            $this->addError($attribute, Yii::t('app', 'Invalid auth key'));
        } else {
            $this->_user = $user;
        }
    }

    public function verifyAuthKey(): bool
    {
        if (!$this->validate() || empty($this->auth_key)) {
            Yii::error('Validation failed: ' . print_r($this->errors, true), __METHOD__);
            return false;
        }
        return $this->getUser() !== null;
    }

    public function getUser(): ?User
    {
        if ($this->_user === null && !empty($this->email)) {
            $this->_user = User::findOne(['email' => $this->email]);
        }
        return $this->_user;
    }

    protected function sendEmail(User $user): void
    {
        Yii::$app->mailer->compose()
            ->setFrom(['aristakesyandav@yandex.com' => 'Yii2 Basic Application'])
            ->setTo($user->email)
            ->setSubject('Password Reset')
            ->setTextBody("Hello {$user->username},\n\nYour password reset key is: {$user->auth_key}\n\nEnter it to proceed with resetting your password.")
            ->setHtmlBody("<p>Hello {$user->username},</p><p>Your password reset key is: <strong>{$user->auth_key}</strong></p><p>Enter it to proceed with resetting your password.</p>")
            ->send();
    }
}
