<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\DragedTask $model */

$this->title = Yii::t('app', 'Create Draged Task');
$this->params['breadcrumbs'][] = ['label' =>  Yii::t('app', 'Draged Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="draged-task-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'usersList' => $usersList,
    ]) ?>

</div>
