<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PostSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'post_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'owner_id') ?>

    <?= $form->field($model, 'post_type') ?>

    <?= $form->field($model, 'post_text') ?>

    <?php // echo $form->field($model, 'post_ref') ?>

    <?php // echo $form->field($model, 'post_visibility') ?>

    <?php // echo $form->field($model, 'post_date') ?>

    <?php // echo $form->field($model, 'post_editdate') ?>

    <?php // echo $form->field($model, 'post_additionaltext') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
