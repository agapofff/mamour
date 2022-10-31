<?php
namespace dvizh\order\models\tools;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dvizh\order\models\PaymentType;

class PaymentTypeSearch extends PaymentType
{
    public function rules()
    {
        return [
            [['name', 'widget'], 'string'],
            [['id', 'sort'], 'integer'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = PaymentType::find();

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
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'widget', $this->widget]);

        return $dataProvider;
    }
}
