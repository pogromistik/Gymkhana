<?php

namespace common\helpers;

class FormatHelper
{
	public static function replace($array)
	{
		$result = [];
		foreach ($array as $key => $item) {
			$result[$key] = \Yii::t('app', $item);
		}
	}
}