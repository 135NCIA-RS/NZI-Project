<?php

namespace common\components;

use Yii;
use yii\base\BootstrapInterface;


class InTouchBootstrap implements BootstrapInterface
{

    public function bootstrap($app)
    {
        $url = "http://media." . Utils::url_to_domain($_SERVER['SERVER_NAME']);
        Yii::setAlias("media", $url);
    }

}
