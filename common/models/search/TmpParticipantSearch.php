<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TmpParticipant;
use yii\db\ActiveRecord;

/**
 * TmpParticipantSearch represents the model behind the search form about `common\models\TmpParticipant`.
 */
class TmpParticipantSearch extends TmpParticipant
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'championshipId', 'stageId', 'cityId', 'number', 'dateAdded', 'dateUpdated', 'status', 'athleteId'], 'integer'],
			[['firstName', 'lastName', 'city', 'motorcycleMark', 'motorcycleModel', 'phone'], 'safe'],
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
		$query = TmpParticipant::find();
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);
		
		$this->load($params);
		
		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}
		
		// grid filtering conditions
		$query->andFilterWhere([
			'id'             => $this->id,
			'championshipId' => $this->championshipId,
			'stageId'        => $this->stageId,
			'cityId'         => $this->cityId,
			'number'         => $this->number,
			'dateAdded'      => $this->dateAdded,
			'dateUpdated'    => $this->dateUpdated,
			'status'         => $this->status,
			'athleteId'      => $this->athleteId,
		]);
		
		$query->andFilterWhere(['like', 'firstName', $this->firstName])
			->andFilterWhere(['like', 'lastName', $this->lastName])
			->andFilterWhere(['like', 'city', $this->city])
			->andFilterWhere(['like', 'motorcycleMark', $this->motorcycleMark])
			->andFilterWhere(['like', 'motorcycleModel', $this->motorcycleModel])
			->andFilterWhere(['like', 'phone', $this->phone]);
		
		return $dataProvider;
	}
	
	public function beforeValidate()
	{
		return ActiveRecord::beforeValidate();
	}
}
