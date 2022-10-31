<?php

namespace dvizh\order\models;

use yii;
use common\models\Countries;

class ShippingType extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%order_shipping_type}}';
    }

    public function rules()
    {
        return [
            [['name', 'country_id'], 'required'],
            [['sort', 'active', 'country_id', 'cost', 'free_cost_from'], 'integer'],
            [['description'], 'string'],
            [['postcodes', 'payment_types'], 'safe'],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Countries::className(), 'targetAttribute' => ['country_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('back', 'ID'),
            'name' => Yii::t('back', 'Название'),
            'sort' => Yii::t('back', 'Порядок'),
            'cost' => Yii::t('back', 'Стоимость'),
            'free_cost_from' => Yii::t('back', 'Бесплатно от'),
            'active' => Yii::t('back', 'Активно'),
            'description' => Yii::t('back', 'Описание'),
            'country_id' => Yii::t('back', 'Страна'),
            'postcodes' => Yii::t('back', 'Идентификаторы'),
            'payment_types' => Yii::t('back', 'Способы оплаты'),
        ];
    }
    
    public function getCountry()
    {
        return $this->hasOne(Countries::className(), ['id' => 'country_id']);
    }
}
