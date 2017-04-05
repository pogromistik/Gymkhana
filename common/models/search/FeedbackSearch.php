<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Feedback;
use yii\db\ActiveRecord;

/**
 * FeedbackSearch represents the model behind the search form about `common\models\Feedback`.
 */
class FeedbackSearch extends Feedback
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'dateAdded', 'dateUpdated', 'athleteId', 'isNew'], 'integer'],
			[['username', 'phone', 'email', 'text'], 'safe'],
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
		$query = Feedback::find();
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort' => ['defaultOrder' => ['isNew' => SORT_DESC, 'dateAdded' => SORT_DESC]]
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
			'dateAdded'   => $this->dateAdded,
			'dateUpdated' => $this->dateUpdated,
			'athleteId'   => $this->athleteId,
			'isNew'       => $this->isNew,
		]);
		
		$query->andFilterWhere(['like', 'username', $this->username])
			->andFilterWhere(['like', 'phone', $this->phone])
			->andFilterWhere(['like', 'email', $this->email])
			->andFilterWhere(['like', 'text', $this->text]);
		
		return $dataProvider;
	}
	
	public function beforeValidate()
	{
		return ActiveRecord::beforeValidate();
	}
}
