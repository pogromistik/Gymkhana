<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AssocNews;
use yii\db\ActiveRecord;

/**
 * AssocNewsSearch represents the model behind the search form about `common\models\AssocNews`.
 */
class AssocNewsSearch extends AssocNews
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'dateAdded', 'dateUpdated'], 'integer'],
			[['title', 'previewText', 'fullText', 'link'], 'safe'],
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
		$query = AssocNews::find();
		
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
			'id'          => $this->id,
			'dateAdded'   => $this->dateAdded,
			'dateUpdated' => $this->dateUpdated,
		]);
		
		$query->andFilterWhere(['like', 'title', $this->title])
			->andFilterWhere(['like', 'previewText', $this->previewText])
			->andFilterWhere(['like', 'fullText', $this->fullText])
			->andFilterWhere(['like', 'link', $this->link]);
		
		return $dataProvider;
	}
	
	public function beforeValidate()
	{
		return ActiveRecord::beforeValidate();
	}
}
