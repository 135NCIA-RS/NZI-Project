<?php
/**
 * Created by PhpStorm.
 * User: grzes
 * Date: 5/8/2016
 * Time: 1:37 PM
 */

namespace common\components;


class UserEvent
{
	private $event_type;
	private $event_id;
	private $userId;
	private $data_connected;
	private $user_connected;
	private $date;

	public function __construct($event_id, EEvent $event_type, UserId $owner, \DateTime $date, UserId $user_connected = null, $data_connected = "[]")
	{
		$this->event_id = $event_id;
		$this->event_type = $event_type;
		$this->userId = $owner;
		$this->data_connected = json_decode($data_connected);
		$this->user_connected = $user_connected;
		$this->date = $date;
	}

	public function getEventType()
	{
		return $this->event_type;
	}

	public function getEventOwner()
	{
		return $this->userId->getUser();
	}

	public function getConnectedUser()
	{
		return $this->user_connected === null ? null : $this->user_connected->getUser();
	}

	public function getConnectedData()
	{
		return $this->data_connected;
	}

	public function getEventDate($format = "Y-m-d H:i:s")
	{
		return $this->date->format($format);
	}

	public function getEventDateObj()
	{
		return $this->date;
	}



}