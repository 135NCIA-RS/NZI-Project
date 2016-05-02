<?php

namespace common\components;

use common\models\User;
use common\components\PhotoService;
use common\components\UserService;
use app\models\UserInfo;

class SearchService
{

	public static function findUsers($query)
	{
		$queries = explode(" ", $query);
		$users = [];
		foreach ($queries as $q)
		{
			self::findUser($q, $users);
			self::findUserByInfo($q, $users);
		}
		self::removeArrayRedundation($users);
		return $users;
	}

	private static function createUserObjByID($id)
	{
		$uid = new UserId($id);
		$u = $uid->getUser();
		$photo = $u->getImageUrl();
		$uname = $u->getUsername(); //already checked if ID exists
		$name = $u->getFullName();
		if ($name === false)
		{
			$name = $uname;
		}

		return ['name' => $name, 'photo' => $photo, 'link' => "/" . $uname];
	}

	private static function findUser($query, &$usersarray)
	{
		$id = UserService::getUserIdByName($query);
		if ($id !== false) // if true user exists (found in db)
		{
			$usersarray[] = self::createUserObjByID($id);
		}
	}

	private static function findUserByInfo($info, &$userarray)
	{
		$data = UserInfo::find()
			->select('user_id')
			->where(['user_name' => $info])
			->orWhere(['user_surname' => $info])
			->orWhere(['user_city' => $info])
			->all();
		foreach ($data as $var)
		{
			$userarray[] = self::createUserObjByID($var['user_id']);
		}
	}

	private static function removeArrayRedundation(&$array)
	{
		if (is_array($array))
		{
			$array = array_map("unserialize", array_unique(array_map("serialize", $array)));
		}
		else
		{
			return false;
		}
	}

}
