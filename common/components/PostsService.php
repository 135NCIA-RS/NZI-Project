<?php

namespace common\components;

use app\models\Event;
use Yii;
use common\components;
use app\models\UserInfo;
use common\models\User;
use common\components\Exceptions\InvalidDateException;
use common\components\Exceptions\InvalidUserException;
use yii\db\Query;
use app\models\Post;
use app\models\PostAttachment;
use app\models\Comment;
use common\components\RelationService;
use common\components\PostAttachment as PostAtt;


class PostsService
{
	/*
	 * returns Friends Posts ordered by date
	 */
	public static function getFriendsPosts(UserId $uid, $lastid = null)
	{
		$arr = [];
		$friendList = RelationService::getFriendsList($uid);
		$friendList[] = UserService::getUserById($uid);

		$arr = self::getPostsOrderById($friendList, $lastid);

		return $arr;
	}

	/**
	 * used to sort posts by date
	 *
	 * @param $friendsIds UserId[]
	 * @param $startId
	 *
	 * @return array
	 */
	private static function getPostsOrderById($friendsIds, $startId)
	{
		$fIds = [];
		foreach ($friendsIds as $fr)
		{
			$fIds[] = $fr->getId();
		}
		$data = Post::find()->where(['in', 'user_id', $fIds]);

		if (!is_null($startId))
		{
			$data->andWhere(['>', 'post_id', $startId]);
		}

		$data = $data->orderBy(['post_id' => SORT_DESC])
			->limit(5)
			->all();

		$counter = 0;
		$posts = [];
		foreach ($data as $row)
		{
			$posts[] = self::getPostById($row->post_id);
		}
		return $posts;
	}

	public static function getPostById($postID)
	{
		$p = Post::findOne(['post_id' => $postID]);
		if (!is_null($p))
		{
			$comments = Comment::find()->where(['post_id' => $postID])->all();
			$comms = [];
			foreach ($comments as $comment)
			{
				$author = new UserId($comment->author_id);
				$date = new \DateTime($comment->comment_date);
				$comm = new \common\components\Comment($comment->comment_id, $date, $author, $comment->comment_text);
				$comms[] = $comm;
			}
			$attachment = new PostAtt($postID);
			$attachments = ($attachment->getFile() == null) ? null : $attachment;
			$date = new \DateTime($p->post_date);
			$vis = $p->post_visibility;
			$visibility = EVisibility::$vis();
			$author = new UserId($p->owner_id);
			$pt = $p->post_type;
			$ptype = EPostType::$pt();
			$post = new \common\components\Post($postID, $author, $p->post_text, $date, $visibility, $ptype, $comms,
				$attachments);
			return $post;
		}
		else
		{
			return null;
		}
	}

	public static function getUserPosts(UserId $user)
	{
		$id = $user->getId();
		$data =
			Post::find()->select(['post_id'])->where(['user_id' => $id])->orderBy(['post_date' => SORT_DESC])->all();
		$posts = [];
		foreach ($data as $post)
		{
			$posts[] = self::getPostById($post->post_id);
		}
		return $posts;
	}

	public static function getPostAttachmentPhoto($post_id)
	{
		$data = PostAttachment::find()
			->where(['post_id' => $post_id])
			->one();
		if (!is_null($data))
		{
			return $data->file;
		}
		else
		{
			return false;
		}
	}

	public static function createPost(UserId $receiverId, $text)
	{
		$author_id = Yii::$app->user->getId();
		$receiver_id = $receiverId->getId();

		try
		{
			if (!AccessService::hasAccess($receiver_id, ObjectCheckType::Post))
			{
				Yii::$app->session->setFlash('error', 'Access Denied');
				return false;
			}
		}
		catch (Exception $ex)
		{
			Yii::$app->session->setFlash('warning', 'Something went wrong, contact Administrator');
			return false;
		}
		$post = new Post();
		$post->owner_id = $author_id;
		$post->user_id = $receiver_id;
		$post->post_text = $text;
		$post->post_date = date('Y-m-d H:i:s');
		$post->post_type = EPostType::text;
		$post->post_visibility = "visible";
		if ($post->save())
		{
			return $post->post_id;
		}
		else
		{
			return false;
		}
	}

	/*
	 * Adds the post attachment photo to the database
	 * Takes post_id and filename as arguments
	 */
	public static function addPostAttachmentPhoto($post_id, $file)
	{
		$att = new PostAttachment();
		$att->post_id = $post_id;
		$att->file = $file;
		return $att->save();
	}

	/**
	 * @param \common\components\Post $post
	 * @param IntouchUser             $author
	 * @param                         $content
	 *
	 * @return \common\components\Comment
	 */
	public static function createComment(\common\components\Post $post, $content)
	{
		try
		{
			if (!AccessService::hasAccess($post->getAuthor()->getId(), ObjectCheckType::PostComment))
			{
				Yii::$app->session->setFlash('error', 'Access Denied');
				return false;
			}
		}
		catch (Exception $ex)
		{
			Yii::$app->session->setFlash('warning', 'Something went wrong, contact Administrator');
			return false;
		}
		$comment = new Comment();
		$author_id = Yii::$app->user->getId();
		$comment->author_id = $author_id;
		$comment->comment_text = $content;
		$comment->comment_date = date('Y-m-d H:i:s');
		$comment->post_id = $post->getId();
		return $comment->save();
	}

	public static function getNumberOfComments(\common\components\Post $post)
	{
		return count($post->getComments());
	}

	public static function deletePost(\common\components\Post $post)
	{
		return self::_deletePost($post->getId());
	}

	private static function _deletePost($id)
	{
		$del = Post::findOne($id);
		return isset($del) ? $del->delete() : false;
	}

	public static function deleteComment(\common\components\Comment $comment)
	{
		return self::_deleteComment($comment->getId());
	}

	private static function _deleteComment($id)
	{
		$del = Comment::findOne($id);
		return isset($id) ? $del->delete() : false;
	}

	public static function savePost(\common\components\Post $post)
	{
		//tu pobierz wszystko z posta i zapisz (łacznie z komentarzami itd.
		throw new components\exceptions\FeatureNotImplemented();
	}

	public static function removePost(\common\components\Post $post)
	{
		//usun post
		throw new components\exceptions\FeatureNotImplemented();
	}

	public static function getCommentById($id)
	{
		$c = Comment::findOne($id);
		$com = new \common\components\Comment(
			$c->comment_id,
			new \DateTime($c->comment_date),
			new UserId($c->author_id),
			$c->comment_text
		);
		return $com;
	}

	public static function saveComment(\common\components\Comment $comment)
	{
		$c = Comment::findOne($comment->getId());
		$c->comment_text = $comment->getContent();
		return $c->save();
	}

	public static function removeComment(\common\components\Comment $comment)
	{
		return Comment::findOne($comment->getId())->delete();
	}

}
