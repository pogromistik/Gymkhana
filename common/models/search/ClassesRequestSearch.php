<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ClassesRequest;
use yii\db\ActiveRecord;

/**
 * ClassesRequestSearch represents the model behind the search form about `common\models\ClassesRequest`.
 */
class ClassesRequestSearch extends ClassesRequest
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'dateAdded', 'status', 'athleteId', 'newClassId'], 'integer'],
			[['comment', 'feedback'], 'safe'],
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
		$query = ClassesRequest::find();
		
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
			'id'         => $this->id,
			'dateAdded'  => $this->dateAdded,
			'status'     => $this->status,
			'athleteId'  => $this->athleteId,
			'newClassId' => $this->newClassId,
		]);
		
		$query->andFilterWhere(['like', 'comment', $this->comment])
			->andFilterWhere(['like', 'feedback', $this->feedback]);
		
		return $dataProvider;
	}
	
	public function beforeValidate()
	{
		return ActiveRecord::beforeValidate();
	}
}
