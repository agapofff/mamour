<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class ModificationToOption extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%shop_product_modification_to_option}}';
    }
    
    public function rules()
    {
        return [
            [['modification_id', 'option_id'], 'required'],
            [['option_id', 'option_id', 'variant_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'modification_id' => 'Модификация',
            'option_id' => 'Опция',
            'variant_id' => 'Значение',
        ];
    }
}
