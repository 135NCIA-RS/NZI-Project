<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;

$txt_username = Yii::t('app', 'Username');
$txt_password = Yii::t('app', 'Password');
$txt_password2 = Yii::t('app', 'Retype Password');
?>
<div class="site-signup">
    <div class="register-box">
        <div class="register-logo">
            <b>In</b>Touch</a>
        </div>

        <div class="register-box-body">
            <p class="login-box-msg"><?= Yii::t('app', 'Register a new account') ?></p>
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

            <div class="form-group has-feedback">
                <?=
                $form->field($model, 'username', [
                    'template' => "<i class='glyphicon glyphicon-user form-control-feedback'></i>\n{input}\n{hint}\n{error}"
                ])->textInput(array('placeholder' => $txt_username));
                ?>
            </div>

            <div class="form-group has-feedback">
                <?=
                $form->field($model, 'email', [
                    'template' => "<i class='glyphicon glyphicon-envelope form-control-feedback'></i>\n{input}\n{hint}\n{error}"
                ])->textInput(array('placeholder' => 'e-mail'));
                ?>
            </div>

            <div class="form-group has-feedback">
                <?=
                $form->field($model, 'password', [
                    'template' => "<i class='glyphicon glyphicon-lock form-control-feedback'></i>\n{input}\n{hint}\n{error}"
                ])->passwordInput(array('placeholder' => $txt_password));
                ?>
            </div>



            <?= Html::submitButton('Signup', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'signup-button']) ?>


            <?php ActiveForm::end(); ?>



               <?php 
        $lost= Yii::t('app', 'I already have an account');
        ?>
        <?= Html::a($lost, ['site/login']) ?>.
        </div><!-- /.form-box -->
    </div><!-- /.register-box -->
</div>
