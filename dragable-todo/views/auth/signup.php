<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = Yii::t('app', 'Signup');
?>

<div class="auth-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('app', 'Please fill out the following fields to signup:') ?></p>

    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
        <?= $form->field($model, 'email')->textInput()->label(Yii::t('app', 'Email')) ?>
        <?= $form->field($model, 'username')->textInput()->label(Yii::t('app', 'Username')) ?>
        <?= $form->field($model, 'password')->passwordInput()->label(Yii::t('app', 'Password')) ?>
        <?= $form->field($model, 'password_repeat')->passwordInput()->label(Yii::t('app', 'Repeat Password')) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Signup'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>
        <div class="offset-lg-1" style="color:#999;">
            <?= Yii::t('app', 'Already have an account?') ?> <?= Html::a(Yii::t('app', 'Login'), ['auth/login']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>
