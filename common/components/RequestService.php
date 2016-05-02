<?php

namespace common\components;

use app\models\Request;
use common\components\RelationService;
use common\components\RelationType;
use common\models\User;
use common\components\exceptions\InvalidDateException;
use common\components\exceptions\InvalidUserException;
use common\components\exceptions\InvalidEnumKeyException;
use MyCLabs\Enum\Enum;
use common\components\AccessService;

class RequestService
{

	/**
	 * Creating a request
	 *
	 * @param type                           $user1_id User who added id
	 * @param type                           $user2_id User whos adding id
	 * @param \common\components\RequestType $req_type type of request etc friend_request
	 * @param type                           $date date of added
	 *
	 * @return boolean false if reqtype is null,
	 */
	public static function createRequest(UserId $user1_id, UserId $user2_id, $req_type, $date)
	{

		$check = Request::find()
			->select('req_id')
			->where(['user1_id' => $user1_id->getId(), 'user2_id' => $user2_id->getId()])
			->one();
		if (!is_null($check))
		{
			return true;
		}

		if (!RequestType::isValid($req_type))
		{
			throw new InvalidEnumKeyException("ERROR, VAL: " . $req_type);
		}
		$data = new Request();
		$data->req_type = $req_type;
		$data->user1_id = $user1_id->getId();
		$data->user2_id = $user2_id->getId();
		$data->date = $date;
		return $data->save();
	}

	/**
	 * answering on request, accept or dismiss
	 *
	 * @param type $req_id
	 * @param type $answer answer on request true or false.
	 */
	public static function answerRequest($req_id, $answer)
	{
		$user1_id = RequestService::getUser1Id($req_id);
		$user2_id = RequestService::getUser2Id($req_id);
		$uid1 = new UserId($user1_id);
		$uid2 = new UserId($user2_id);

		///AccessService
		try
		{
			if (!AccessService::hasAccess($user2_id, ObjectCheckType::Request))
			{
				\Yii::$app->session->setFlash('error', 'Access Denied');
				return false;
			}
		}
		catch (Exception $ex)
		{
			Yii::$app->session->setFlash('warning', 'Something went wrong, contact Administrator');
			return false;
		}
		///end AccessService

		if ($answer)
		{
			RelationService::setRelation($uid1, $uid2, RelationType::Friend);
		}
		self::dropRequest($req_id);

		//TODO Przemek popraw to!
		$check = Request::find()
			->select('req_id')
			->where(['user1_id' => $user2_id, 'user2_id' => $user1_id, 'req_type' => 'friend'])
			->one();
		if (!is_null($check))
		{
			self::dropRequest($check['req_id']);
		}
	}

	/**
	 * getting user1 ID
	 *
	 * @param type $req_id
	 *
	 * @return user 1 ID
	 */
	public static function getUser1Id($req_id)
	{
		$data = Request::find()
			->select('user1_id')
			->where(['req_id' => $req_id])
			->one();
		return isset($data['user1_id']) ? $data['user1_id'] : false;
	}

	/**
	 *  getting user 2 ID
	 *
	 * @param type $req_id
	 *
	 * @return type User 2 ID
	 */
	public static function getUser2Id($req_id)
	{
		$data = Request::find()
			->select('user2_id')
			->where(['req_id' => $req_id])
			->one();
		return isset($data['user2_id']) ? $data['user2_id'] : false;
	}

	/**
	 * dropping a request//private function
	 *
	 * @param type $req_id
	 */
	private static function dropRequest($req_id)
	{
		$data = Request::findOne($req_id);
		$data->delete();
	}

	/**
	 *
	 * @param type $user2_id user who is logged.
	 *
	 * @return table with request to logged user.
	 */
	public static function getMyRequests(UserId $user2_idC) //TODO!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!DONT WORK
	{
		$user2_id = $user2_idC->getId();
		$arr = [];
		$rel = Request::find()
			->where([
				'user2_id' => $user2_id,
				'req_type' => RequestType::FriendRequest
			])
			->all();
		if (is_null($rel))
		{
			return [];
		}
		foreach ($rel as $var)
		{
			$arr[] = self::createReqObj($var['user1_id'], $var['date'], $var['req_id'], $var['req_type']);
		}
		return $arr;
	}

	private static function createReqObj($user1_id, $date, $req_id, $req_type)
	{
		$u1uid = new UserId($user1_id);
		$u1 = UserService::getUserById($u1uid);
		$uname = $u1->getUsername();
		$fullname = $u1->getFullName();
		return ['type' => $req_type, 'req_id' => $req_id, 'senderUserName' => $uname, 'date' => $date,
		        'fullname' => $fullname];
	}

	/**
	 * Getting Request Date
	 *
	 * @param type $req_id
	 *
	 * @return date of Request
	 */
	public static function getRequestDate($req_id)
	{
		$data = Request::find()
			->select('date')
			->where(['req_id' => $req_id])
			->one();
		return isset($data['date']) ? $data['date'] : false;
	}

	public static function isRequestBetween(UserId $user1_id, UserId $user2_id, $req_type)
	{
		if (!(RequestType::isValid($req_type)))
		{
			throw new InvalidEnumKeyException("Value: " . $req_type);
		}

		return Request::find()
			->where(['user1_id' => $user1_id->getId(), 'user2_id' => $user2_id->getId()])
			->orWhere(['user1_id' => $user2_id->getId(), 'user2_id' => $user1_id->getId()])
			->exists();
	}

}

class RequestType extends Enum
{

	const FriendRequest = "friend";

}

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

