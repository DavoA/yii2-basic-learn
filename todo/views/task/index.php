<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Tasks');

$ajaxSearchUrl = $isAll
    ? Url::to(['task/search-all'])
    : Url::to(['task/search']);
?>

<div class="task-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Task'), ['create', 'isAll' => $isAll], ['class' => 'btn btn-success']) ?>
        <?php if(empty($isAll)): ?>
            <?= Html::a(Yii::t('app', 'View All'), ['index-all'], ['class' => 'btn btn-success'])?>
        <?php else: ?>
            <?= Html::a(Yii::t('app', "View User's"), ['index'], ['class' => 'btn btn-success'])?>
        <?php endif; ?>
    </p>


    <div id="grid-view-container">
        <?= $this->render('_grid', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'isAll' => $isAll,
            'usersList' => $usersList ?? [],
        ]) ?>
    </div>

</div>

<?php
$this->registerJsVar('ajaxSearchUrl', $ajaxSearchUrl); // передаёт переменную из PHP в JS
$this->registerJsFile('@web/js/task-grid.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>