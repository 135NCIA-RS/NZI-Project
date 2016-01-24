<style>
    .hr{
        width: 95%;
        font-size: 1px;
        color: rgba(0,0,0,0);
        line-height: 1px;

        background-color: grey;
        margin-top: -6px;
        margin-bottom: 10px;
    }
    #hr1{
        position: relative;
        top: 10em;
    }
    .userbox{
        background-color: #EDF5F7;
        padding: 10px 10px 1px 10px;
        border-radius: 10px;
        margin-left: 30px;
        margin-bottom:5px;
        max-width: 90%;
    }
</style>
<div>
    <div>
        <font size ="5"><?= Yii::t('app','Search results for:'); ?> "<?= $query ?>"</font>
        <div class="hr" id="hr1">.</div>
    </div>
    <div>
        <p>
            <?= $count ?> <?= ($count == 1) ? Yii::t('app','result') : Yii::t('app','results') ?> <?= Yii::t('app','found:'); ?>
        </p>
    </div>
    <div>
        <font size="3" ><?= Yii::t('app','Users:'); ?></font>
        <?php
        foreach ($users as $user)
        {
            ?>
            <div class="userbox">
                <img class="direct-chat-img" src="<?= $user['photo'] ?>" alt="message user image" style="margin-right: 10px;">

                <p><?= $user['name'] ?></p>
                <a href="/user<?= $user['link'] ?>"><?= Yii::t('app','Profile'); ?></a>
            </div>
            <?php
        }
        ?>

    </div>
</div>
