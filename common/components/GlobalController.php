<?php
/**
 * Created by PhpStorm.
 * User: Przemek
 * Date: 4/17/2016
 * Time: 6:25 PM
 */

namespace common\components;

use common\components\exceptions\InvalidUserException;
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
		$uid = null;
		try
		{
			$uid = new UserId($id);
		}
		catch(InvalidUserException $e)
		{
			Yii::$app->session->setFlash("error", "User does not exists");
			Yii::$app->user->logout(true);
		}

		$user = $uid->getUser();
		$this->view->params['userInfo'] = $user;
		////////////////////////////////////////////////////// request service
		$notification = RequestService::getMyRequests($uid);
		$this->view->params['notification_data'] = $notification;
		$this->view->params['notification_count'] = count($notification);
	}
}