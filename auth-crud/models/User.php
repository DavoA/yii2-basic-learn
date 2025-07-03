<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'users';
    }

    public static function primaryKey()
	{
		return ['id'];
	}

    public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'gender' => 'Gender',
			'email' => 'Email',
            'password' => 'Password',
            'username' => 'Username',
            'phone_number' => 'Phone Number',
            'country' => 'Country',
            'terms_agreed' => 'Terms Agreed',
            'auth_key' => 'Authentication Key',
            'created_at' => 'Created At',
        ];
	}

    public function rules()
    {
        return[
            [['first_name', 'last_name', 'email', 'password'], 'required'],
            ['gender', 'in', 'range' => ['Male', 'Female'], 'skipOnEmpty' => true],
            ['gender', 'default', 'value' => null],
            [['terms_agreed'], 'boolean'],
            [['phone_number'], 'string', 'max' => 20],
            [['country'], 'string', 'max' => 100],
            [['email', 'username'], 'string', 'max' => 255],
            [['email'], 'email'],
            [['email', 'username'], 'unique'],
            ['auth_key', 'string', 'max' => 255],
        ];
    }
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }
    public static function findByEmailOrUsername($emailOrUsername)
    {
        return static::find()
            ->where(['email' => $emailOrUsername])
            ->orWhere(['username' => $emailOrUsername])
            ->one();
    }
}
?>