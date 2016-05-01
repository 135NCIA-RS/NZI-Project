<?php

namespace common\components;

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


class PostsService
{
	/*
	 * returns Friends Posts ordered by date
	 */
	public static function getFriendsPosts($id, $lastid = null)
	{
		$arr = [];
		$friendList = RelationService::getFriendsList($id);
		$friendList[] = $id;
		if (count($friendList) == 0)
		{
			return [];
		}

		$arr = self::getPostsOrderById($friendList, $lastid);

		return $arr;
	}

	/*
	 * used to sort posts by date
	 */
	private static function getPostsOrderById($friendsIds, $startId)
	{
		$data = Post::find()->where(['in', 'user_id', $friendsIds]);

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

	public static function getUserPosts(IntouchUser $user)
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

	public static function getPostById($postID)
	{
		$p = Post::findOne(['post_id' => $postID]);
		if (!is_null($p))
		{
			$comments = Comment::find()->where(['post_id' => $postID])->all();
			$comms = [];
			foreach ($comments as $comment)
			{
				$author = UserService::getUserById($comment->author_id);
				$date = new \DateTime($comment->comment_date);
				$comm = new \common\components\Comment($comment->comment_id, $date, $author, $comment->comment_text);
				$comms[] = $comm;
			}
			$attachments = []; //TODO
			$date = new \DateTime($p->post_date);
			$vis = $p->post_visibility;
			$visibility = EVisibility::$vis();

			$post = new \common\components\Post($postID, $date, $visibility, $comms, $attachments);
			return $post;
		}
		else
		{
			return null;
		}
	}

	public static function createPost($receiver_id, $text)
	{
		$author_id = Yii::$app->user->getId();
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
		$post->post_type = "text";
		$post->post_visibility = "visible";
		return $post->save();
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
			if (!AccessService::hasAccess($post->getId(), ObjectCheckType::PostComment))
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
		$comment->save();
		return new \common\components\Comment(
			$comment->comment_id,
			new \DateTime($comment->comment_date),
			UserService::getUserById($comment->author_id),
			$content
		);
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

}
