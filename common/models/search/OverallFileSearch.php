<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\OverallFile;

/**
 * OverallFileSearch represents the model behind the search form about `common\models\OverallFile`.
 */
class OverallFileSearch extends OverallFile
{
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'userId', 'date', 'sort'], 'integer'],
			[['modelClass', 'modelId', 'title', 'fileName', 'filePath'], 'safe'],
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
		$query = OverallFile::find();
		
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
			'id'     => $this->id,
			'userId' => $this->userId,
			'date'   => $this->date,
		]);
		
		$query->andFilterWhere(['like', 'modelClass', $this->modelClass])
			->andFilterWhere(['like', 'modelId', $this->modelId])
			->andFilterWhere(['like', 'title', $this->title])
			->andFilterWhere(['like', 'fileName', $this->fileName])
			->andFilterWhere(['like', 'filePath', $this->filePath]);
		
		return $dataProvider;
	}
}
