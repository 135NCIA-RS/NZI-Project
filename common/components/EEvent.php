<?php
/**
 * Created by PhpStorm.
 * User: grzes
 * Date: 5/8/2016
 * Time: 12:20 PM
 */

namespace common\components;


use MyCLabs\Enum\Enum;

class EEvent extends Enum
{
	//ACCOUNT
	const ACCOUNT_CREATE = 1;
	const ACCOUNT_INFO_CHANGED = 2;
	const ACCOUNT_PASSWORD_CHANGED = 3;
	const ACCOUNT_PASSWORD_RESET = 4;

	//POSTS
	const POST_CREATE = 101;
	const POST_DELETE = 102;
	const POST_EDIT = 103;
	const POST_LIKED = 104;
	const POST_UNLIKED = 105;
	
	//COMMENTS
	const COMMENT_CREATE = 201;
	const COMMENT_DELETE = 202;
	const COMMENT_EDIT = 203;

	//ACTIONS
	const FOLLOWS = 301;
	const FRIEND_REQUEST_SENT = 302;
	const FRIEND_REQUEST_ACCEPTED = 303;
	const FRIEND_REQUEST_DENIED = 304;
	const UNFRIEND = 305;
	const UNFOLLOWS = 306;

}