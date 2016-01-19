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
    <div>
        <div>
            <?php
            if ($var['type'] == RequestType::FriendRequest)
            {
                ?>

                <?= $var['date'] ?> | <?= $var['senderUserName'] ?> sent you an friendship request.

                <?php
            }
            ?>
        </div>
        <div>
            <?= Html::beginForm("", 'post', []) ?>
            <button type="submit" name="accept-btn" style=" width: 100px;" class="btn btn-danger pull-right btn-block btn-sm">Accept</button>
            <button type="submit" name="dismiss-btn" style=" width: 100px;" class="btn btn-danger pull-right btn-block btn-sm">Dismiss</button>
            <input type="hidden" name="request_id" value="<?= $var['req_id'] ?>">
            <?= Html::endForm() ?>
        </div>
    </div>
    <?php
}
?>
