<?php

namespace common\models;

use Yii;

class Languages extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%languages}}';
    }

    public function rules()
    {
        return [
            [['name', 'code', 'currency'], 'required'],
            [['active', 'sort'], 'integer'],
            [['name', 'code', 'currency'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('back', 'ID'),
            'name' => Yii::t('back', 'Название'),
            'code' => Yii::t('back', 'Код (ISO-639)'),
            'active' => Yii::t('back', 'Включено'),
            'currency' => Yii::t('back', 'Валюта (ISO-4217)'),
            'sort' => Yii::t('back', 'Порядок'),
        ];
    }
    
    public static function getActiveCodes()
    {
        return self::find()
            ->select('code')
            ->where([
                'active' => 1
            ])
            ->asArray()
            ->column();
    }
}
