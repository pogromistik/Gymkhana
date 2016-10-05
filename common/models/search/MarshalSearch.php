<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Marshal;

/**
 * MarshalSearch represents the model behind the search form about `common\models\Marshal`.
 */
class MarshalSearch extends Marshal
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'post', 'photo', 'text1', 'text2', 'text3', 'motorcycle', 'motorcyclePhoto', 'gif', 'link'], 'safe'],
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
        $query = Marshal::find();

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
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'post', $this->post])
            ->andFilterWhere(['like', 'photo', $this->photo])
            ->andFilterWhere(['like', 'text1', $this->text1])
            ->andFilterWhere(['like', 'text2', $this->text2])
            ->andFilterWhere(['like', 'text3', $this->text3])
            ->andFilterWhere(['like', 'motorcycle', $this->motorcycle])
            ->andFilterWhere(['like', 'motorcyclePhoto', $this->motorcyclePhoto])
            ->andFilterWhere(['like', 'gif', $this->gif])
            ->andFilterWhere(['like', 'link', $this->link]);

        return $dataProvider;
    }
}
