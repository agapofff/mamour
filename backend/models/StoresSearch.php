<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Stores;

class StoresSearch extends Stores
{

    public function rules()
    {
        return [
            [['id', 'active', 'type', 'store_id', 'country_id'], 'integer'],
            [['name', 'description', 'postcodes', 'currency'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Stores::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'sort' => SORT_ASC
                ],
            ],
            'pagination' => false,
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
            'active' => $this->active,
            'type' => $this->type,
            // 'store_id' => $this->store_id,
            'country_id' => $this->country_id,
        ]);

        $query->andFilterWhere(['like', 'store_id', $this->store_id]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'description', $this->description]);
        $query->andFilterWhere(['like', 'postcodes', $this->postcodes]);
        $query->andFilterWhere(['like', 'currency', $this->postcodes]);

        return $dataProvider;
    }
}
