<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Forgot Password';
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="site-forgot-password">
    <?php $form = ActiveForm::begin(['id' => 'forgot-password-form']); ?>

    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Send key', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>