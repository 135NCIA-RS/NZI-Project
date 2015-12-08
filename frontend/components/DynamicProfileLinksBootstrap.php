<?php

namespace app\components;

use Yii;
use common\models\User;
use app\components\exceptions\InvalidUserException;
use yii\base\BootstrapInterface;

class DynamicProfileLinksBootstrap implements BootstrapInterface
{

    public function bootstrap($app)
    {
        // $app->getUrlManager()->addRules(['macintoshx' => 'intouch/profile'],false);
        $users = User::find()
                ->select('username')
                ->where(['status' => '10'])
                ->all();
        foreach ($users as $user)
        {
            $app->getUrlManager()->addRules([
                strtolower($user['username']) => 'intouch/userprofile',
                $user['username'] => 'intouch/userprofile'
                    ], false);
        }
    }

}
