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
    <font size ="5">My friends</font>
    <div class="hr" id="hr1">.</div>
</div>
<br/>
<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

foreach ($friends as $friend)
{
    ?>
    <div class="userbox">
        <img class="direct-chat-img" src="<?= $friend['photo'] ?>" alt="message user image" style="margin-right: 10px;">

        <p><?= $friend['name'] . " " . $friend['surname'] . " (" . $friend['username'] . ")" ?></p>
        <a href="/<?= $friend['username'] ?>">Profile</a>
    </div>
    <?php
}

