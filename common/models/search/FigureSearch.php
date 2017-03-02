<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Figure;

/**
 * FigureSearch represents the model behind the search form about `common\models\Figure`.
 */
class FigureSearch extends Figure
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'bestTime', 'bestTimeInRussia'], 'integer'],
			[['title', 'description', 'file', 'picture', 'bestAthlete', 'bestAthleteInRussia'], 'safe'],
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
		$query = Figure::find();
		
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
			'id'               => $this->id,
			'bestTime'         => $this->bestTime,
			'bestTimeInRussia' => $this->bestTimeInRussia,
		]);
		
		$query->andFilterWhere(['like', 'title', $this->title])
			->andFilterWhere(['like', 'description', $this->description])
			->andFilterWhere(['like', 'file', $this->file])
			->andFilterWhere(['like', 'picture', $this->picture])
			->andFilterWhere(['like', 'bestAthlete', $this->bestAthlete])
			->andFilterWhere(['like', 'bestAthleteInRussia', $this->bestAthleteInRussia]);
		
		return $dataProvider;
	}
}
