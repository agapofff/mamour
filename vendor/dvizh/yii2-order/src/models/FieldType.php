<?php
namespace dvizh\order\models;

use yii;

class FieldType extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%order_field_type}}';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['widget'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('back', 'ID'),
            'name' => Yii::t('back', 'Name'),
            'widget' => Yii::t('back', 'Widget'),
        ];
    }
}
