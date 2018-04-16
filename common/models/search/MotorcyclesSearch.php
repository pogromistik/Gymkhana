<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Motorcycle;
use yii\db\ActiveRecord;

/**
 * MotorcyclesSearch represents the model behind the search form about `common\models\Motorcycle`.
 */
class MotorcyclesSearch extends Motorcycle
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'athleteId', 'internalClassId', 'dateAdded', 'dateUpdated', 'status', 'creatorUserId', 'cbm', 'isCruiser'], 'integer'],
			[['mark', 'model'], 'safe'],
			[['power'], 'number'],
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
		$query = Motorcycle::find();
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort'  => ['defaultOrder' => ['dateAdded' => SORT_DESC]]
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
			'athleteId'       => $this->athleteId,
			'internalClassId' => $this->internalClassId,
			'dateAdded'       => $this->dateAdded,
			'dateUpdated'     => $this->dateUpdated,
			'status'          => $this->status,
			'creatorUserId'   => $this->creatorUserId,
			'cbm'             => $this->cbm,
			'power'           => $this->power,
			'isCruiser'       => $this->isCruiser,
		]);
		
		$query->andFilterWhere(['like', 'mark', $this->mark])
			->andFilterWhere(['like', 'model', $this->model]);
		
		return $dataProvider;
	}
	
	public function beforeValidate()
	{
		return ActiveRecord::beforeValidate();
	}
}
