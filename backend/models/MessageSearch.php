<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Message;

class MessageSearch extends Message
{
    
    public $sourceMessage;
    
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['language', 'translation'], 'safe'],
            [['sourceMessage'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Message::find();

        // add conditions that should always apply here
        
        $query->joinWith('sourceMessage');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ],
            ],
        ]);
        
        $dataProvider->sort->attributes['sourceMessage'] = [
            'asc' => ['{{%source_message}}.message' => SORT_ASC],
            'desc' => ['{{%source_message}}.message' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            '{{%message}}.id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'language', $this->language])
            ->andFilterWhere(['like', 'translation', $this->translation])
            ->andFilterWhere(['like', '{{%source_message}}.message', $this->sourceMessage]);

        return $dataProvider;
    }
}
