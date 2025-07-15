<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = Yii::t('app','Enter Reset Auth Key');
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="auth-enter-auth-key">
    <?php $form = ActiveForm::begin(['id' => 'enter-auth-key-form']); ?>

    <?= $form->field($model, 'email')->textInput(['readonly' => true, 'value' => $model->email])->label(Yii::t('app', 'Email')) ?>
    <?= $form->field($model, 'auth_key')->textInput(['autofocus' => true])->label(Yii::t('app', 'Reset Key')) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app','Verify Key'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>