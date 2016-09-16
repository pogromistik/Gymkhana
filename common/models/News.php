<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "news".
 *
 * @property integer     $id
 * @property string      $title
 * @property integer     $dateCreated
 * @property integer     $datePublish
 * @property integer     $dateUpdated
 * @property string      $previewText
 * @property string      $previewImage
 * @property integer     $isPublish
 *
 * @property NewsBlock[] $newsBlock
 */
class News extends \yii\db\ActiveRecord
{
    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'dateCreated', 'datePublish', 'dateUpdated', 'isPublish'], 'required'],
            [['dateCreated', 'datePublish', 'dateUpdated', 'isPublish'], 'integer'],
            [['previewText'], 'string'],
            [['title', 'previewImage'], 'string', 'max' => 255],
            [['file'], 'file', 'extensions' => 'png, jpg'],
            [['isPublish'], 'default', 'value' => 1]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'title'        => 'Название',
            'dateCreated'  => 'Дата создания',
            'datePublish'  => 'Дата публикации',
            'dateUpdated'  => 'Дата обновления',
            'previewText'  => 'Preview Text',
            'previewImage' => 'Preview Image',
            'isPublish'    => 'Опубликовать',
            'file'         => 'Preview image'
        ];
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->dateCreated = time();
            if (!$this->datePublish) {
                $this->datePublish = time();
            }
        }
        $this->dateUpdated = time();

        return parent::beforeValidate();
    }

    public function getNewsBlock()
    {
        $this->hasMany(NewsBlock::tableName(), ['newsId' => 'id']);
    }
}
