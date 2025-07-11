<?php

namespace app\controllers;

use Yii;
use app\models\User;
use app\models\Task;
use app\models\TaskSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class TaskController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'toggle-status' => ['POST'],
                    'search' => ['GET'],
                    'search-all' => ['GET'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'toggle-status', 'search', 'search-all', 'index-all'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function () {
                    if (Yii::$app->user->isGuest) {
                        Yii::$app->session->setFlash('error', Yii::t('app', 'Please log in to access this feature.'));
                        return Yii::$app->response->redirect(['auth/login']);
                    }
                    throw new \yii\web\ForbiddenHttpException(Yii::t('app', 'You are not allowed to perform this action.'));
                },
            ],
        ]);
    }

    public function beforeAction($action)
    {
        if ($action->id === 'search') {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'isAll' => false,
        ]);
    }

    public function actionIndexAll()
    {
        $searchModel = new TaskSearch();
        $usersList = User::find()
            ->select(['username', 'id'])
            ->indexBy('id')
            ->column();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'isAll' => true,
            'usersList' => $usersList,
        ]);
    }

    public function actionSearch()
    {
        $searchModel = new TaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);

        Yii::info('Search action called with params: ' . print_r(Yii::$app->request->queryParams, true), __METHOD__);

        return $this->render('_grid', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'isAll' => false,
        ]);
    }

    public function actionSearchAll()
    {
        $searchModel = new TaskSearch();
        $usersList = User::find()
            ->select(['username', 'id'])
            ->indexBy('id')
            ->column();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('_grid', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'isAll' => true,
            'usersList' => $usersList,
        ]);
    }

    public function actionView($id, $isAll = 0)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'isAll' => (bool)$isAll,
        ]);
    }

    public function actionCreate($isAll = 0)
    {
        $model = new Task();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'isAll' => $isAll]);
        }

        $model->loadDefaultValues();

        return $this->render('create', [
            'model' => $model,
            'isAll' => (bool)$isAll,
        ]);
    }

    public function actionUpdate($id, $isAll = 0)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'isAll' => $isAll]);
        }

        return $this->render('update', [
            'model' => $model,
            'isAll' => (bool)$isAll,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Task::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function actionToggleStatus($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        if (empty($id)) {
            Yii::error('Missing id parameter');
            return ['success' => false, 'message' => Yii::t('app', 'Missing required parameter: id')];
        }

        $task = Task::findOne(['id' => $id, 'user_id' => Yii::$app->user->id]);

        if (!$task) {
            Yii::error('Task not found for id: ' . $id);
            return ['success' => false, 'message' => Yii::t('app', 'Task not found or access denied')];
        }

        if ($task->toggleStatus()) {
            return [
                'success' => true,
                'message' => Yii::t('app', 'Task status updated successfully'),
                'status' => $task->status,
            ];
        }

        return ['success' => false, 'message' => Yii::t('app', 'Failed to update task status')];
    }
}
