<?php
namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Users;

class ForgotPasswordForm extends Model
{
    public $email;
    public $password;
    public $confirm_password;
    public $auth_key;
    private $_user = false;

    public function scenarios()
    {
        return [
            'request' => ['email'],
            'enter-token' => ['auth_key'],
            'change-password' => ['password', 'confirm_password'],
        ];
    }

    public function rules()
    {
        return [
            ['email', 'required', 'on' => 'request'],
            ['email', 'email', 'on' => 'request'],
            ['email', 'exist', 'targetClass' => '\app\models\Users', 'targetAttribute' => 'email', 'message' => 'This email address does not exist in the database.', 'on' => 'request'],


            ['auth_key', 'required', 'on' => 'enter-token'],
            ['auth_key', 'validateAuthKey', 'on' => 'enter-token'],

            ['password', 'required', 'on' => 'change-password'],
            ['password', 'string', 'min' => 6, 'on' => 'change-password'],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Passwords do not match.', 'on' => 'change-password'],
        ];
    }

    public function sendResetEmail()
    {
        if (!$this->validate() || empty($this->email)) {
            \Yii::error('Validation failed: ' . print_r($this->errors, true), __METHOD__);
            return false;
        }

        $user = $this->getUser();
        if (!$user) {
            $this->addError('email', 'User not found.');
            return false;
        }

        if ($user->auth_key) {
            $this->sendEmail($user);
            Yii::$app->session->set('reset_email', $this->email);
            return true;
        } else {
            $this->addError('email', 'No auth key found for this user.');
            return false;
        }
    }

    public function validateAuthKey($attribute, $params)
    {
        $conditions = ['auth_key' => $this->$attribute];
        if (!empty($this->email)) {
            $conditions['email'] = $this->email;
        }

        $user = User::findOne($conditions);
        if (!$user) {
            $this->addError($attribute, 'Invalid auth key.');
        } else {
            $this->_user = $user;
        }
    }

    public function verifyAuthKey()
    {
        if (!$this->validate() || empty($this->auth_key)) {
            \Yii::error('Validation failed: ' . print_r($this->errors, true), __METHOD__);
            return false;
        }
        return $this->getUser() !== null;
    }

    public function getUser()
    {
        if ($this->_user === false && !empty($this->email)) {
            $this->_user = User::findOne(['email' => $this->email]);
        }
        return $this->_user;
    }

    private function sendEmail($user, $type = 0)
    {
        $subject = $type == 1 ? 'Password Changed' : 'Password Reset';
        $textBody = $type == 1 ? "Hello {$user->username},\n\nYour password has been changed" : "Hello {$user->username},\n\nYour password reset key is: {$user->auth_key}\n\nEnter it to proceed with resetting your password.";
        $htmlBody = $type == 1 ? "<p>Hello {$user->username},</p><p>Your password has been changed.</p>" : "<p>Hello {$user->username},</p><p>Your password reset key is: <strong>{$user->auth_key}</strong></p><p>Enter it to proceed with resetting your password.</p>";

        Yii::$app->mailer->compose()
            ->setFrom(['aristakesyandav@yandex.com' => 'Yii2 Basic Application'])
            ->setTo($user->email)
            ->setSubject($subject)
            ->setTextBody($textBody)
            ->setHtmlBody($htmlBody)
            ->send();
    }

    public function changePassword()
    {
        if(!$this->validate()){
            return false;
        }
        $user = $this->getUser();
        if($user){
            $user->setPassword($this->password);
            $user->auth_key = Yii::$app->security->generateRandomString(32);
            if($user->save()){
                $this->sendEmail($user, 1);
                return true;
            }
        }
        return false;
    }
}
?>