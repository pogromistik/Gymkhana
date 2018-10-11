<?php

namespace common\models;

use common\components\BaseActiveRecord;
use common\components\Resize;
use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "TrainingTracks".
 *
 * @property int     $id
 * @property string  $title
 * @property string  $description
 * @property string  $imgPath
 * @property int     $status
 * @property double  $minWidth
 * @property double  $minHeight
 * @property int     $level
 * @property int     $conesCount
 * @property int     $dateAdded
 * @property int     $dateUpdated
 * @property int     $creatorUserId
 *
 * @property Comment $comments
 */
class TrainingTrack extends BaseActiveRecord
{
    protected static $enableLogging = true;

    public $imgFile;

    const LEVEL_EASY = 1;
    const LEVEL_NORMAL = 2;
    const LEVEL_HARD = 3;
    public static $levelTitles = [
        self::LEVEL_EASY   => 'Легко',
        self::LEVEL_NORMAL => 'Средне',
        self::LEVEL_HARD   => 'Сложно'
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_MODERATION = 2;
    public static $statusTitles = [
        self::STATUS_ACTIVE     => 'Активна',
        self::STATUS_MODERATION => 'На модерации'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'TrainingTracks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'string'],
            [['dateAdded', 'dateUpdated'], 'required'],
            [['minWidth', 'minHeight'], 'number'],
            [['level', 'conesCount', 'dateAdded', 'dateUpdated', 'creatorUserId'], 'default', 'value' => null],
            [['level', 'conesCount', 'dateAdded', 'dateUpdated', 'creatorUserId'], 'integer'],
            [['title', 'imgPath'], 'string', 'max' => 255],
            [
                ['level', 'conesCount', 'minWidth', 'minHeight'],
                'required',
                'when'       => function ($model) {
                    return \Yii::$app->id === 'app-admin';
                },
                'whenClient' => "function (attribute, value) {
        return " . (\Yii::$app->id == 'app-admin') . ";
    }"
            ],
            [
                'imgFile',
                'file',
                'extensions' => 'jpg, jpeg, png, gif',
                'mimeTypes'  => 'image/jpeg, image/png',
                'maxFiles'   => 1,
                'maxSize'    => 2097152,
                'tooBig'     => \Yii::t('app', 'Размер файла не должен превышать 2МБ')
            ],
            [['status'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'title'         => \Yii::t('app', 'Название'),
            'description'   => \Yii::t('app', 'Описание'),
            'imgPath'       => \Yii::t('app', 'Фото'),
            'imgFile'       => \Yii::t('app', 'Фото'),
            'status'        => 'Статус',
            'minWidth'      => \Yii::t('app', 'Длина площадки, м'),
            'minHeight'     => \Yii::t('app', 'Ширина площадки, м'),
            'level'         => \Yii::t('app', 'Уровень сложности'),
            'conesCount'    => \Yii::t('app', 'Количество конусов'),
            'dateAdded'     => 'Date Added',
            'dateUpdated'   => 'Date Updated',
            'creatorUserId' => 'Creator User ID',
        ];
    }

    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->dateAdded = time();
            if (\Yii::$app->user && \Yii::$app->id !== 'app-admin') {
                $this->creatorUserId = \Yii::$app->user->id;
            }
        }
        $this->dateUpdated = time();

        return parent::beforeValidate();
    }

    public function afterSave($insert, $changedAttributes)
    {
        if (!$this->title) {
            $this->title = 'Track №' . $this->id;
            $this->save(false);
        }
        parent::afterSave($insert, $changedAttributes);
    }

    public function beforeSave($insert)
    {
        $file = UploadedFile::getInstance($this, 'imgFile');
        $oldFile = $this->imgPath;
        if ($file && $file->size <= 2097152) {
            $path = 'training-tracks';
            $dir = \Yii::getAlias('@files') . '/' . $path;
            HelpModel::createFolders($path);
            $title = uniqid() . '.' . $file->extension;
            $folder = $dir . '/' . $title;
            if ($file->saveAs($folder)) {
                Resize::resizeWithProportions($folder, 1200);
                $this->imgPath = $path . '/' . $title;
                if ($this->imgPath) {
                    HelpModel::deleteFile($oldFile);
                }
            }
        }

        return parent::beforeSave($insert);
    }

    public function getComments()
    {
        return $this->hasMany(Comment::class, ['modelId' => 'id'])
            ->andOnCondition(['modelClass' => self::class])->orderBy(['dateAdded' => SORT_DESC]);
    }
}
