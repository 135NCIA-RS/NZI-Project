<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title"><?= Yii::t('app','Edit your about section'); ?></h3>
    </div>
    <form enctype="multipart/form-data" method="post">
        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
        <div class="box-body">
            <div class="form-group">
                <label><?= Yii::t('app','Education'); ?></label>
                <input type="text" class="form-control" name="inputEducation" placeholder="Enter you education" value="<?= $education ?>">
            </div>
            <div class="form-group">
                <label><?= Yii::t('app','Location'); ?></label>
                <input type="text" class="form-control" name="inputLocation" placeholder="Enter your location" value="<?= $city ?>">
            </div>
            <div class="form-group">
                <label><?= Yii::t('app','Birthday'); ?></label>
                <input type="date" class="form-control" name="inputDate" placeholder="Enter your date of birth's" value="<?= $birth ?>">
            </div>
            <div class="form-group">
                <label><?= Yii::t('app','Miscellaneous'); ?></label>
                <input type="text" class="form-control" name="inputNotes" placeholder="Enter your notes" value="<?= $about ?>">
            </div>

        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary"><?= Yii::t('app','Save changes'); ?></button>
        </div>
    </form>
</div>