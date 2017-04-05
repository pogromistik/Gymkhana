<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Stage;
use yii\db\ActiveRecord;

/**
 * StageSearch represents the model behind the search form about `common\models\Stage`.
 */
class StageSearch extends Stage
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'championshipId', 'cityId', 'dateAdded', 'dateUpdated', 'dateOfThe', 'startRegistration', 'endRegistration', 'status'], 'integer'],
			[['title', 'location', 'description', 'class'], 'safe'],
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
		$query = Stage::find();
		
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
			'id'                => $this->id,
			'championshipId'    => $this->championshipId,
			'cityId'            => $this->cityId,
			'dateAdded'         => $this->dateAdded,
			'dateUpdated'       => $this->dateUpdated,
			'dateOfThe'         => $this->dateOfThe,
			'startRegistration' => $this->startRegistration,
			'endRegistration'   => $this->endRegistration,
			'status'            => $this->status,
		]);
		
		$query->andFilterWhere(['like', 'title', $this->title])
			->andFilterWhere(['like', 'location', $this->location])
			->andFilterWhere(['like', 'description', $this->description])
			->andFilterWhere(['like', 'class', $this->class]);
		
		return $dataProvider;
	}
	
	public function beforeValidate()
	{
		return ActiveRecord::beforeValidate();
	}
}
