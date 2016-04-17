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
			$this->view->params['userData'] = $this->getUserData();
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

		$photo = PhotoService::getProfilePhoto($id);
		$this->view->params['userProfilePhoto'] = $photo;

		$userinfo = array();
		$userinfo['user_name'] = UserService::getName($id);
		$userinfo['user_surname'] = UserService::getSurname($id);
		if ($userinfo['user_name'] == false)
		{
			$userinfo['user_name'] = "Uzupełnij";
		}
		if ($userinfo['user_surname'] == false)
		{
			$userinfo['user_surname'] = "swoje dane";
		}

		$this->view->params['userInfo'] = $userinfo;
		////////////////////////////////////////////////////// request service

		$notification = RequestService::getMyRequests($id);
		$tablelength = count($notification);
		$this->view->params['notification_data'] = $notification;
		$this->view->params['notification_count'] = $tablelength;
	}
}