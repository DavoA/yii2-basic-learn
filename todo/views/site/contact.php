<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\captcha\Captcha;

$this->title = Yii::t('app', 'Contact');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>

        <div class="alert alert-success">
            <?= Yii::t('app', 'Thank you for contacting us. We will respond to you as soon as possible.') ?>
        </div>

        <p>
            <?= Yii::t('app', 'Note that if you turn on the Yii debugger, you should be able to view the mail message on the mail panel of the debugger.') ?>
            <?php if (Yii::$app->mailer->useFileTransport): ?>
                <?= Yii::t('app', 'Because the application is in development mode, the email is not sent but saved as a file under') ?>
                <code><?= Yii::getAlias(Yii::$app->mailer->fileTransportPath) ?></code>.
                <?= Yii::t('app', 'Please configure the {property} property of the {component} application component to be false to enable email sending.', [
                    'property' => '<code>useFileTransport</code>',
                    'component' => '<code>mail</code>',
                ]) ?>
            <?php endif; ?>
        </p>

    <?php else: ?>

        <p>
            <?= Yii::t('app', 'If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.') ?>
        </p>

        <div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                    <?= $form->field($model, 'name')->textInput(['autofocus' => true])->label(Yii::t('app', 'Name')) ?>

                    <?= $form->field($model, 'email')->label(Yii::t('app', 'Email')) ?>

                    <?= $form->field($model, 'subject')->label(Yii::t('app', 'Subject')) ?>

                    <?= $form->field($model, 'body')->textarea(['rows' => 6])->label(Yii::t('app', 'Message')) ?>

                    <?= $form->field($model, 'verifyCode')->widget(Captcha::class, [
                        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                    ])->label(Yii::t('app', 'Verification Code')) ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    <?php endif; ?>
</div>
