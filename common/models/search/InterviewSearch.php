<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Interview;
use yii\db\ActiveRecord;

/**
 * InterviewSearch represents the model behind the search form of `common\models\Interview`.
 */
class InterviewSearch extends Interview
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'dateAdded', 'dateUpdated', 'dateStart', 'dateEnd', 'onlyPictures', 'showResults'], 'integer'],
			[['title', 'titleEn', 'description', 'descriptionEn'], 'safe'],
		];
	}
	
	/**
	 * {@inheritdoc}
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
		$query = Interview::find();
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort'  => [
				'defaultOrder' => [
					'dateStart' => SORT_DESC
				]
			]
		]);
		
		$this->load($params);
		
		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}
		
		// grid filtering conditions
		$query->andFilterWhere([
			'id'           => $this->id,
			'dateAdded'    => $this->dateAdded,
			'dateUpdated'  => $this->dateUpdated,
			'dateStart'    => $this->dateStart,
			'dateEnd'      => $this->dateEnd,
			'onlyPictures' => $this->onlyPictures,
			'showResults'  => $this->showResults,
		]);
		
		$query->andFilterWhere(['ilike', 'title', $this->title])
			->andFilterWhere(['ilike', 'titleEn', $this->titleEn])
			->andFilterWhere(['ilike', 'description', $this->description])
			->andFilterWhere(['ilike', 'descriptionEn', $this->descriptionEn]);
		
		return $dataProvider;
	}
	
	public function beforeValidate()
	{
		return ActiveRecord::beforeValidate();
	}
}
