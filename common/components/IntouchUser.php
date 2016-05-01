<?php
/**
 * Created by PhpStorm.
 * User: grzes
 * Date: 4/30/2016
 * Time: 11:50 PM
 */

namespace common\components;


class IntouchUser
{
	private $id;
	private $name;
	private $surname;
	private $birth;
	private $photo;
	private $education;
	private $city;
	private $about;
	private $username;
	private $email;


	public function __construct($id, $email, $username, $name, $surname, \DateTime $birth, $photo, $education, $city,
	                            $about)
	{
		$this->id = $id;
		$this->name = $name;
		$this->surname = $surname;
		$this->birth = $birth;
		$this->photo = $photo;
		$this->education = $education;
		$this->city = $city;
		$this->about = $about;
		$this->username = $username;
		$this->email = $email;
	}

	public function getId()
	{
		return $this->id;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function getImageUrl()
	{
		return $this->photo;
	}

	public function getFullName()
	{
		return $this->name . " " . $this->surname;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getSurname()
	{
		return $this->surname;
	}

	public function setSurname($surname)
	{
		$this->surname = $surname;
	}

	public function getEducation()
	{
		return $this->education;
	}

	public function setEducation($edu)
	{
		$this->education = $edu;
	}

	public function getCity()
	{
		return $this->city;
	}

	public function setCity($city)
	{
		$this->city = $city;
	}

	public function getBirthDate($format = "Y-m-d H:i:s")
	{
		return $this->birth->format($format);
	}

	public function getBirthDateTimeObj()
	{
		return $this->birth;
	}

	public function getAbout()
	{
		return $this->about;
	}

	public function setAbout($about)
	{
		$this->about = $about;
	}

	public function changePassword($newPassword)
	{
		UserService::setPassword($this->id, $newPassword);
	}

}