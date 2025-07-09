<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

class SignupForm extends Model
{
    public string $username = '';
    public string $email = '';
    public string $password = '';
    public string $password_repeat = '';

    public function rules()
    {
        return [
            [['username', 'password', 'password_repeat'], 'required', 'message' => Yii::t('app', 'This field is required.')],
            ['username', 'string', 'min' => 2, 'max' => 50, 'tooShort' => Yii::t('app', 'Username must be at least 2 characters.'), 'tooLong' => Yii::t('app', 'Username must be at most 50 characters.')],
            ['password', 'string', 'min' => 6, 'tooShort' => Yii::t('app', 'Password must be at least 6 characters.')],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('app', 'Passwords do not match.')],
            ['email', 'email', 'message' => Yii::t('app', 'Invalid email address.')],
            ['email', 'unique', 'targetClass' => User::class, 'message' => Yii::t('app', 'This email address has already been taken.')],
            ['username', 'unique', 'targetClass' => User::class, 'message' => Yii::t('app', 'This username has already been taken.')],
        ];
    }
    public function signup()
    {
        if($this->validate()){
            $user = new User();
            $user->setAttributes([
                'username' => $this->username,
                'email' => $this->email,
                'status' => User::STATUS_ACTIVE,
            ]);
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->generateAccessToken();
            return $user->save()? $user : null;
        }
        return null;
    }
}
