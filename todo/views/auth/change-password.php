<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = Yii::t('app', 'Change Password');
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="auth-change-password">
    <?php $form = ActiveForm::begin(['id' => 'change-password-form']); ?>

    <?= $form->field($model, 'email')->textInput(['readonly' => true])->label(Yii::t('app', 'Email')) ?>
    <?= $form->field($model, 'auth_key')->textInput(['readonly' => true])->label(Yii::t('app', 'Reset Key')) ?>
    <?= $form->field($model, 'password')->passwordInput()->label(Yii::t('app', 'Password')) ?>
    <?= $form->field($model, 'password_repeat')->passwordInput()->label(Yii::t('app', 'Repeat Password')) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Change Password'), ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>