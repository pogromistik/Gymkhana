<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TmpFigureResult;
use yii\db\ActiveRecord;

/**
 * TmpFigureResultSearch represents the model behind the search form about `common\models\TmpFigureResult`.
 */
class TmpFigureResultSearch extends TmpFigureResult
{
	public $status;
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'athleteId', 'motorcycleId', 'figureId', 'date', 'time', 'fine', 'isNew', 'dateAdded', 'dateUpdated', 'status'], 'integer'],
			[['videoLink', 'figureResultId', 'cancelReason'], 'safe'],
		];
	}
	
	/**
	 * @inheritdoc
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
		$query = TmpFigureResult::find();
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => ['defaultOrder' => ['dateAdded' => SORT_ASC]]
		]);
		
		$this->load($params);
		
		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}
		
		// grid filtering conditions
		$query->andFilterWhere([
			'id'           => $this->id,
			'athleteId'    => $this->athleteId,
			'motorcycleId' => $this->motorcycleId,
			'figureId'     => $this->figureId,
			'date'         => $this->date,
			'time'         => $this->time,
			'fine'         => $this->fine,
			'dateAdded'    => $this->dateAdded,
			'dateUpdated'  => $this->dateUpdated,
		]);
		
		if ($this->status) {
			switch ($this->status) {
				case self::STATUS_NEW:
					$query->andFilterWhere(['isNew' => 1]);
					break;
				case self::STATUS_APPROVE:
					$query->andFilterWhere(['isNew' => 0]);
					$query->andWhere(['not', ['figureResultId' => null]]);
					break;
				case self::STATUS_CANCEL:
					$query->andFilterWhere(['isNew' => 0]);
					$query->andWhere(['not', ['cancelReason' => null]]);
					break;
			}
		}
		
		$query->andFilterWhere(['like', 'videoLink', $this->videoLink]);
		
		return $dataProvider;
	}
	
	public function beforeValidate()
	{
		return ActiveRecord::beforeValidate();
	}
}
