<?php
/**
 * Created by PhpStorm.
 * User: grzes
 * Date: 4/30/2016
 * Time: 11:05 PM
 */

namespace common\components;
use common\components\exceptions\FeatureNotImplemented;


/**
 * Class Post is created only for existing posts!
 * @package common\components
 */
class Post
{
	private $Id;
	private $Content;
	private $Date;
	private $Visibility;
	private $Attachments;
	private $Comments;
	private $isEdited;

	/**
	 * Post constructor.
	 *
	 * @param $postId
	 */
	public function __construct($Id, $Date, EVisibility $Visibility, $Comments = [], $Attachments = [], $isEdited = false)
	{
		$this->Id = $Id;
		$this->Date = $Date;
		$this->Visibility = $Visibility;
		$this->Attachments = $Attachments;
		$this->Comments = $Comments;
		$this->isEdited = $isEdited;
	}

	/**
	 * @return int Id of Post
	 */
	public function getId()
	{
		return $this->Id;
	}

	/**
	 * @return string Content of Post
	 */
	public function getContent()
	{
		return $this->Content;
	}

	/**
	 * @return DateTime Date
	 */
	public function getDate()
	{
		return $this->Date;
	}

	/**
	 * @return EVisibility
	 */
	public function getVisibility()
	{
		return $this->Visibility;
	}

	/**
	 * @return array
	 */
	public function getAttachments()
	{
		return $this->Attachments;
	}

	/**
	 * @return array
	 */
	public function getComments()
	{
		return $this->Comments;
	}

	/**
	 * @return bool
	 */
	public function isEdited()
	{
		return $this->isEdited;
	}

	public function addAttachment()
	{
		throw new FeatureNotImplemented();
	}

	/**
	 * @param IntouchUser $author
	 * @param string $content
	 */
	public function addComment(IntouchUser $author, $content)
	{
		$comm = PostsService::createComment(this, $content);
		$this->Comments[] = $comm;
	}
	
	public function changeContent($newContent)
	{
		$this->Content = $newContent;
		$this->isEdited = true;
	}
	
	public function Save()
	{
		return PostsService::savePost(this);
	}
	
	public function Remove()
	{
		return PostsService::removePost(this);
	}

}