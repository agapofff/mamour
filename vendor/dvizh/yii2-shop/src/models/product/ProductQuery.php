<?php
namespace dvizh\shop\models\product;

use dvizh\shop\models\Category;
use yii\db\ActiveQuery;

class ProductQuery extends ActiveQuery
{
    function behaviors()
    {
       return [
           'filter' => [
               'class' => 'dvizh\filter\behaviors\Filtered',
           ],
           'field' => [
               'class' => 'dvizh\field\behaviors\Filtered',
           ],
       ];
    }
    
    public function available()
    {
         return $this->andwhere("{{%shop_product}}.available = 1");
    }
    
    public function notAvailable()
    {
        return $this->andWhere("{{%shop_product}}.available = 0");
    }
    
    public function category($childCategoriesIds)
    {
        return $this->andwhere(['category_id' => $childCategoriesIds]);
    }
    
    public function active()
    {
         return $this->andWhere(['{{%shop_product}}.active' => 1]);
    }
    
}
