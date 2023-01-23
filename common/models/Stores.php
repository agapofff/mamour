<?php

namespace common\models;

use Yii;

class Stores extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%stores}}';
    }

    public function rules()
    {
        return [
            [['active', 'type', 'store_id', 'country_id', 'sort',], 'integer'],
            [['type', 'store_id', 'country_id'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['postcodes'], 'safe'],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Countries::className(), 'targetAttribute' => ['country_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('back', 'ID'),
            'active' => Yii::t('back', 'Активно'),
            'type' => Yii::t('back', 'Тип'),
            'store_id' => Yii::t('back', 'ID магазина'),
            'name' => Yii::t('back', 'Название'),
            'description' => Yii::t('back', 'Описание'),
            'country_id' => Yii::t('back', 'Страна'),
            'postcodes' => Yii::t('back', 'Идентификаторы'),
            'sort' => Yii::t('back', 'Порядок'),
        ];
    }
    
    public function getCountry()
    {
        return $this->hasOne(Countries::className(), ['id' => 'country_id']);
    }
    
}
