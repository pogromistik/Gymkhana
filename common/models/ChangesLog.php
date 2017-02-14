<?php

namespace common\models;

use common\components\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "ChangesLog".
 *
 * @property integer $id
 * @property integer $userId
 * @property string  $modelClass
 * @property string  $modelId
 * @property integer $action
 * @property string  $changes
 * @property string  $comment
 * @property string  $date
 *
 * @property User    $user
 */
class ChangesLog extends BaseActiveRecord
{
	
	const ACTION_INSERT = 1;
	const ACTION_EDIT = 2;
	const ACTION_DELETE = 3;
	const ACTION_PRINT = 3;
	
	public static $actionTitles = [
		self::ACTION_INSERT => 'Добавление',
		self::ACTION_EDIT   => 'Изменение',
		self::ACTION_DELETE => 'Удаление',
		self::ACTION_PRINT  => 'Печать'
	];
	
	
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'ChangesLog';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['userId', 'modelClass', 'modelId', 'action', 'date'], 'required'],
			[['userId', 'action', 'date'], 'integer'],
			[['changes'], 'string'],
			[['modelClass', 'modelId', 'comment'], 'string', 'max' => 255]
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'         => 'ID',
			'userId'     => 'User ID',
			'modelClass' => 'Model Class',
			'modelId'    => 'Model ID',
			'action'     => 'Action',
			'changes'    => 'Changes',
			'comment'    => 'Comment',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'userId']);
	}
	
	public function getChanges()
	{
		if ($this->changes) {
			return json_decode($this->changes, true);
		}
		return [];
	}
	
	/**
	 * @param      $userId
	 * @param      $changes
	 * @param      $dateStart
	 * @param null $dateEnd
	 * @param bool $distinct
	 * @return \yii\db\ActiveQuery
	 */
	public static function getByChanges($userId, $changes, $dateStart = null, $dateEnd = null, $distinct = true)
	{
		$query = self::find()->where(['@>', 'changes', json_encode($changes)]);
		$query->andWhere(['userId' => $userId]);
		if ($distinct) {
			$query->select('modelId')->distinct();
		}
		if ($dateStart) {
			$query->andWhere(['>=', 'date', $dateStart]);
			if (!$dateEnd) {
				$dateEnd = $dateStart + 86400;
			}
			$query->andWhere(['<', 'date', $dateEnd]);
		}
		return $query;
	}
}
