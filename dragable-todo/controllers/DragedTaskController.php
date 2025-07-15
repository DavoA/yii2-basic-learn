<?php
namespace app\controllers;

use app\models\User;
use app\models\DragedTask;
use app\models\DragedTaskSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class DragedTaskController extends Controller
{
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                        'update-status' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function beforeAction($action)
    {
        if (in_array($action->id, ['search', 'update-status'])) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        list($needToDo, $inProgress, $completed) = DragedTask::getTasks();
        
        return $this->render('index', [
            'needToDo' => $needToDo,
            'inProgress' => $inProgress,
            'completed' => $completed,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new DragedTask();
        $usersList = User::getUsersList();
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'usersList' => $usersList,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $usersList = User::getUsersList();
        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'usersList' => $usersList,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = DragedTask::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionUpdateStatus()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $id = \Yii::$app->request->post('id');
        $status = \Yii::$app->request->post('status');

        $model = DragedTask::findOne($id);
        if ($model && in_array($status, [
            'need-to-do',
            'in-progress',
            'completed'
        ])) {
            switch ($status) {
                case 'need-to-do':
                    $model->status = DragedTask::STATUS_NEED_TO_DO;
                    break;
                case 'in-progress':
                    $model->status = DragedTask::STATUS_IN_PROGRESS;
                    break;
                case 'completed':
                    $model->status = DragedTask::STATUS_COMPLETED;
                    break;
            }
            if ($model->save(false)) {
                return ['success' => true];
            }
            return ['success' => false, 'error' => 'Failed to save model'];
        }
        return ['success' => false, 'error' => 'Invalid task or status'];
    }
}