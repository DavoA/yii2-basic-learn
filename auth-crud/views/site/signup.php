<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Signup';
?>

<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup:</p>

    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
        <?= $form->field($model, 'first_name')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'last_name')->textInput(); ?>
        <?= $form->field($model, 'gender')->radioList([
            'Male' => 'Male',
            'Female' => 'Female',
        ], [
            'item' => function($index, $label, $name, $checked, $value) {
                return '<label class="radio-inline"><input type="radio" name="' . $name . '" value="' . $value . '" ' . ($checked ? 'checked' : '') . '> ' . $label . '</label>';
            }],
         ['prompt' => '']) ?>
        <?= $form->field($model, 'email')->textInput() ?>
        <?= $form->field($model, 'username')->textInput() ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'confirm_password')->passwordInput() ?>
        <?= $form->field($model, 'phone_number')->textInput() ?>
        <?= $form->field($model, 'country')->textInput() ?>
        <?= $form->field($model, 'terms_agreed')->checkbox() ?>

        <div class="form-group">
            <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>
    <?php ActiveForm::end(); ?>
</div>