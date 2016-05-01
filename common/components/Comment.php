<?php
/**
 * Created by PhpStorm.
 * User: grzes
 * Date: 4/30/2016
 * Time: 11:05 PM
 */

namespace common\components;


use common\components\exceptions\FeatureNotImplemented;

class Comment
{
	private $Content;
	private $Id;
	private $Date;
	private $Author;
	private $isEdited;

	public function __construct($Id, \DateTime $Date, IntouchUser $Author, $Content, $isEdited = false)
	{
		$this->Content = $Content;
		$this->Id = $Id;
		$this->Date = $Date;
		$this->Author = $Author;
		$this->isEdited = $isEdited;
	}
	
	public function getId()
	{
		return $this->Id;
	}
	
	public function getDate()
	{
		return $this->Date;
	}
	
	public function getContent()
	{
		return $this->Content;
	}
	
	public function isEdited()
	{
		return $this->isEdited;
	}
	
	public function changeContent($newContent)
	{
		$this->Content = $newContent;
		$this->isEdited = true;
	}
	
	public function getAuthor()
	{
		return $this->Author;
	}
}