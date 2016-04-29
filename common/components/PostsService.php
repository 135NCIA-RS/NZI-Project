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
			$posts = [];
			return $posts;
		}

		$arr = self::getPostsOrderById($friendList, $lastid);
//		foreach ($friendList as $friend)
//		{
//			$arr[] = self::getPostsOrderById($friend);
//		}
		//convert to two dimensional array from three dimensional
		//$posts = call_user_func_array('array_merge', $arr);
		//sort posts(array) by date
		//usort($posts, ["\common\components\PostsService", "date_compare"]);
		return $arr;

	}

	/*
	 * used to sort posts by date
	 */
	private static function date_compare($a, $b)
	{
		$t1 = strtotime($a['post_date']);
		$t2 = strtotime($b['post_date']);
		return $t2 - $t1;
	}

	public static function getPostsOrderById($friendsIds, $startId)
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
		foreach ($data as $row)
		{
			$refined_data[$counter]['post_id'] = (int)$row['post_id'];

			$refined_data[$counter]['owner_id'] = $row['owner_id'];
			$refined_data[$counter]['name'] = UserService::getName($row['owner_id']);
			$refined_data[$counter]['surname'] = UserService::getSurname($row['owner_id']);

			$refined_data[$counter]['post_visibility'] = $row['post_visibility'];
			$refined_data[$counter]['post_date'] = $row['post_date'];
			$refined_data[$counter]['post_type'] = $row['post_type'];
			$refined_data[$counter]['post_text'] = $row['post_text'];
			$refined_data[$counter]['comments'] = PostsService::getComments($row['post_id']);
			$refined_data[$counter]['attachments'] = PostsService::getAttachments($row['post_id']);
			$refined_data[$counter]['photo'] =
				PhotoService::getProfilePhoto($refined_data[$counter]['owner_id'], true, true);
			$counter++;
		}
		return isset($refined_data) ? $refined_data : [];
	}


	public static function getPosts($id)
	{
		$data = Post::find()->where(['user_id' => $id])->orderBy(['post_date' => SORT_DESC])->all();
		$counter = 0;
		foreach ($data as $row)
		{
			$refined_data[$counter]['post_id'] = (int)$row['post_id'];
			if ($row['owner_id'] != NULL)
			{
				$refined_data[$counter]['owner_id'] = $row['owner_id'];
				$refined_data[$counter]['name'] = UserService::getName($row['owner_id']);
				$refined_data[$counter]['surname'] = UserService::getSurname($row['owner_id']);
			}
			else
			{
				$refined_data[$counter]['owner_id'] = $id;
				$refined_data[$counter]['name'] = UserService::getName($id);
				$refined_data[$counter]['surname'] = UserService::getSurname($id);
			}
			$refined_data[$counter]['post_visibility'] = $row['post_visibility'];
			$refined_data[$counter]['post_date'] = $row['post_date'];
			$refined_data[$counter]['post_type'] = $row['post_type'];
			$refined_data[$counter]['post_text'] = $row['post_text'];
			$refined_data[$counter]['comments'] = PostsService::getComments($row['post_id']);
			$refined_data[$counter]['attachments'] = PostsService::getAttachments($row['post_id']);
			$refined_data[$counter]['photo'] =
				PhotoService::getProfilePhoto($refined_data[$counter]['owner_id'], true, true);
			$counter++;
		}

		return isset($refined_data) ? $refined_data : [];
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
		//if ($receiver_id != $author_id)
		//{
		$post->owner_id = $author_id;
		//}
		$post->user_id = $receiver_id;
		$post->post_text = $text;
		$post->post_date = date('Y-m-d H:i:s');
		$post->post_type = "text";
		$post->post_visibility = "visible";
		return $post->save();
	}

	public static function createComment($post_id, $text)
	{
		try
		{
			if (!AccessService::hasAccess($post_id, ObjectCheckType::PostComment))
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
		$comment->comment_text = $text;
		$comment->comment_date = date('Y-m-d H:i:s');
		$comment->post_id = $post_id;
		$comment->save();
	}

	public static function getNumberOfComments($post_id)
	{
		$data = Comment::find()->where(['post_id' => $post_id])->all();
		return isset($data) ? count($data) : false;
	}

	public static function getPostDate($post_id)
	{
		$data = Post::find()->where(['post_id' => $post_id])->one();
		return isset($data) ? $data['post_date'] : false;
	}

	public static function getComments($post_id)
	{
		$data = Comment::find()->where(['post_id' => $post_id])->all();
		$counter = 0;
		$refined_data = [];
		foreach ($data as $row)
		{
			$refined_data[$counter]['comment_text'] = $row['comment_text'];
			$refined_data[$counter]['name'] = UserService::getName($row['author_id']);
			$refined_data[$counter]['surname'] = UserService::getSurname($row['author_id']);
			$refined_data[$counter]['comment_date'] = $row['comment_date'];
			$refined_data[$counter]['photo'] = PhotoService::getProfilePhoto($row['author_id'], true, true);
			$counter++;
		}
		return isset($refined_data) ? $refined_data : false;
	}

	public static function getAttachments($post_id)
	{
		$data = PostAttachment::find()->where(['post_id' => $post_id])->all();
		return isset($data) ? $data : false;
	}

	public static function getPost($id)
	{
		$row = Post::find()->where(['post_id' => $id])->one();


		$refined_data['post_id'] = (int)$row['post_id'];
		if ($row['owner_id'] != NULL)
		{
			$refined_data['owner_id'] = $row['owner_id'];
			$refined_data['name'] = UserService::getName($row['owner_id']);
			$refined_data['surname'] = UserService::getSurname($row['owner_id']);
		}
		else
		{
			$refined_data['owner_id'] = $id;
			$refined_data['name'] = UserService::getName($id);
			$refined_data['surname'] = UserService::getSurname($id);
		}
		$refined_data['post_visibility'] = $row['post_visibility'];
		$refined_data['post_date'] = $row['post_date'];
		$refined_data['post_type'] = $row['post_type'];
		$refined_data['post_text'] = $row['post_text'];
		$refined_data['comments'] = PostsService::getComments($row['post_id']);
		$refined_data['attachments'] = PostsService::getAttachments($row['post_id']);
		$refined_data['photo'] =
			PhotoService::getProfilePhoto($refined_data['owner_id'], true, true);

		return isset($refined_data) ? $refined_data : [];

	}

	public static function getComment($id)
	{
		$row = Comment::find()->where(['comment_id' => $id])->one();
		$refined_data = [];
		$refined_data['comment_id'] = $row['comment_id'];
		$refined_data['comment_text'] = $row['comment_text'];
		$refined_data['name'] = UserService::getName($row['author_id']);
		$refined_data['surname'] = UserService::getSurname($row['author_id']);
		$refined_data['comment_date'] = $row['comment_date'];
		$refined_data['photo'] = PhotoService::getProfilePhoto($row['author_id'], true, true);
		//die(var_dump($refined_data));


		return isset($refined_data) ? $refined_data : false;
	}

	public static function deletePost($id)
	{
		$del = Post::findOne($id);
		return isset($del) ? $del->delete() : false;
	}
}
