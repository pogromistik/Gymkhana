<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\FigureTime;
use yii\db\ActiveRecord;

/**
 * FigureTimeSearch represents the model behind the search form about `common\models\FigureTime`.
 */
class FigureTimeSearch extends FigureTime
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'figureId', 'athleteId', 'motorcycleId', 'yearId', 'athleteClassId', 'newAthleteClassId', 'newAthleteClassStatus', 'date', 'time', 'fine', 'dateAdded', 'dateUpdated', 'resultTime'], 'integer'],
			[['percent'], 'number'],
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
		$query = FigureTime::find();
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => ['defaultOrder' => ['resultTime' => SORT_ASC, 'dateAdded' => SORT_DESC]]
		]);
		
		$this->load($params);
		
		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}
		
		// grid filtering conditions
		$query->andFilterWhere([
			'id'                    => $this->id,
			'figureId'              => $this->figureId,
			'athleteId'             => $this->athleteId,
			'motorcycleId'          => $this->motorcycleId,
			'yearId'                => $this->yearId,
			'athleteClassId'        => $this->athleteClassId,
			'newAthleteClassId'     => $this->newAthleteClassId,
			'newAthleteClassStatus' => $this->newAthleteClassStatus,
			'date'                  => $this->date,
			'percent'               => $this->percent,
			'time'                  => $this->time,
			'fine'                  => $this->fine,
			'dateAdded'             => $this->dateAdded,
			'dateUpdated'           => $this->dateUpdated,
			'resultTime'            => $this->resultTime,
		]);
		
		return $dataProvider;
	}
	
	public function beforeValidate()
	{
		return ActiveRecord::beforeValidate();
	}
}
