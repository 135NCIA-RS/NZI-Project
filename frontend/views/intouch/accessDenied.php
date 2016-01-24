<?php

use yii\helpers\Html;
?>

<br/><br/>


<div class="box box-danger" style="max-width: 60%; left: 50%; transform: translateX(-50%)">
    <div class="box-header with-border">
        <i class="fa fa-warning"></i>
        <h3 class="box-title"><?= Yii::t('app','You are not allowed to perform this task.'); ?></h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body" style="padding: 60px">
        <center>
            <?= Html::img("@web/dist/img/error.gif", ['class' => '', 'alt' => 'Error GIF']) ?>
        </center>
    </div>
    <!-- /.box-body -->
</div>



