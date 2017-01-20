<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Track;
use yii\db\ActiveRecord;

/**
 * TrackSearch represents the model behind the search form about `common\models\Track`.
 */
class TrackSearch extends Track
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'documentId'], 'integer'],
			[['photoPath', 'description', 'title'], 'safe'],
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
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = Track::find();
		
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
			'id'         => $this->id,
			'documentId' => $this->documentId,
		]);
		
		$query->andFilterWhere(['like', 'photoPath', $this->photoPath])
			->andFilterWhere(['like', 'description', $this->description])
			->andFilterWhere(['like', 'title', $this->title]);
		
		return $dataProvider;
	}
	
	public function beforeValidate()
	{
		return ActiveRecord::beforeValidate();
	}
}
