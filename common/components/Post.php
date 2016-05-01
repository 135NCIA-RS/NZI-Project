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
	private $author;
	private $postType;

	/**
	 * Post constructor.
	 *
	 * @param $postId
	 */
	public function __construct($Id, $author, $content, \DateTime $Date, EVisibility $Visibility, EPostType $PostType, $Comments = [], $Attachments = [], $isEdited = false)
	{
		$this->Id = $Id;
		$this->Date = $Date;
		$this->Visibility = $Visibility;
		$this->Attachments = $Attachments;
		$this->Comments = $Comments;
		$this->isEdited = $isEdited;
		$this->author = $author;
		$this->postType = $PostType;
		$this->Content = $content;
	}

	/**
	 * @return int Id of Post
	 */
	public function getId()
	{
		return $this->Id;
	}

	/**
	 * @return IntouchUser
	 */
	public function getAuthor()
	{
		return $this->author;
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
	public function getDateObj()
	{
		return $this->Date;
	}

	public function getDate($format = "Y-m-d H:i:s")
	{
		return $this->Date->format($format);
	}

	/**
	 * @return EVisibility
	 */
	public function getVisibility()
	{
		return $this->Visibility;
	}

	/**
	 * @param EVisibility $visibility
	 *
	 * @return bool
	 */
	public function checkVisibility(EVisibility $visibility)
	{
		return ($this->Visibility == $visibility);
	}

	/**
	 * @return array
	 */
	public function getAttachments()
	{
		return $this->Attachments;
	}

	/**
	 * @return Comment[]
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

	/**
	 * @return EPostType
	 */
	public function getPostType()
	{
		return $this->postType;
	}

	/**
	 * @param EPostType $postType
	 *
	 * @return bool
	 */
	public function checkPostType(EPostType $postType)
	{
		return ($this->postType == $postType);
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