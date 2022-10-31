<?php
namespace dvizh\shop\models\category;

use yii\db\ActiveQuery;

class CategoryQuery extends ActiveQuery
{
    public function popular()
    {
         return $this->andWhere(['is_popular' => 'yes']);
    }
    
    public function active()
    {
         return $this->andWhere(['active' => 1]);
    }
}