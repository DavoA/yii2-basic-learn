<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'horizontalCssClasses' => [
                        'label' => 'col-lg-3',
                        'offset' => 'col-lg-offset-1',
                        'wrapper' => 'col-lg-6',
                    ],
                ],
            ]); ?>

            <?= $form->field($model, 'email_or_username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>
            <div class="form-group" style="margin-top: -10px; margin-bottom: 20px;">
                <div class="col-md-offset-1 col-md-11 mt-n1" style="padding-left: 0;">
                    <small class="text-muted">
                        Forgot password? <?= Html::a('Reset', ['site/forgot-password']) ?>
                    </small>
                </div>
            </div>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
            ]) ?>
            
            <div class="form-group">
                <div class="offset-lg-1 col-lg-11">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

            <div class="offset-lg-1" style="color:#999;">
                Don’t have an account? <?= Html::a('Sign up', ['site/signup']) ?>
            </div>
        </div>
    </div>
</div>
