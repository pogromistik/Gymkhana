<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Participant;
use yii\db\ActiveRecord;

/**
 * ParticipantSearch represents the model behind the search form about `common\models\Participant`.
 */
class ParticipantSearch extends Participant
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'championshipId', 'stageId', 'athleteId', 'motorcycleId', 'internalClassId', 'athleteClassId', 'bestTime', 'place', 'number', 'sort', 'dateAdded', 'status'], 'integer'],
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
		$query = Participant::find();
		
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
			'id'              => $this->id,
			'championshipId'  => $this->championshipId,
			'stageId'         => $this->stageId,
			'athleteId'       => $this->athleteId,
			'motorcycleId'    => $this->motorcycleId,
			'internalClassId' => $this->internalClassId,
			'athleteClassId'  => $this->athleteClassId,
			'bestTime'        => $this->bestTime,
			'place'           => $this->place,
			'number'          => $this->number,
			'sort'            => $this->sort,
			'dateAdded'       => $this->dateAdded,
			'status'          => $this->status,
		]);
		
		return $dataProvider;
	}
	
	public function beforeValidate()
	{
		return ActiveRecord::beforeValidate();
	}
}
