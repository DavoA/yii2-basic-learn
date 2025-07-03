<?php
namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Users;

class SignupForm extends Model
{
    public $first_name;
    public $last_name;
    public $gender;
    public $email;
    public $username;
    public $password;
    public $confirm_password;
    public $phone_number;
    public $country;
    public $terms_agreed;
    
    public function rules()
    {
        return [
            [['first_name', 'last_name', 'gender', 'email', 'username', 'password', 'confirm_password', 'terms_agreed'], 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\app\models\Users', 'message' => 'This email address has already been taken.'],
            ['username', 'unique', 'targetClass' => '\app\models\Users', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 50],
            ['password', 'string', 'min' => 6],
            ['confirm_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Passwords do not match.'],
            ['gender', 'in', 'range' => ['Male', 'Female'], 'skipOnEmpty' => true],
            ['gender', 'default', 'value' => null],
            ['phone_number', 'string', 'max' => 20, 'skipOnEmpty' => true],
            ['country', 'string', 'max' => 100, 'skipOnEmpty' => true],
            ['terms_agreed', 'boolean'],
            ['terms_agreed', 'compare', 'compareValue' => 1, 'message' => 'You must agree to the terms.'],
        ];
    }
    public function signup(){
        if(!$this->validate()){
            \Yii::error('Validation failed: ' . print_r($this->errors, true), __METHOD__);
            return null;
        }

        $user = new Users;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->gender = $this->gender;
        $user->email = $this->email;
        $user->username = $this->username;
        $user->phone_number = $this->phone_number;
        $user->country = $this->country;
        $user->terms_agreed = $this->terms_agreed;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        if (!$user->save()) {
            \Yii::error('User save failed: ' . print_r($user->errors, true), __METHOD__);
            return null;
        }

        return $user;
    }
}
?>