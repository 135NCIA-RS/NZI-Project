<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
$txt_username = Yii::t('app', 'Username');
$txt_password = Yii::t('app', 'Password');
?>
<div class="site-login">
     <div class="login-box">
      <div class="login-logo">
       <b>In</b>Touch</a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg"><?= Yii::t('app', 'Sign in to start your session')?></p>
        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <div class="form-group has-feedback">
                <?= $form->field($model, 'username', [
  'template' => "<i class='glyphicon glyphicon-envelope form-control-feedback'></i>\n{input}\n{hint}\n{error}"
])->textInput(array('placeholder' => $txt_username));  ?>
            </div>
            <div class="form-group has-feedback">
                <?= $form->field($model, 'password', [
  'template' => "<i class='glyphicon glyphicon-lock form-control-feedback'></i>\n{input}\n{hint}\n{error}"
])->passwordInput(array('placeholder' => $txt_password));  ?>
            </div>
            <div class="row">
            <div class="col-xs-8">
              <?= $form->field($model, 'rememberMe')->checkbox() ?>
            </div><!-- /.col -->
            <div class="col-xs-4">
              <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>
            </div><!-- /.col -->
          </div>

            <?php ActiveForm::end(); ?>

        
        <?php 
        $lost= Yii::t('app', 'I forgot my password');
        ?>
        <?= Html::a($lost, ['site/request-password-reset']) ?>.
        <br>
   

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->
    <div class="row">
        <div class="col-lg-5">
            
        </div>
    </div>
</div>
