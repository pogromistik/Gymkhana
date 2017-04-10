<?php

namespace common\models\search;

use common\models\Athlete;
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
			[['id', 'championshipId', 'stageId', 'motorcycleId', 'internalClassId', 'athleteClassId', 'bestTime', 'place', 'number', 'sort', 'dateAdded', 'status'], 'integer'],
			[['athleteId'], 'safe']
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
			'sort' => ['defaultOrder' => ['sort' => SORT_ASC, 'id' => SORT_ASC]]
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
		
		if ($this->athleteId) {
			$athleteIds = Athlete::find()->select('id')->where(['upper("firstName")' => mb_strtoupper($this->athleteId, 'UTF-8')])
				->orWhere(['upper("lastName")' => mb_strtoupper($this->athleteId, 'UTF-8')])->asArray()->column();
			if (!$athleteIds) {
				$query->andFilterWhere(['athleteId' => 0]);
			}
			$query->andFilterWhere(['athleteId' => $athleteIds]);
		}
		
		return $dataProvider;
	}
	
	public function beforeValidate()
	{
		return ActiveRecord::beforeValidate();
	}
}
