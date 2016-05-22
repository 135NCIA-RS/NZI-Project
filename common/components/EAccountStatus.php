<?php
/**
 * Created by PhpStorm.
 * User: grzes
 * Date: 5/8/2016
 * Time: 12:20 PM
 */

namespace common\components;


use MyCLabs\Enum\Enum;

class EAccountStatus extends Enum
{
	const STATUS_DELETED = 0;
	const STATUS_ACTIVE = 10;
	const STATUS_NOTACTIVATED = 5;
}