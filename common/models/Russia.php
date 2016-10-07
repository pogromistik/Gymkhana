<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "russia".
 *
 * @property integer $id
 * @property integer $title
 * @property integer $link
 * @property double  $top
 * @property double  $left
 */
class Russia extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'russia';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title'], 'required'],
			[['title', 'link'], 'integer'],
			[['top', 'left'], 'number'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'    => 'ID',
			'title' => 'Город',
			'link'  => 'Ссыдка',
			'top'   => 'Top',
			'left'  => 'Left',
		];
	}
}
