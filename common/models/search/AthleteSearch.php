<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Athlete;
use yii\db\ActiveRecord;

/**
 * AthleteSearch represents the model behind the search form about `common\models\Athlete`.
 */
class AthleteSearch extends Athlete
{
	public $firstOrLastName;
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['id', 'login', 'number', 'status', 'createdAt', 'updatedAt', 'hasAccount', 'lastActivityDate'], 'integer'],
			[['firstName', 'cityId', 'lastName', 'phone', 'email', 'authKey', 'passwordHash',
				'passwordResetToken', 'regionId', 'athleteClassId', 'countryId', 'firstOrLastName'], 'safe'],
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
	 * @param int   $pg
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params, $pg = 20)
	{
		$query = Athlete::find();
		
		// add conditions that should always apply here
		
		$dataProvider = new ActiveDataProvider([
			'pagination' => [
				'pageSize' => $pg,
			],
			'query'      => $query,
			'sort'       => ['defaultOrder' => ['lastName' => SORT_ASC, 'cityId' => SORT_ASC, 'firstName' => SORT_ASC]]
		]);
		
		$this->load($params);
		
		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}
		// grid filtering conditions
		$query->andFilterWhere([
			'id'               => $this->id,
			'login'            => $this->login,
			'cityId'           => $this->cityId,
			'athleteClassId'   => $this->athleteClassId,
			'number'           => $this->number,
			'status'           => $this->status,
			'createdAt'        => $this->createdAt,
			'updatedAt'        => $this->updatedAt,
			'hasAccount'       => $this->hasAccount,
			'lastActivityDate' => $this->lastActivityDate,
			'regionId'         => $this->regionId,
			'countryId'        => $this->countryId
		]);
		
		if ($this->firstOrLastName) {
			$array = explode(' ', $this->firstOrLastName);
			$this->lastName = $array[0];
			if (isset($array[1])) {
				$this->firstName = $array[1];
			} else {
				$query->andFilterWhere(['or',
					['like', 'firstName', $this->firstOrLastName],
					['like', 'lastName', $this->firstOrLastName]
				]);
			}
		}
		
		$query->andFilterWhere(['like', 'firstName', $this->firstName])
			->andFilterWhere(['like', 'lastName', $this->lastName])
			->andFilterWhere(['like', 'phone', $this->phone])
			->andFilterWhere(['like', 'email', $this->email])
			->andFilterWhere(['like', 'authKey', $this->authKey])
			->andFilterWhere(['like', 'passwordHash', $this->passwordHash])
			->andFilterWhere(['like', 'passwordResetToken', $this->passwordResetToken]);
		
		return $dataProvider;
	}
	
	public function beforeValidate()
	{
		return ActiveRecord::beforeValidate();
	}
}
