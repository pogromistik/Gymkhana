<?php

namespace common\models;

use Yii;
use yii\helpers\Inflector;

/**
 * This is the model class for table "pages".
 *
 * @property integer      $id
 * @property integer      $dateAdded
 * @property integer      $dateUpdated
 * @property integer      $parentId
 * @property string       $url
 * @property string       $title
 * @property string       $keywords
 * @property string       $description
 * @property integer      $status
 * @property integer      $showInMenu
 * @property integer      $sort
 * @property string       $layoutId
 *
 * @property Layout       $layout
 * @property Page         $parent
 * @property Page[]       $children
 */
class Page extends \yii\db\ActiveRecord
{
	const STATUS_ACTIVE = 1;
	const STATUS_INACTIVE = 0;

	const SHOW_IN_MENU_YES = 1;
	const SHOW_IN_MENU_NO = 0;

	public static $statusesTitle = [
		self::STATUS_ACTIVE   => 'Активна',
		self::STATUS_INACTIVE => 'Неактивна'
	];

	public static $showTitles = [
		self::SHOW_IN_MENU_NO  => 'нет',
		self::SHOW_IN_MENU_YES => 'да'
	];

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'pages';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['dateAdded', 'dateUpdated', 'title'], 'required'],
			[['dateAdded', 'dateUpdated', 'parentId', 'status', 'showInMenu', 'sort'], 'integer'],
			[['keywords', 'description', 'text'], 'string'],
			[['url', 'title', 'layoutId'], 'string', 'max' => 255],
			['status', 'default', 'value' => 1],
			['showInMenu', 'default', 'value' => 0],
			['url', 'unique'],
			[['layoutId'], 'exist', 'skipOnError' => true, 'targetClass' => Layout::className(), 'targetAttribute' => ['layoutId' => 'id']],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'          => 'ID',
			'dateAdded'   => 'Дата создания',
			'dateUpdated' => 'Дата редактирования',
			'parentId'    => 'Родитель',
			'url'         => 'Url',
			'title'       => 'Заголовок',
			'keywords'    => 'Ключевые слова',
			'description' => 'Описание',
			'status'      => 'Статус',
			'showInMenu'  => 'Показывать в меню',
			'sort'        => 'Сортировка',
			'text'        => 'Текст',
			'layoutId'    => 'Шаблон',
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getLayout()
	{
		return $this->hasOne(Layout::className(), ['id' => 'layoutId']);
	}
	
	public function beforeValidate()
	{
		if ($this->isNewRecord) {
			$this->dateAdded = time();
			if (!$this->url) {
				$this->url = $this->generateUrl($this->title);
			}
			if (!$this->sort) {
				if ($this->parentId) {
					$this->sort = self::find()->where(['parentId' => $this->parentId])->max('sort') + 1;
				} else {
					$this->sort = self::find()->where(['parentId' => null])->max('sort') + 1;
				}
			}
		}
		$this->dateUpdated = time();

		return parent::beforeValidate();
	}

	public function getParent()
	{
		if (!$this->parentId) {
			return null;
		}

		return $this->hasOne(Page::className(), ['id' => 'parentId']);
	}

	private function generateUrl($url)
	{
		$url = $this->translit($url);
		if ($parent = $this->parent) {
			$url = $parent->url . '/' . $url;
		}
		if ($this->checkUniqueUrl($url)) {
			return $url;
		} else {
			for ($suffix = 2; !$this->checkUniqueUrl($new_url = $url . '-' . $suffix); $suffix++) {
			}

			return $new_url;
		}
	}

	private function translit($url)
	{
		return Inflector::slug($this->str2url($url), '-', true);
	}

	public function rus2translit($string) {
		$converter = array(
			'а' => 'a',   'б' => 'b',   'в' => 'v',
			'г' => 'g',   'д' => 'd',   'е' => 'e',
			'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
			'и' => 'i',   'й' => 'y',   'к' => 'k',
			'л' => 'l',   'м' => 'm',   'н' => 'n',
			'о' => 'o',   'п' => 'p',   'р' => 'r',
			'с' => 's',   'т' => 't',   'у' => 'u',
			'ф' => 'f',   'х' => 'h',   'ц' => 'c',
			'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
			'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
			'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

			'А' => 'A',   'Б' => 'B',   'В' => 'V',
			'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
			'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
			'И' => 'I',   'Й' => 'Y',   'К' => 'K',
			'Л' => 'L',   'М' => 'M',   'Н' => 'N',
			'О' => 'O',   'П' => 'P',   'Р' => 'R',
			'С' => 'S',   'Т' => 'T',   'У' => 'U',
			'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
			'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
			'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
			'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
		);
		return strtr($string, $converter);
	}
	public function str2url($str) {
		// переводим в транслит
		$str = $this->rus2translit($str);
		// в нижний регистр
		$str = strtolower($str);
		$str = trim($str, "-");
		return $str;
	}

	private function checkUniqueUrl($url)
	{
		return !self::findOne(['url' => $url]);
	}

	public static function getParents($id = null)
	{
		$result = self::find();
		if ($id) {
			$result = $result->andWhere(['not', ['id' => $id]]);
		}

		return $result->all();
	}

	public function getChildren()
	{
		return $this->hasMany(Page::className(), ['parentId' => 'id']);
	}
}
