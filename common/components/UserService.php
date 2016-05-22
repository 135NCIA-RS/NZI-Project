<?php

namespace common\components;

use app\models\UserInfo;
use common\components\exceptions\FeatureNotImplemented;
use common\models\User;
use common\components\exceptions\InvalidDateException;
use common\components\exceptions\InvalidUserException;
use common\models\UserTokens;

class UserService
{
	/**
	 * Returns Id for specified user's name (username)
	 *
	 * @param string $name
	 *
	 * @return string|boolean
	 */
	public static function getUserIdByName($name)
	{
		$data = User::find()
			->select('id')
			->where(['username' => $name])
			->one();
		return isset($data['id']) ? $data['id'] : false;
	}

	/**
	 * Sets new password for specified user's id
	 *
	 * @param int    $id User's ID
	 * @param string $password User's new password
	 *
	 * @return boolean true on success, false on fail
	 * @throws InvalidUserException if user does not exist
	 */
	public static function setPassword($id, $password)
	{
		$user = User::findOne($id);
		if ($user == null)
		{
			throw new InvalidUserException();
		}

		$user->password = $password;
		if ($user->save())
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Checks if user exists
	 *
	 * @param int $user_id User's ID
	 *
	 * @return boolean true if exists, false if not
	 */
	public static function existUser($user_id)
	{
		$user = User::findOne($user_id);
		return ($user != null);
	}

	/**
	 * Returns username for specified user's id (or false)
	 *
	 * @param int $id User's ID
	 *
	 * @return string|boolean Username or false on error
	 */
	public static function getUserName($id)
	{
		$data = User::find()
			->select("username")
			->where(['id' => $id])
			->one();
		return isset($data['username']) ? $data['username'] : false;
	}

	/**
	 * @param $id
	 *
	 * @return IntouchUser
	 */
	public static function getUserById(UserId $uid)
	{
		$id = $uid->getId();
		$u = User::findOne($id);

		if (!is_null($u))
		{
			$details = UserInfo::findOne($id);
			$intouchUser = new IntouchUser(
				$id,
				$u->email,
				$u->username,
				$details->user_name,
				$details->user_surname,
				new \DateTime($details->user_birthdate),
				PhotoService::getProfilePhoto($id),
				$details->user_education,
				$details->user_city,
				$details->user_about
			);
			return $intouchUser;
		}
		else
		{
			return null;
		}
	}

	/**
	 * @param IntouchUser $user
	 *
	 * @return bool
	 */
	public static function saveUser(IntouchUser $user)
	{
		$id = $user->getId();
		$u = User::findIdentity($id);
		/* @var $u User*/
		$u->email = $user->getEmail();
		$u->username = $user->getUsername();
		if(!$u->save()) { return false; }
		$uinfo = UserInfo::findOne($id);
		/* @var $uinfo UserInfo */
		$uinfo->user_about = $user->getAbout();
		$uinfo->user_birthdate = $user->getBirthDate();
		$uinfo->user_city = $user->getCity();
		$uinfo->user_education = $user->getEducation();
		$uinfo->user_name = $user->getName();
		$uinfo->user_surname = $user->getSurname();
		if(!$uinfo->save()){ return false; };
		return true;
	}

	public static function activateUser(UserId $uid)
	{
		$token = self::getUserActivationToken($uid);
		TokenService::revokeToken($token);

		$user = User::findOne(['id' => $uid->getId()]);
		$user->status = 10; // UserId checks if user exists.
		return $user->save();
	}

	public static function getUserActivationToken(UserId $uid)
	{
		$tokens = TokenService::getUserTokensByType($uid, ETokenType::ACCOUNT_ACTIVATION());
		if(count($tokens) > 0)
		{
			return $tokens[0];
		}
		else
		{
			return null;
		}
	}

	public static function isUserAccountActivated(UserId $uid)
	{
		$user = User::findOne(['id' => $uid->getId()]);
		return ($user->status == EAccountStatus::STATUS_ACTIVE && $user->status != EAccountStatus::STATUS_NOTACTIVATED);
	}





}
