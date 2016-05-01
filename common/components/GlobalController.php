<?php
/**
 * Created by PhpStorm.
 * User: Przemek
 * Date: 4/17/2016
 * Time: 6:25 PM
 */

namespace common\components;

use yii\web\Controller;
use Yii;


abstract class GlobalController extends Controller
{
	public function beforeAction($event)
	{
		if (!\Yii::$app->user->isGuest)
		{
			$this->getUserData();
			$this->layout = '@app/views/layouts/logged';
		}
		else
		{

			$this->layout = '@app/views/layouts/main';
		}
		return parent::beforeAction($event);
	}

	public function getUserData()
	{
		$id = Yii::$app->user->getId();
		$user = UserService::getUserById($id);
		$this->view->params['userInfo'] = $user;
		////////////////////////////////////////////////////// request service
		$notification = RequestService::getMyRequests($id);
		$this->view->params['notification_data'] = $notification;
		$this->view->params['notification_count'] = count($notification);
	}
}