<?php

namespace backend\models;

use Yii;

class ShopProduct extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%shop_product}}';
    }

    public function rules()
    {
        return [
            [['category_id', 'producer_id', 'amount', 'is_new', 'is_popular', 'is_promo', 'sort'], 'integer'],
            [['related_products', 'name', 'text', 'short_text', 'images', 'available', 'related_ids', 'sku', 'video'], 'string'],
            [['name'], 'required'],
            [['code'], 'string', 'max' => 155],
            [['slug', 'vendor_code'], 'string', 'max' => 255],
            [['barcode'], 'string', 'max' => 55],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['producer_id'], 'exist', 'skipOnError' => true, 'targetClass' => ShopProducer::className(), 'targetAttribute' => ['producer_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('back', 'ID'),
            'category_id' => Yii::t('back', 'Category ID'),
            'producer_id' => Yii::t('back', 'Producer ID'),
            'amount' => Yii::t('back', 'Amount'),
            'related_products' => Yii::t('back', 'Related Products'),
            'name' => Yii::t('back', 'Name'),
            'code' => Yii::t('back', 'Code'),
            'text' => Yii::t('back', 'Text'),
            'short_text' => Yii::t('back', 'Short Text'),
            'is_new' => Yii::t('back', 'Is New'),
            'is_popular' => Yii::t('back', 'Is Popular'),
            'is_promo' => Yii::t('back', 'Is Promo'),
            'images' => Yii::t('back', 'Images'),
            'available' => Yii::t('back', 'Available'),
            'sort' => Yii::t('back', 'Sort'),
            'slug' => Yii::t('back', 'Slug'),
            'related_ids' => Yii::t('back', 'Related Ids'),
            'sku' => Yii::t('back', 'Sku'),
            'barcode' => Yii::t('back', 'Barcode'),
            'video' => Yii::t('back', 'Video'),
            'vendor_code' => Yii::t('back', 'Vendor Code'),
        ];
    }

    public function getLotteries()
    {
        return $this->hasMany(Lotteries::className(), ['product_id' => 'id']);
    }

    public function getCategory()
    {
        return $this->hasOne(ShopCategory::className(), ['id' => 'category_id']);
    }

    public function getProducer()
    {
        return $this->hasOne(ShopProducer::className(), ['id' => 'producer_id']);
    }

    public function getShopProductModifications()
    {
        return $this->hasMany(ShopProductModification::className(), ['product_id' => 'id']);
    }

    public function getShopProductToCategories()
    {
        return $this->hasMany(ShopProductToCategory::className(), ['product_id' => 'id']);
    }
}
