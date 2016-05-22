<?php

namespace frontend\models;

use app\models\UserInfo;
use common\components\EAccountStatus;
use common\components\EEvent;
use common\components\ETokenType;
use common\components\EventService;
use common\components\UserId;
use common\models\User;
use common\models\UserTokens;
use yii\base\Model;
use Yii;
use yii\helpers\Url;

/**
 * Signup form
 */
class SignupForm extends Model
{

	public $username;
	public $email;
	public $password;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['username', 'filter', 'filter' => 'trim'],
			['username', 'required'],
			['username', 'unique', 'targetClass' => '\common\models\User',
			 'message' => 'This username has already been taken.'],
			['username', 'string', 'min' => 2, 'max' => 255],
			['email', 'filter', 'filter' => 'trim'],
			['email', 'required'],
			['email', 'email'],
			['email', 'string', 'max' => 255],
			['email', 'unique', 'targetClass' => '\common\models\User',
			 'message' => 'This email address has already been taken.'],
			['password', 'required'],
			['password', 'string', 'min' => 6],
		];
	}

	/**
	 * Signs user up.
	 *
	 * @return User|null the saved model or null if saving fails
	 */
	public function signup()
	{
		if ($this->validate())
		{
			$user = new User();
			$user->username = $this->username;
			$user->email = $this->email;
			$user->setPassword($this->password);
			$user->generateAuthKey();
			$user->status = User::STATUS_NOTACTIVATED;

			$b = $user->save();

			$activationToken = new UserTokens();
			$activationToken->user_id = $user->id;
			$activationToken->token_type = ETokenType::ACCOUNT_ACTIVATION;
			$activationToken->token = sha1(mt_rand(10000, 99999) . time() . $user->email);
			$activationToken->save();

			$auth = Yii::$app->authManager;
			$userRole = $auth->getRole('user');
			$auth->assign($userRole, $user->id);

			if ($b)
			{
				$x = new UserInfo();
				$x->user_id = $user->id;
				$x->save();
				EventService::createEvent(EEvent::ACCOUNT_CREATE(), new UserId($user->id));
				$this->sendActivationMail($user, $activationToken->token);
				return $user;
			}
		}

		return null;
	}

	private function sendActivationMail(User $user, $token)
	{
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$headers .= 'From: InTouch < noreply@intouch.com>' . "\r\n";

		$subject = "Your Activation Link";
		$url = Url::toRoute(['site/activate', 'email' => $user->email, 'token' => $token],true);
		$message =
			"<html><body>Thanks for joining!<br/><br/>Please click this link below to activate your membership<br /><br/><br/><a href='" .
			$url .
			"'>". $url . "</a><br/><br/>Thanks.</body></html>";
		Yii::$app->mailer->compose()
			->setFrom('noreply@InTouch.com')
			->setTo($user->email)
			->setSubject($subject)
			->setHtmlBody($message)
			->send();
	}

}
