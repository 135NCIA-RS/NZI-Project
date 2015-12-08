<div class="box box-primary">
    <div class="box-header">
        <h3 class="box-title">Edit your about section</h3>
    </div>
    <form enctype="multipart/form-data" method="post">
        <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
        <div class="box-body">
            <div class="form-group">
                <label>Education</label>
                <input type="text" class="form-control" name="inputEducation" placeholder="Enter you education" value="<?= $education ?>">
            </div>
            <div class="form-group">
                <label>Location</label>
                <input type="text" class="form-control" name="inputLocation" placeholder="Enter your location" value="<?= $city ?>">
            </div>
            <div class="form-group">
                <label>Date of birth's</label>
                <input type="date" class="form-control" name="inputDate" placeholder="Enter your date of birth's" value="<?= $birth ?>">
            </div>
            <div class="form-group">
                <label>Notes</label>
                <input type="text" class="form-control" name="inputNotes" placeholder="Enter your notes" value="<?= $about ?>">
            </div>

        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
    </form>
</div>