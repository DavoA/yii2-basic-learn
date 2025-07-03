<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Change Password';
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="site-changepasswd">
    <?php $form = ActiveForm::begin(['id' => 'change-password-form']); ?>

    <?= $form->field($model, 'email')->textInput(['readonly' => true]) ?>
    <?= $form->field($model, 'auth_key')->textInput(['readonly' => true]) ?>
    <?= $form->field($model, 'password')->passwordInput() ?>
    <?= $form->field($model, 'confirm_password')->passwordInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Change Password', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>