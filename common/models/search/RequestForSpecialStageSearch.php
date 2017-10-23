<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\RequestForSpecialStage;
use yii\db\ActiveRecord;

/**
 * RequestForSpecialStageSearch represents the model behind the search form about
 * `common\models\RequestForSpecialStage`.
 */
class RequestForSpecialStageSearch extends RequestForSpecialStage
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'athleteId', 'motorcycleId', 'status', 'time', 'fine', 'resultTime', 'athleteClassId', 'newAthleteClassId', 'newAthleteClassStatus', 'percent', 'stageId', 'date', 'dateAdded', 'dateUpdated'], 'integer'],
			[['data', 'videoLink', 'cancelReason'], 'safe'],
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
		$query = RequestForSpecialStage::find();
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort'  => ['defaultOrder' => ['status' => SORT_ASC, 'dateAdded' => SORT_DESC]]]);
		
		$this->load($params);
		
		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}
		
		// grid filtering conditions
		$query->andFilterWhere([
			'id'                    => $this->id,
			'athleteId'             => $this->athleteId,
			'motorcycleId'          => $this->motorcycleId,
			'status'                => $this->status,
			'time'                  => $this->time,
			'fine'                  => $this->fine,
			'resultTime'            => $this->resultTime,
			'athleteClassId'        => $this->athleteClassId,
			'newAthleteClassId'     => $this->newAthleteClassId,
			'newAthleteClassStatus' => $this->newAthleteClassStatus,
			'percent'               => $this->percent,
			'stageId'               => $this->stageId,
			'date'                  => $this->date,
			'dateAdded'             => $this->dateAdded,
			'dateUpdated'           => $this->dateUpdated,
		]);
		
		$query->andFilterWhere(['like', 'data', $this->data])
			->andFilterWhere(['like', 'videoLink', $this->videoLink])
			->andFilterWhere(['like', 'cancelReason', $this->cancelReason]);
		
		return $dataProvider;
	}
	
	public function beforeValidate()
	{
		return ActiveRecord::beforeValidate();
	}
}
