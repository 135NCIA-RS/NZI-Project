<?php
/**
 * Created by PhpStorm.
 * User: grzes
 * Date: 3/20/2016
 * Time: 4:27 PM
 */

namespace common\components;

use MyCLabs\Enum\Enum;

class ImageTypes extends Enum
{
	const __default = self::InTouchImage;
	const PostPhoto = "postphoto";
	const ProfilePhoto = "postphoto";
	const PostCommentPhoto = "postcommentphoto";
	const InTouchImage = "intouchSystemImage";
	const GalleryPhoto = "galleryphoto";
}