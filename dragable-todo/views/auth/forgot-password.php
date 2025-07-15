<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app', 'Forgot Password');
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="auth-forgot-password">
    <?php $form = ActiveForm::begin(['id' => 'forgot-password-form']); ?>
    <?= $form->field($model, 'email')->textInput(['autofocus' => true])->label(Yii::t('app', 'Email')) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Send Reset Email'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>