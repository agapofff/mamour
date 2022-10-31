<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Redirects;

class RedirectsSearch extends Redirects
{

    public function rules()
    {
        return [
            [['id', 'active', 'type'], 'integer'],
            [['link_from', 'link_to'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Redirects::find();

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
            'active' => $this->active,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'link_from', $this->link_from])
            ->andFilterWhere(['like', 'link_to', $this->link_to]);

        return $dataProvider;
    }
}
