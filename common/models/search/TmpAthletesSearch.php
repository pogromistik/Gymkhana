<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TmpAthlete;
use yii\db\ActiveRecord;

/**
 * TmpAthletesSearch represents the model behind the search form about `common\models\TmpAthlete`.
 */
class TmpAthletesSearch extends TmpAthlete
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'athleteId', 'countryId', 'cityId', 'status', 'dateAdded', 'dateUpdated'], 'integer'],
			[['firstName', 'lastName', 'phone', 'email', 'city', 'motorcycles'], 'safe'],
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
		$query = TmpAthlete::find();
		
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
			'id'          => $this->id,
			'athleteId'   => $this->athleteId,
			'countryId'   => $this->countryId,
			'cityId'      => $this->cityId,
			'status'      => $this->status,
			'dateAdded'   => $this->dateAdded,
			'dateUpdated' => $this->dateUpdated,
		]);
		
		$query->andFilterWhere(['like', 'firstName', $this->firstName])
			->andFilterWhere(['like', 'lastName', $this->lastName])
			->andFilterWhere(['like', 'phone', $this->phone])
			->andFilterWhere(['like', 'email', $this->email])
			->andFilterWhere(['like', 'city', $this->city])
			->andFilterWhere(['like', 'motorcycles', $this->motorcycles]);
		
		return $dataProvider;
	}
	
	public function beforeValidate()
	{
		return ActiveRecord::beforeValidate();
	}
}
