<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\TaskSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="task-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'title')->label(Yii::t('app', 'Title')) ?>

    <?= $form->field($model, 'description')->label(Yii::t('app', 'Description')) ?>

    <?= $form->field($model, 'status')->dropDownList([ 
        'pending' => Yii::t('app', 'Pending'),
        'completed' => Yii::t('app', 'Completed'), 
    ], ['prompt' => Yii::t('app', 'Select status')])  ?>

    <?php if (!empty($isAll)): ?>
        <?php
            $usersList = ArrayHelper::map(User::find()->all(), 'id', 'username');
        ?>
        <?= $form->field($model, 'user_id')->dropDownList(
            $usersList,
            ['prompt' => Yii::t('app', 'Select User')]
        )->label(Yii::t('app', 'User')) ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
