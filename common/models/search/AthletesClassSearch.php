<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AthletesClass;
use yii\db\ActiveRecord;

/**
 * AthletesClassSearch represents the model behind the search form about `common\models\AthletesClass`.
 */
class AthletesClassSearch extends AthletesClass
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'title', 'percent', 'sort'], 'integer'],
			[['description'], 'safe'],
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
		$query = AthletesClass::find();
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => ['defaultOrder' => ['sort' => SORT_ASC]]
		]);
		
		$this->load($params);
		
		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}
		
		// grid filtering conditions
		$query->andFilterWhere([
			'id'      => $this->id,
			'title'   => $this->title,
			'percent' => $this->percent,
			'sort'    => $this->sort,
		]);
		
		$query->andFilterWhere(['like', 'description', $this->description]);
		
		return $dataProvider;
	}
	
	public function beforeValidate()
	{
		return ActiveRecord::beforeValidate();
	}
}
