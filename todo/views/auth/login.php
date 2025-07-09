<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = Yii::t('app','Login');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container">
    <div class="auth-login">
        <h1><?= Html::encode($this->title) ?></h1>
        <p><?= Yii::t('app', 'Please fill out the following fields to login:') ?></p>

        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                        'inputOptions' => ['class' => 'col-lg-3 form-control'],
                        'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
                    ],
                ]); ?>

                <?= $form->field($model, 'email_or_username')->textInput(['autofocus' => true])->label(Yii::t('app', 'Email or Username')) ?>
                <?= $form->field($model, 'password')->passwordInput()->label(Yii::t('app', 'Password')) ?>
                <div class="form-group" style="margin-top: -10px; margin-bottom: 20px;">
                    <div class="col-md-offset-1 col-md-11 mt-n1" style="padding-left: 0;">
                        <small class="text-muted">
                        <?= Yii::t('app', 'Forgot password?') ?> <?= Html::a(Yii::t('app','Reset'), ['auth/forgot-password']) ?>
                        </small>
                    </div>
                </div>
                <?= $form->field($model, 'rememberMe')->checkbox([
                    'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
                ])->label(Yii::t('app', 'Remember Me')) ?>

                <div class="form-group">
                    <div>
                        <?= Html::submitButton(Yii::t('app','Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>

                <div class="offset-lg-1" style="color:#999;">
                <?= Yii::t('app', 'Don’t have an account?') ?> <?= Html::a(Yii::t('app','Sign up'), ['auth/signup']) ?>
                </div>
            </div>
        </div>
    </div>
</div>