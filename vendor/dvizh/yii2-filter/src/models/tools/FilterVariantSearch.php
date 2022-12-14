<?php
namespace dvizh\filter\models\tools;

use yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dvizh\filter\models\FilterVariant;


class FilterVariantSearch extends FilterVariant
{
    public function rules()
    {
        return [
            [['id', 'filter_id', 'sort', 'active'], 'integer'],
            [['value'], 'safe'],
        ];
    }
    
    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = FilterVariant::find();

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
            'active' => $this->active,
            'filter_id' => $this->filter_id  ?: Yii::$app->request->get('id'),
        ]);

        $query->andFilterWhere(['like', 'value', $this->value]);

        return $dataProvider;
    }
}
