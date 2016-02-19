<?php

namespace common\components;

use Yii;
use common\models\User;
use common\components\exceptions\InvalidUserException;
use yii\base\BootstrapInterface;

/**
 * @deprecated No longer in use
 */
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
                $user['username']             => 'intouch/userprofile'
                    ], true);
        }
    }

}
