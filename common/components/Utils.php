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
		{
			$host = $url;
		}
		if (substr($host, 0, 4) == "www.")
		{
			$host = substr($host, 4);
		}

		while (substr_count($host, ".") > 1) // remove subdomains
		{
			$val = strpos($host, ".");
			$host = substr($host, $val + 1);
		}

		if($host == "bluequeen.tk"){$host = "intouch.bluequeen.tk";} // Just a shitty override...

		return $host;
	}
}