<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

class LoginForm extends Model
{
    public $email_or_username;
    public $password;
    public $rememberMe = true;

    private ?User $_user = null;

    public function rules(): array
    {
        return [
            [['email_or_username', 'password'], 'required', 'message' => Yii::t('app', 'This field is required.')],
            ['rememberMe', 'boolean', 'message' => Yii::t('app', 'Invalid value.')],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword(string $attribute, $params): void
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect email/username or password.'));
            }
        }
    }


    public function login(): bool
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    public function getUser(): ?User
    {
        if ($this->_user === null) {
            $this->_user = User::findByEmailOrUsername($this->email_or_username);
        }
        return $this->_user;
    }
}
