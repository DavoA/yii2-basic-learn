<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var app\models\DragedTask $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="draged-task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true])->label(Yii::t('app', 'Title')) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6])->label(Yii::t('app', 'Description')) ?>

    <?= $form->field($model, 'status')->dropDownList([
         'need to do' =>  Yii::t('app', 'Need to do'),
         'in progress' => Yii::t('app', 'In progress'), 
         'completed' => Yii::t('app', 'Completed'), ], 
         ['prompt' => Yii::t('app', 'Select status')])->label(Yii::t('app', 'Status')) ?>

    <?= $form->field($model, 'user_id')->widget(Select2::class, [
        'data' => $usersList,
        'options' => ['placeholder' => Yii::t('app', 'Select User')],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label(Yii::t('app', 'User')) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
