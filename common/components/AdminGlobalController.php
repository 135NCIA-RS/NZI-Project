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


abstract class AdminGlobalController extends Controller
{
	public function beforeAction($event)
	{
		$excludedActions = [
			'denied',
		    'login',
		    'error',
		];

		$this->getUserData();

		if (Yii::$app->user->can('admin'))
		{
			$this->layout = "@app/views/layouts/Admin";
		}
		else
		{
			$this->layout = "@app/views/layouts/NonAdmin";
			$exclude = false;
			foreach($excludedActions as $var)
			{
				if($event->id == $var)
				{
					$exclude = true;
				}
			}
			if(!$exclude)
			{
				Yii::$app->getResponse()->redirect('denied');
			}

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