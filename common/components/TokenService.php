<?php
/**
 * Created by PhpStorm.
 * User: grzes
 * Date: 5/22/2016
 * Time: 8:39 PM
 */

namespace common\components;

use \common\models\UserTokens;

class TokenService
{
	/**
	 * @param UserId     $uid
	 * @param ETokenType $tokenType
	 * @param bool       $expireable
	 * @param \DateTime|null       $expirationDate
	 */
	public static function createToken(UserId $uid, ETokenType $tokenType, $expireable = false, $expirationDate = null)
	{
		$token = new UserTokens();
		$token->user_id = $uid->getId();
		$token->token_type = $tokenType->getValue();
		$token->token_expirable = $expireable;
		if(!is_null($expirationDate))
		{
			$token->token_expiration_date = $expirationDate->format('Y-m-d H:i:s');
		}

		return $token->save();
	}
	
	public static function getUserTokens(UserId $uid)
	{
		$tokens = [];
		$tkns = UserTokens::findAll(['user_id' => $uid->getId()]);
		foreach($tkns as $var)
		{
			$tokenType = ETokenType::search($var->token_type);
			$token = new Token($var->token,  ETokenType::$tokenType(), $var->user_id, $var->token_expiration_date);
			$tokens[] = $token;
		}
		return $tokens;
	}

	/**
	 * @param UserId     $uid
	 * @param ETokenType $type
	 */
	public static function getUserTokensByType(UserId $uid, ETokenType $type)
	{
		$tokens = [];
		$tkns = UserTokens::findAll(['user_id' => $uid->getId(), 'token_type' => $type->getValue()]);
		foreach($tkns as $var)
		{
			$tokenType = ETokenType::search($var->token_type);
			$token = new Token($var->token, ETokenType::$tokenType(), $var->user_id, $var->token_expiration_date);
			$tokens[] = $token;
		}
		return $tokens;
	}
	
	public static function revokeToken(Token $token)
	{
		$token = UserTokens::findOne(['token' => $token->getToken()]);
		if($token != null)
		{
			return $token->delete();
		}
		else
		{
			return null;
		}
	}
	
}