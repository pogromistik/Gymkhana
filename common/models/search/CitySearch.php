<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\City;
use yii\db\ActiveRecord;

/**
 * RussiaSearch represents the model behind the search form about `common\models\Russia`.
 */
class CitySearch extends City
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'link'], 'integer'],
			[['top', 'left'], 'number'],
			[['title', 'link'], 'string']
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
		$query = City::find();
		
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
			'id'   => $this->id,
			'link' => $this->link,
			'top'  => $this->top,
			'left' => $this->left,
		]);
		
		$query->andFilterWhere(['like', 'title', $this->title]);
		$query->andFilterWhere(['like', 'link', $this->link]);
		
		return $dataProvider;
	}
	
	public function beforeValidate()
	{
		return ActiveRecord::beforeValidate();
	}
}
