<?php
namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;

class ChangePasswordForm extends Model
{
    public $email;
    public $password;
    public $password_repeat;
    public $auth_key;

    private $_user = null;

    public function rules()
    {
        return [
            [['email', 'password', 'password_repeat', 'auth_key'], 'required', 'message' => Yii::t('app', 'This field is required.')],
            ['email', 'email', 'message' => Yii::t('app', 'Invalid email address.')],
            ['password', 'string', 'min' => 6, 'tooShort' => Yii::t('app', 'Password must be at least 6 characters.')],
            ['password_repeat', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('app', 'Passwords do not match.')],
            ['auth_key', 'validateAuthKey'],
        ];
    }

    public function validateAuthKey($attribute)
    {
        if (!($user = $this->getUser()) || $user->auth_key !== $this->$attribute) {
            $this->addError($attribute, Yii::t('app', 'Invalid auth key.'));
        }
    }

    public function changePassword()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = $this->getUser();
        if ($user) {
            $user->setPassword($this->password);
            $user->auth_key = Yii::$app->security->generateRandomString(32);
            if ($user->save()) {
                $this->sendEmail($user);
                return true;
            }
        }
        return false;
    }

    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne(['email' => $this->email, 'auth_key' => $this->auth_key]);
        }
        return $this->_user;
    }

    private function sendEmail($user)
    {
        Yii::$app->mailer->compose()
            ->setFrom(['aristakesyandav@yandex.com' => 'Yii2 Basic Application'])
            ->setTo($user->email)
            ->setSubject('Password Reset')
            ->setTextBody("Hello {$user->username},\n\nYour password has been changed.")
            ->setHtmlBody("<p>Hello {$user->username},</p><p>Your password has been changed.</p>")
            ->send();
    }
}
