<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "about_slider".
 *
 * @property integer    $id
 * @property integer    $blockId
 * @property string     $picture
 * @property integer    $sort
 * @property AboutBlock $block
 */
class AboutSlider extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'about_slider';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['blockId', 'picture'], 'required'],
			[['blockId', 'sort'], 'integer'],
			[['picture'], 'string', 'max' => 255],
			[['blockId'], 'exist', 'skipOnError' => true, 'targetClass' => AboutBlock::className(), 'targetAttribute' => ['blockId' => 'id']],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'      => 'ID',
			'blockId' => 'Block ID',
			'picture' => 'Picture',
			'sort'    => 'Sort',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getBlock()
	{
		return $this->hasOne(AboutBlock::className(), ['id' => 'blockId']);
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			if (!$this->sort) {
				$this->sort = self::find()->max('sort') + 1;
			}
			
			var_dump($this->sort);
		}
		
		return parent::beforeValidate();
	}
}
