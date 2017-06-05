<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "news_block".
 *
 * @property integer      $id
 * @property integer      $newsId
 * @property string       $text
 * @property integer      $sliderText
 * @property integer      $sort
 *
 * @property News         $news
 * @property NewsSlider[] $newsSliders
 */
class NewsBlock extends \yii\db\ActiveRecord
{
    public $slider;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'NewsBlock';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['newsId'], 'required'],
            [['newsId', 'sort'], 'integer'],
            [['text', 'sliderText'], 'string'],
            [['newsId'], 'exist', 'skipOnError' => true, 'targetClass' => News::className(), 'targetAttribute' => ['newsId' => 'id']],
            [['slider'], 'file', 'extensions' => 'png, jpg', 'maxFiles' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'newsId'     => 'News ID',
            'text'       => 'Текст',
            'sliderText' => 'Текст для слайдера',
            'sort'       => 'Сортировка',
            'slider'     => 'Слайдер'
        ];
    }
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			if (!$this->sort) {
				$this->sort = self::find()->where(['newsId' => $this->newsId])->max('sort') + 1;
			}
		}
		
		return parent::beforeValidate();
	}
	
	
	/**
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasOne(News::className(), ['id' => 'newsId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewsSliders()
    {
        return $this->hasMany(NewsSlider::className(), ['blockId' => 'id', 'newsId' => 'newsId'])->orderBy(['sort' => SORT_ASC]);
    }
}
