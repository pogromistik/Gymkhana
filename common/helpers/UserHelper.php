<?php

namespace common\helpers;

use common\models\User;
use yii\base\ErrorException;

class UserHelper
{
	const CONSOLE_LOG_USER_ID = -1;
	
	/**
	 * @return null|User
	 */
	public static function getUser()
	{
		if(\Yii::$app->user->isGuest)
		{
			return null;
		}
		if(!isset(\Yii::$app->user))
		{
			return null;
		}
		if (\Yii::$app->id != 'app-admin') {
			return null;
		}
		return \Yii::$app->user->identity;
	}
	
	public static function getUserId($allowSystem = true)
	{
		if ($user = UserHelper::getUser()) {
			return $user->id;
		} else {
			return self::CONSOLE_LOG_USER_ID;
		}
	}
}