<?php

namespace common\components;


use common\helpers\UserHelper;
use common\models\ChangesLog;
use common\models\HelpModel;
use yii\console\Application;
use yii\db\ActiveRecord;

/**
 * Class BaseActiveRecord
 * @package common\components
 *
 * @property string publicUrl
 */
abstract class BaseActiveRecord extends ActiveRecord
{
	protected static $enableLogging = false;
	
	protected static $ignoredAttributes = [
		'dateAdded',
		'dateUpdated'
	];
	
	
	/**
	 * @param array $q
	 * @return static
	 */
	public static function findOrCreate(array $q)
	{
		$query = static::find()->where($q);
		if (!$model = $query->one()) {
			$class = static::className();
			$model = new $class($q);
		}
		
		return $model;
	}
	
	public function afterSave($insert, $changedAttributes)
	{
		$userId = null;
		
		$userId = UserHelper::getUserId(\Yii::$app instanceof Application);
		if (static::$enableLogging && $userId) {
			$this->saveChanges($insert, $changedAttributes, $userId);
		}
		parent::afterSave($insert, $changedAttributes);
	}
	
	public function addLog($action, array $changes = [], $comment = '', $userId = null)
	{
		$userId = UserHelper::getUserId();
		if ($userId) {
			$changeLog = new ChangesLog();
			$changeLog->modelClass = static::class;
			$changeLog->modelId = (string)$this->primaryKey;
			$changeLog->userId = $userId;
			$changeLog->date = time();
			$changeLog->action = $action;
			if ($comment) {
				$changeLog->comment = $comment;
			}
			if ($changes) {
				$changeLog->changes = json_encode($changes);
			}
			if ($changeLog->validate()) {
				$changeLog->save(false);
			} else {
				var_dump($changeLog->errors);
			}
		}
		return true;
	}
	
	protected function saveChanges($insert, $changedAttributes, $userId, $comment = false)
	{
		$changeLog = new ChangesLog();
		$changeLog->modelClass = static::class;
		$changeLog->modelId = (string)$this->primaryKey;
		$changeLog->userId = $userId;
		$changeLog->date = time();
		$changeLog->action = $insert ? ChangesLog::ACTION_INSERT : ChangesLog::ACTION_EDIT;
		if ($comment) {
			$changeLog->comment = $comment;
		}
		
		if (!$insert) {
			$changes = [];
			foreach ($changedAttributes as $k => $v) {
				$new = $this->$k;
				$byType = false;
				if ($new == $v && $new !== $v) {
					$byType = true;
				}
				if (!in_array($k, static::$ignoredAttributes) && !$byType) {
					$changes[$k] = [
						'old' => (string)$v,
						'new' => (string)$this->$k
					];
				}
			}
			if (!$changes) {
				return true;
			}
			$changeLog->changes = json_encode($changes);
		}
		if ($changeLog->validate()) {
			$changeLog->save(false);
		} else {
			var_dump($changeLog->errors);
		}
		return true;
	}
	
	/**
	 * @return ChangesLog[]
	 */
	public function getChanges()
	{
		return ChangesLog::find()->where([
			'modelClass' => static::class,
			'modelId'    => (string)$this->primaryKey
		])->orderBy(['date' => SORT_ASC])->all();
	}
	
	/***
	 * @param string $attribute
	 * @param string $value
	 * @return mixed
	 */
	public function getAttributeDisplayValue($attribute, $value)
	{
		if ($this->hasAttribute($attribute)) {
			if (mb_strpos($attribute, 'time') !== false || mb_strpos($attribute, 'Time') !== false
			|| mb_strpos($attribute, 'recordInMoment') !== false) {
				return HelpModel::convertTimeToHuman($value);
			}
			if (mb_strpos($attribute, 'date') !== false || mb_strpos($attribute, 'Date') !== false
			|| mb_strpos($attribute, 'startRegistration') !== false || mb_strpos($attribute, 'endRegistration') !== false) {
				return date('d.m.Y, H:i', $value);
			}
			return $value;
		}
		return $value;
	}
	
	public function getPublicUrl()
	{
		return false;
	}
}