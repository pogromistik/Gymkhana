<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "news_slider".
 *
 * @property integer   $id
 * @property integer   $newsId
 * @property integer   $blockId
 * @property string    $picture
 * @property integer   $sort
 *
 * @property NewsBlock $block
 * @property News      $news
 */
class NewsSlider extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news_slider';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['newsId', 'blockId', 'picture'], 'required'],
            [['newsId', 'blockId', 'sort'], 'integer'],
            [['picture'], 'string', 'max' => 255],
            [['blockId'], 'exist', 'skipOnError' => true, 'targetClass' => NewsBlock::className(), 'targetAttribute' => ['blockId' => 'id']],
            [['newsId'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['newsId' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'      => 'ID',
            'newsId'  => 'News ID',
            'blockId' => 'Block ID',
            'picture' => 'Picture',
            'sort'    => 'Сортировка'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlock()
    {
        return $this->hasOne(NewsBlock::className(), ['id' => 'blockId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasOne(News::className(), ['id' => 'newsId']);
    }
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			if (!$this->sort) {
				$this->sort = self::find()->max('sort') + 1;
			}
		}
		
		return parent::beforeValidate();
	}
}
