<?php
use app\models\DragedTask;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

$this->title = Yii::t('app', 'Dragable Tasks');
$this->params['breadcrumbs'][] = $this->title;

$columns = [
    'need-to-do' => [
        'label' => Yii::t('app', 'Need to Do'),
        'items' => $needToDo
    ],
    'in-progress' => [
        'label' => Yii::t('app', 'In Progress'),
        'items' => $inProgress
    ],
    'completed' => [
        'label' => Yii::t('app', 'Completed'),
        'items' => $completed
    ],
];
?>
<div class="draged-task-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Draged Task'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="kanban-board">
        <?php foreach ($columns as $status => $data): ?>
            <div id="<?= $status ?>" class="kanban-column">
                <div class="kanban-header">
                    <h3><?= $data['label'] ?></h3>
                    <h3 class="task-count" data-status="<?= $status ?>"><?= count($data['items']) ?></h3>
                </div>
                <?php foreach ($data['items'] as $task): ?>
                    <div class="kanban-task" data-id="<?= $task->id ?>">
                        <strong><?= Html::encode($task->title) ?></strong>
                        <p><?= Html::encode($task->description) ?></p>
                        <?= Html::a(Yii::t('app', 'View'), ['view', 'id' => $task->id], ['class' => 'btn btn-sm btn-primary']) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
$updateStatusUrl = Url::to(['draged-task/update-status']);
$csrfToken = Yii::$app->request->csrfToken;
$this->registerJsFile('@web/js/kanban.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJs("initKanban('$updateStatusUrl', '$csrfToken');");
?>