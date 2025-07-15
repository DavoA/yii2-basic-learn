<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Task $model */

$this->title = Yii::t('app', 'Update Task: ') . $model->title;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', 'Tasks'),
    'url' => $isAll ? ['index-all'] : ['index'],
];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="task-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'isAll' => $isAll,
    ]) ?>

</div>
