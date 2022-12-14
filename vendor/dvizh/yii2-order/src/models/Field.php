<?php
namespace dvizh\order\models;

use yii;
use dvizh\order\models\FieldValueVariant;
use dvizh\order\models\FieldValue;
use dvizh\order\models\FieldType;

class Field extends \yii\db\ActiveRecord
{    
    public static function tableName()
    {
        return '{{%order_field}}';
    }

    public function rules()
    {
        return [
            [['name', 'type_id'], 'required'],
            [['id', 'type_id', 'sort', 'active', 'required'], 'integer'],
            [['description', 'name',], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('back', 'ID'),
            'active' => Yii::t('back', 'Активно'),
            'description' => Yii::t('back', 'Описание'),
            'name' => Yii::t('back', 'Имя'),
            'type_id' => Yii::t('back', 'Тип'),
            'sort' => Yii::t('back', 'Порядок'),
            'required' => Yii::t('back', 'Обязательно'),
        ];
    }
    
    public function getType()
    {
        return $this->hasOne(FieldType::className(), ['id' => 'type_id'])->one();
    }
    
    public function getValue($orderId)
    {
        if($value = $this->hasOne(FieldValue::className(), ['field_id' => 'id'])->andWhere(['order_id' => $orderId])->one()) {
            return $value->value;
        }
        else {
            return null;
        }
    }
    
    public function getVariants()
    {
        return $this->hasMany(FieldValueVariant::className(), ['field_id' => 'id']);
    }
    
    public function beforeDelete()
    {
        foreach ($this->hasMany(FieldValue::className(), ['field_id' => 'id'])->all() as $val) {
            $val->delete();
        }

        return true;
    }
}
