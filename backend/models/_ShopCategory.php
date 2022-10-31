<?php

namespace backend\models;

use Yii;

class ShopCategory extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%shop_category}}';
    }

    public function rules()
    {
        return [
            [['parent_id', 'sort'], 'integer'],
            [['name'], 'required'],
            [['name', 'text', 'image'], 'string'],
            [['code', 'slug'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('back', 'ID'),
            'parent_id' => Yii::t('back', 'Parent ID'),
            'name' => Yii::t('back', 'Name'),
            'code' => Yii::t('back', 'Code'),
            'slug' => Yii::t('back', 'Slug'),
            'text' => Yii::t('back', 'Text'),
            'image' => Yii::t('back', 'Image'),
            'sort' => Yii::t('back', 'Sort'),
        ];
    }

    public function getShopProducts()
    {
        return $this->hasMany(ShopProduct::className(), ['category_id' => 'id']);
    }

    public function getShopProductToCategories()
    {
        return $this->hasMany(ShopProductToCategory::className(), ['category_id' => 'id']);
    }
}
