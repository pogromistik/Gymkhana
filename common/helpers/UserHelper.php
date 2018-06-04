<?php

namespace common\helpers;

use common\models\Region;
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
		if(!isset(\Yii::$app->user))
		{
			return null;
		}
		if(\Yii::$app->user->isGuest)
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
	
	/*
	 * Доступ: организаторы редактируют всё, админы - внутри региона, судьи - только если сами создали
	 */
	public static function accessAverage($regionId, $creatorUserId)
	{
		if (!\Yii::$app->user->can('globalWorkWithCompetitions')) {
			if (!\Yii::$app->user->can('projectOrganizer')) {
				if (\Yii::$app->user->can('projectAdmin')) {
					if (\Yii::$app->user->identity->regionId != $regionId && \Yii::$app->user->id != $creatorUserId) {
						return false;
					}
				}  elseif (\Yii::$app->user->can('refereeOfCompetitions')) {
					if (\Yii::$app->user->id != $creatorUserId) {
						return false;
					}
				}
			}
		}
		return true;
	}
	
	public static function fromRegion($regionTitle)
	{
		if (\Yii::$app->user->can('globalWorkWithCompetitions')) {
			return true;
		}
		$region = Region::find()->where(['upper("title")' => mb_strtoupper($regionTitle, 'UTF-8')])->one();
		if ($region && $region->id == \Yii::$app->user->identity->regionId) {
			return true;
		}
		return false;
	}
}