<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Task extends ActiveRecord
{
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';

    public static function tableName()
    {
        return 'task';
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description'], 'default', 'value' => null],
            [['status'], 'default', 'value' => self::STATUS_PENDING],
            [['description', 'status'], 'string'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Description'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'user_id' => Yii::t('app', 'User ID'),
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function optsStatus()
    {
        return [
            self::STATUS_PENDING => 'pending',
            self::STATUS_COMPLETED => 'completed',
        ];
    }

    public function displayStatus()
    {
        return self::optsStatus()[$this->status] ?? Yii::t('app', 'Unknown');
    }

    public function isStatusPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isStatusCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function setStatusToPending()
    {
        $this->status = self::STATUS_PENDING;
    }

    public function setStatusToCompleted()
    {
        $this->status = self::STATUS_COMPLETED;
    }

    public function toggleStatus()
    {
        $this->status = $this->isStatusPending() ? self::STATUS_COMPLETED : self::STATUS_PENDING;
        return $this->save();
    }

    public function beforeSave($insert)
    {
        if ($insert && empty($this->user_id)) {
            $this->user_id = Yii::$app->user->id;
        }
        return parent::beforeSave($insert);
    }
}
