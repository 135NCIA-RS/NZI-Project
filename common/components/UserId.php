<?php
/**
 * Created by PhpStorm.
 * User: grzes
 * Date: 5/2/2016
 * Time: 2:02 PM
 */

namespace common\components;


use common\components\exceptions\InvalidUserException;

class UserId
{
	private $id;

	/**
	 * UserId constructor.
	 *
	 * @param $userId
	 * @throws InvalidUserException
	 */
	public function __construct($userId)
	{
		if(!UserService::existUser($userId))
		{
			throw new InvalidUserException("User does not exist");
		}
		$this->id = $userId;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getUser()
	{
		return UserService::getUserById($this);
	}

}