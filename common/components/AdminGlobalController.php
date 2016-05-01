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

		if(!Yii::$app->user->isGuest)
		{
			$this->getUserData();
		}
		else
		{
			$this->layout="@app/views/layouts/main";
			return parent::beforeAction($event);
		}

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
		$user = UserService::getUserById($id);
		$this->view->params['userInfo'] = $user;
		////////////////////////////////////////////////////// request service
		$notification = RequestService::getMyRequests($id);
		$this->view->params['notification_data'] = $notification;
		$this->view->params['notification_count'] = count($notification);
	}
}