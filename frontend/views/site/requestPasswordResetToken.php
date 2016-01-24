<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Request password reset';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-request-password-reset">


    <div class="login-box">
        <div class="login-logo">
            <b>In</b>Touch</a>
        </div><!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg"><?= Yii::t('app', 'Request password reset') ?></p>



            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'email') ?>
            <div class="row">
                
                <div class="col-xs-4">
                    <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary']) ?>
             

                </div>
             </div>

            <?php ActiveForm::end(); ?>



            <br>


        </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
    <div class="row">
        <div class="col-lg-5">

        </div>
    </div>

    <!-- don't delete that-->
</div>


