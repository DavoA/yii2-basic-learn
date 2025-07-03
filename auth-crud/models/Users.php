<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string|null $gender
 * @property string $email
 * @property string $password
 * @property string|null $username
 * @property string|null $phone_number
 * @property string|null $country
 * @property int $terms_agreed
 * @property string $auth_key
 * @property string|null $created_at
 */
class Users extends \yii\db\ActiveRecord implements IdentityInterface
{

    /**
     * ENUM field values
     */
    const GENDER_MALE = 'Male';
    const GENDER_FEMALE = 'Female';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gender', 'username', 'phone_number', 'country'], 'default', 'value' => null],
            [['terms_agreed'], 'default', 'value' => 0],
            [['terms_agreed'], 'in', 'range' => [0, 1]],
            [['first_name', 'last_name', 'email', 'password', 'auth_key'], 'required'],
            [['gender'], 'string'],
            [['terms_agreed'], 'integer'],
            [['created_at'], 'safe'],
            [['first_name', 'last_name', 'email', 'password', 'auth_key'], 'string', 'max' => 255],
            [['username'], 'string', 'max' => 50],
            [['phone_number'], 'string', 'max' => 20],
            [['country'], 'string', 'max' => 100],
            ['gender', 'in', 'range' => array_keys(self::optsGender())],
            [['email'], 'unique'],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
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
            'auth_key' => 'Auth Key',
            'created_at' => 'Created At',
        ];
    }


    /**
     * column gender ENUM value labels
     * @return string[]
     */
    public static function optsGender()
    {
        return [
            self::GENDER_MALE => 'Male',
            self::GENDER_FEMALE => 'Female',
        ];
    }

    /**
     * @return string
     */
    public function displayGender()
    {
        return $this->gender && isset(self::optsGender()[$this->gender]) ? self::optsGender()[$this->gender] : 'Not specified';
    }

    /**
     * @return bool
     */
    public function isGenderMale()
    {
        return $this->gender === self::GENDER_MALE;
    }

    public function setGenderToMale()
    {
        $this->gender = self::GENDER_MALE;
    }

    /**
     * @return bool
     */
    public function isGenderFemale()
    {
        return $this->gender === self::GENDER_FEMALE;
    }

    public function setGenderToFemale()
    {
        $this->gender = self::GENDER_FEMALE;
    }
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isAttributeChanged('password') || $insert) {
                $this->setPassword($this->password);
            }
            if ($insert && empty($this->auth_key)) {
                $this->auth_key = Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }
    public static function findByEmailOrUsername($emailOrUsername)
    {
        return static::find()
            ->where(['email' => $emailOrUsername])
            ->orWhere(['username' => $emailOrUsername])
            ->one();
    }
    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * Finds an identity by the given access token.
     * @param mixed $token the access token
     * @param mixed $type the type of the token
     * @return IdentityInterface|null the identity object associated with the token
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // Implement if using API authentication; otherwise, return null
        return null;
    }

    /**
     * Returns the ID of the user.
     * @return string|int the ID of the user
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the auth key.
     * @return string the authentication key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the auth key.
     * @param string $authKey the authentication key to validate
     * @return bool whether the auth key is valid
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
}
