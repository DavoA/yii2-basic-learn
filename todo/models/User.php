<?php
namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
    const STATUS_DELETED = 'deleted';

    public $password = null;

    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['username', 'email', 'password_hash', 'auth_key'], 'required'],
            [['access_token'], 'default', 'value' => null],
            [['status'], 'default', 'value' => self::STATUS_ACTIVE],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'string'],
            [['username', 'email', 'password_hash', 'access_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            [['username'], 'unique'],
            [['email'], 'unique'],
        ];
    }

    public static function optsStatus()
    {
        return [
            self::STATUS_ACTIVE => 'active',
            self::STATUS_INACTIVE => 'inactive',
            self::STATUS_DELETED => 'deleted',
        ];
    }

    public function displayStatus()
    {
        return isset(self::optsStatus()[$this->status]) ? self::optsStatus()[$this->status] : 'unknown';
    }

    public function isStatusActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isStatusInactive()
    {
        return $this->status === self::STATUS_INACTIVE;
    }

    public function isStatusDeleted()
    {
        return $this->status === self::STATUS_DELETED;
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if (!empty($this->password)) {
            $this->setPassword($this->password);
        }
        if ($insert && empty($this->auth_key)) {
            $this->generateAuthKey();
        }
        if ($insert && empty($this->access_token)) {
            $this->generateAccessToken();
        }

        return true;
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public static function findByEmailOrUsername($emailOrUsername)
    {
        return static::find()
            ->where(['email' => $emailOrUsername])
            ->orWhere(['username' => $emailOrUsername])
            ->one();
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

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString();
    }
}
