<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\components\RequestType;

$data = $this->params['notification_data'];
$count = $this->params['notification_count'];
foreach ($data as $var)
{
    ?>
    <div style="background-color: #dcebef; padding: 10px 10px 10px 10px; border-radius: 10px;  margin-bottom:5px;">
        <div>
            <?= Html::beginForm("", 'post', []) ?>
            <?php
            if ($var['type'] == RequestType::FriendRequest)
            {
                ?>

                <?= $var['date'] ?> | <?= $var['senderUserName'] ?> sent you an friendship request.
                <?php
            }
            ?>
            <button type="submit" name="dismiss-btn" style=" width: 100px; margin-left: 5px; margin-top: -5px;" class="btn btn-danger pull-right btn-sm">Dismiss</button>
            <button type="submit" name="accept-btn" style=" width: 100px; margin-top: -5px;" class="btn btn-success pull-right btn-sm">Accept</button>
            <input type="hidden" name="request_id" value="<?= $var['req_id'] ?>">
            <?= Html::endForm() ?>
            
        </div>
    </div>
    <?php
}
?>
