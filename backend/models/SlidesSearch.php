<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Slides;

/**
 * SlidesSearch represents the model behind the search form of `backend\models\Slides`.
 */
class SlidesSearch extends Slides
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'active', 'show_button', 'content_align', 'sort'], 'integer'],
            [['category', 'text', 'link', 'button_text'], 'safe'],
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
        $query = Slides::find();

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
            'show_button' => $this->show_button,
            'content_align' => $this->content_align,
            'category' => $this->category,
        ]);

        $query->andFilterWhere(['like', 'category', $this->category]);
        $query->andFilterWhere(['like', 'text', $this->text]);
        $query->andFilterWhere(['like', 'link', $this->link]);
        $query->andFilterWhere(['like', 'button_text', $this->button_text]);

        return $dataProvider;
    }
}
