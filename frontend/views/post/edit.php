<?php
/* @var $this yii\web\View */
?>

<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title"><?= Yii::t('app','Edit your post'); ?></h3>
    </div>
    <form enctype="multipart/form-data" method="post">
        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
        <div class="box-body">
            <div class="form-group">
                <label><?= Yii::t('app','Post'); ?></label>
                <input type="text" class="form-control" name="inputContent" placeholder="Something Went Wrong" value="<?= $post->getContent() ?>">
                <input type="hidden" name="post_id" value="<?= $post->getId() ?>">
            </div>


        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary"><?= Yii::t('app','Save changes'); ?></button>
        </div>
    </form>
</div>