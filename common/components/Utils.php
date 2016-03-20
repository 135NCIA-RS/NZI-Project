<?php
/**
 * Created by PhpStorm.
 * User: grzes
 * Date: 3/20/2016
 * Time: 7:34 PM
 */

namespace common\components;


class Utils
{
	public static function url_to_domain($url)
	{
		$host = @parse_url($url, PHP_URL_HOST);
		if (!$host)
			$host = $url;
		if (substr($host, 0, 4) == "www.")
			$host = substr($host, 4);
		if(\Yii::$app->id == "app-frontend")
		{
			if (substr_count($host, ".") > 1) // remove subdomain
			{
				$val = strpos($host, ".");
				$host = substr($host, $val + 1);
			}
		}
		else
		{
			while (substr_count($host, ".") > 1) // remove subdomains
			{
				$val = strpos($host, ".");
				$host = substr($host, $val + 1);
			}
		}
		return $host;
	}
}