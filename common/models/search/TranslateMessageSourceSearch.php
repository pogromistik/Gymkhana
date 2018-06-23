<?php

namespace common\models\search;

use common\models\TranslateMessage;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TranslateMessageSource;
use yii\db\Query;

/**
 * SourceMessageSearch represents the model behind the search form about `common\models\SourceMessage`.
 */
class TranslateMessageSourceSearch extends TranslateMessageSource
{
	public $translation;
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'status'], 'integer'],
			[['category', 'message', 'translation'], 'safe'],
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
		$query = TranslateMessageSource::find();
		$query->select('*, (SELECT COUNT("b"."id") FROM "TranslateMessage" "b" WHERE "TranslateMessageSource"."id"="b"."id" AND "b"."translation" != \'\') as count')->distinct();
		$query->orderBy(['count' => SORT_ASC]);
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'sort'  => ['defaultOrder' => ['message' => SORT_ASC]]
		]);
		
		$this->load($params);
		
		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}
		
		if ($this->translation) {
			$query->joinWith('messages');
			$query->andFilterWhere(
				['like', 'upper("translation")', mb_strtoupper($this->translation, 'UTF-8')]
			);
		}
		
		// grid filtering conditions
		$query->andFilterWhere([
			'id'     => $this->id,
			'status' => $this->status,
		]);
		
		$query->andFilterWhere(['like', 'category', $this->category])
			->andFilterWhere(['like', 'upper("message")', mb_strtoupper($this->message, 'UTF-8')]);
		
		return $dataProvider;
	}
}
