<?php
/**
 * Created by PhpStorm.
 * User: grzes
 * Date: 5/22/2016
 * Time: 8:48 PM
 */

namespace common\components;


class Token
{
	private $token;
	private $exp_date;
	private $uid;
	private $type;

	/**
	 * Token constructor.
	 *
	 * @param string     $token
	 * @param ETokenType $type
	 * @param UserId     $uid
	 * @param \DateTime  $exp_date
	 */
	public function __construct($token, ETokenType $type, UserId $uid, \DateTime $exp_date)
	{
		$this->token = $token;
		$this->uid = $uid;
		$this->exp_date = $exp_date;
		$this->type = $type;
	}

	/**
	 * @return UserId
	 */
	public function getUserId()
	{
		return $this->uid;
	}

	/**
	 * @return bool
	 */
	public function isExpireable()
	{
		return $this->exp_date != null;
	}

	/**
	 * @return \DateTime|null
	 */
	public function getExpirationDate()
	{
		return $this->exp_date;
	}

	/**
	 * @return ETokenType
	 */
	public function getTokenType()
	{
		return $this->type;
	}

	/**
	 * @return string
	 */
	public function getToken()
	{
		return $this->token;
	}





}