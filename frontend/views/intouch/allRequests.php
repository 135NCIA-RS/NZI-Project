<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\components\RequestType;


$data=$this->params['notification_data'];
$count=$this->params['notification_count'];
foreach($data as $var)
{
    ?>
<div>
    <div>
        <?php
            if($var['type']==RequestType::FriendRequest)
            { ?>
                You have friendship request from <?=$var['senderUserName']?>
                <?php
            }
        ?>
    </div>
    <div>
<form method="post">
    <button type="submit" style=" width: 100px;" class="btn btn-danger pull-right btn-block btn-sm">Accept</button>
    <button type="submit" style=" width: 100px;" class="btn btn-danger pull-right btn-block btn-sm">Dismiss</button>
</form>
    </div>
</div>
<?php
}
?>
