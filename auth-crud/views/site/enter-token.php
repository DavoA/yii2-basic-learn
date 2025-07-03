<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Enter Reset Key';
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="site-enter-token">
    <?php $form = ActiveForm::begin(['id' => 'enter-token-form']); ?>

    <?= $form->field($model, 'email')->textInput(['readonly' => true, 'value' => $model->email]) ?>
    <?= $form->field($model, 'auth_key')->textInput(['autofocus' => true])->label('Reset Key') ?>

    <div class="form-group">
        <?= Html::submitButton('Verify Key', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>