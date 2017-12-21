<?php

namespace common\helpers;

class TranslateHelper
{
	public static function translate($message)
	{
		return \Yii::t('app', $message);
	}
	
	public static function translateArray($array)
	{
		$result = [];
		foreach ($array as $index => $message) {
			$result[$index] = self::translate($message);
		}
		
		return $result;
	}
}