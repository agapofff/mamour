<?php
namespace dvizh\order\models\tools;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dvizh\order\models\ShippingType;

class ShippingTypeSearch extends ShippingType
{
    public function rules()
    {
        return [
            [['name', 'postcodes', 'payment_types'], 'string'],
            [['id', 'sort', 'country_id', 'cost',], 'integer'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = ShippingType::find();

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
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'country_id' => $this->country_id,
            'cost' => $this->cost,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'postcodes', $this->postcodes]);
        $query->andFilterWhere(['like', 'payment_types', $this->payment_types]);

        return $dataProvider;
    }
}
