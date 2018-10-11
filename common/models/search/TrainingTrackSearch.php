<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TrainingTrack;
use yii\db\ActiveRecord;

/**
 * TrainingTrackSearch represents the model behind the search form of `common\models\TrainingTrack`.
 */
class TrainingTrackSearch extends TrainingTrack
{
	public $widthRange;
	public $heightRange;
	public $conesRange;
	
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'level', 'conesCount', 'dateAdded', 'dateUpdated', 'creatorUserId'], 'integer'],
			[['title', 'description', 'imgPath', 'status'], 'safe'],
			[['minWidth', 'minHeight'], 'number'],
			[['widthRange', 'heightRange', 'conesRange'], 'safe']
		];
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}
	
	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = TrainingTrack::find();
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query'      => $query,
			'sort'       => [
				'defaultOrder' => ['dateAdded' => SORT_DESC]
			],
			'pagination' => [
				'pageSize' => 36,
			],
		]);
		
		$this->load($params);
		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}
		
		// grid filtering conditions
		$query->andFilterWhere([
			'id'            => $this->id,
			'minWidth'      => $this->minWidth,
			'minHeight'     => $this->minHeight,
			'level'         => $this->level,
			'conesCount'    => $this->conesCount,
			'dateAdded'     => $this->dateAdded,
			'dateUpdated'   => $this->dateUpdated,
			'creatorUserId' => $this->creatorUserId,
			'status'        => $this->status,
		]);
		
		$query->andFilterWhere(['ilike', 'title', $this->title])
			->andFilterWhere(['ilike', 'description', $this->description])
			->andFilterWhere(['ilike', 'imgPath', $this->imgPath]);
		
		/*if ($this->widthRange) {
			$this->addDimensionsRangeCondition($this->widthRange, $query, 'minWidth');
		}
		if ($this->heightRange) {
			$this->addDimensionsRangeCondition($this->heightRange, $query, 'minHeight');
		}*/
		self::addDimensionsRangeCondition($query, $this->widthRange, $this->heightRange);
		
		if ($this->conesRange) {
			self::addRangeCondition('conesCount', $this->conesRange, $query);
		}
		
		return $dataProvider;
	}
	
	public static function addDimensionsRangeCondition(&$query, $widthRange, $heightRange)
	{
		if ($widthRange && $heightRange) {
			$widthRange = explode(';', $widthRange);
			$minWidth = (float)min($widthRange);
			$maxWidth = (float)max($widthRange);
			
			$heightRange = explode(';', $heightRange);
			$minHeight = (float)min($heightRange);
			$maxHeight = (float)max($heightRange);
			
			$query->andWhere(['or',
				['and', ['>=', 'minWidth', $minWidth], ['<=', 'minWidth', $maxWidth],
					['>=', 'minHeight', $minHeight], ['<=', 'minHeight', $maxHeight]],
				['and', ['>=', 'minWidth', $minHeight], ['<=', 'minWidth', $maxHeight],
					['>=', 'minHeight', $minWidth], ['<=', 'minHeight', $maxWidth]],
			]);
		} elseif ($widthRange) {
			$widthRange = explode(';', $widthRange);
			$minWidth = (float)min($widthRange);
			$maxWidth = (float)max($widthRange);
			
			$query->andWhere(['or',
				['and', ['>=', 'minWidth', $minWidth], ['<=', 'minWidth', $maxWidth]],
				['and', ['>=', 'minHeight', $minWidth], ['<=', 'minHeight', $maxWidth]],
			]);
		} elseif ($heightRange) {
			$heightRange = explode(';', $heightRange);
			$minHeight = (float)min($heightRange);
			$maxHeight = (float)max($heightRange);
			
			$query->andWhere(['or',
				['and', ['>=', 'minHeight', $minHeight], ['<=', 'minHeight', $maxHeight]],
				['and', ['>=', 'minWidth', $minHeight], ['<=', 'minWidth', $maxHeight]]
			]);
		}
	}
	
	/*private function addDimensionsRangeCondition($range, &$query, $attr)
	{
		$range = explode(';', $range);
		$min = (float)min($range);
		$max = (float)max($range);
		if (!$min && !$max) {
			return;
		}
		if ($min === $max) {
			$query->andWhere(['or', ['minWidth' => $min], ['minHeight' => $min]]);
		} else {
			if ($min && $max) {
				$query->andWhere([
					'and',
					['>=', $attr, $min],
					['<=', $attr, $max]
				]);
			} elseif ($min) {
				$query->andWhere(['>=', $attr, $min]);
			} elseif ($max) {
				$query->andWhere(['<=', $attr, $max]);
			}
		}
	}*/
	
	public static function addRangeCondition($attr, $range, &$query)
	{
		$range = explode(';', $range);
		$min = (float)min($range);
		$max = (float)max($range);
		if (!$min && !$max) {
			return;
		}
		if ($min === $max) {
			$query->andWhere([$attr => $min]);
		} else {
			if ($min) {
				$query->andWhere(['>=', $attr, $min]);
			}
			if ($max) {
				$query->andWhere(['<=', $attr, $max]);
			}
		}
	}
	
	public function beforeValidate()
	{
		return ActiveRecord::beforeValidate();
	}
}
