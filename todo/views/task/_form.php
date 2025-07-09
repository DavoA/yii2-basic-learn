<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Task $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label(Yii::t('app', 'Title')) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6])->label(Yii::t('app', 'Description')) ?>

    <?= $form->field($model, 'status')->dropDownList([ 
        'pending' => Yii::t('app', 'Pending'), 
        'completed' => Yii::t('app', 'Completed'), 
    ], ['prompt' => Yii::t('app', 'Select status')])->label(Yii::t('app', 'Status')) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
