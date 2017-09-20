<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MoscowPoint;

/**
 * MoscowPointsSearch represents the model behind the search form about `common\models\MoscowPoint`.
 */
class MoscowPointsSearch extends MoscowPoint
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'class', 'place', 'point'], 'integer'],
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
		$query = MoscowPoint::find();
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort'  => ['defaultOrder' => ['class' => SORT_ASC, 'place' => SORT_ASC]]
		]);
		
		$this->load($params);
		
		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}
		
		// grid filtering conditions
		$query->andFilterWhere([
			'id'    => $this->id,
			'class' => $this->class,
			'place' => $this->place,
			'point' => $this->point,
		]);
		
		return $dataProvider;
	}
}
