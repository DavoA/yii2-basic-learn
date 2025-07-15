<?php

namespace app\models;

use Yii;

class DragedTask extends \yii\db\ActiveRecord
{

    const STATUS_NEED_TO_DO = 'need to do';
    const STATUS_IN_PROGRESS = 'in progress';
    const STATUS_COMPLETED = 'completed';

    public static function tableName()
    {
        return 'draged_task';
    }

    public function rules()
    {
        return [
            [['description'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 'need to do'],
            [['title', 'user_id'], 'required'],
            [['description', 'status'], 'string'],
            [['created_at'], 'safe'],
            [['user_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            ['status', 'in', 'range' => array_keys(self::optsStatus())],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'user_id' => 'User ID',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function optsStatus()
    {
        return [
            self::STATUS_NEED_TO_DO => 'need to do',
            self::STATUS_IN_PROGRESS => 'in progress',
            self::STATUS_COMPLETED => 'completed',
        ];
    }

    public function displayStatus()
    {
        return self::optsStatus()[$this->status];
    }

    public function isStatusNeedToDo()
    {
        return $this->status === self::STATUS_NEED_TO_DO;
    }

    public function setStatusToNeedToDo()
    {
        $this->status = self::STATUS_NEED_TO_DO;
    }

    public function isStatusInProgress()
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function setStatusToInProgress()
    {
        $this->status = self::STATUS_IN_PROGRESS;
    }

    public function isStatusCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function setStatusToCompleted()
    {
        $this->status = self::STATUS_COMPLETED;
    }
    public static function getTasks(){
        return [
            self::find()->where(['status' => self::STATUS_NEED_TO_DO])->all(),
            self::find()->where(['status' => self::STATUS_IN_PROGRESS])->all(),
            self::find()->where(['status' => self::STATUS_COMPLETED])->all(),
        ];
    }
}
