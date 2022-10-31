<?php
namespace dvizh\shop\models\product;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use dvizh\shop\models\Product;
use dvizh\shop\models\Category;

use yii\helpers\ArrayHelper;

class ProductSearch extends Product
{
    
    public $price;
    
    public $pageSize = 9;
    
    // public $route = 'shop/product';
    
    public function rules()
    {
        return [
            [['category_id', 'producer_id', 'price'], 'integer'],
            [['id', 'name', 'text', 'short_text', 'available', 'code', 'sort'], 'safe'],
        ];
    }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Product::find(); // ->with(['prices', 'categories', 'toCategory']);
        
        $query->joinWith(['categories']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => new \yii\data\Sort([
                'attributes' => [
                    'name',
                    'id',
                    'available',
                    'active',
                    'code',
                    'sort',
                ],
                'defaultOrder' => [
                    'sort' => SORT_DESC
                ],
            ]),
            'pagination' => [
                'pageSize' => $this->pageSize,
                // 'route' => $this->route,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            '{{%shop_product}}.id' => $this->id,
            // '{{%shop_product}}.category_id' => $this->category_id,
            // '{{%shop_category}}.parent_id' => $this->category_id,
            '{{%shop_product}}.active' => $this->active,
            '{{%shop_product}}.available' => $this->available,
            '{{%shop_product}}.producer_id' => $this->producer_id,
            // '{{#shop_price}}.price' => $this->price,
        ]);
        
        // выводим товары из подкатегорий в выборке по категориям
        // if ($this->category_id){
            // $child_categories = Category::find()->where([
                // 'parent_id' => $this->category_id
            // ])->all();
            // if ($child_categories){
                // $query->andFilterWhere([
                    // 'in', '{{%shop_product_to_category}}.category_id', ArrayHelper::getColumn($child_categories, 'id')
                // ]);
            // } else {
                // $query->andFilterWhere([
                    // '{{%shop_product_to_category}}.category_id' => $this->category_id
                // ]);
            // }
        // }
        
        if ($this->category_id){
            $query->andFilterWhere(['{{%shop_product_to_category}}.category_id' => $this->category_id]);
        }

        $query
            ->andFilterWhere(['like', '{{%shop_product}}.name', $this->name])
            ->andFilterWhere(['like', '{{%shop_product}}.text', $this->text])
            ->andFilterWhere(['like', '{{%shop_product}}.code', $this->code])
            // ->andFilterWhere(['like', '{{%shop_price}}.price', $this->price])
            ->andFilterWhere(['like', '{{%shop_product}}.short_text', $this->short_text]);
            
        $query->groupBy('{{%shop_product}}.id');
            
// echo '<code><pre>'; print_r($query->all()); echo '</pre></code>'; exit;
            
// print_r($query->createCommand()->getRawSql());

        return $dataProvider;
    }
}
