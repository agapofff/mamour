<?php

namespace common\models;

use Yii;

class Redirects extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%redirects}}';
    }

    public function rules()
    {
        return [
            [['type', 'active'], 'integer'],
            [['link_from', 'link_to'], 'required'],
            [['link_from', 'link_to'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('back', 'ID'),
            'active' => Yii::t('back', 'Активно'),
            'type' => Yii::t('back', 'Тип'),
            'link_from' => Yii::t('back', 'Ссылка'),
            'link_to' => Yii::t('back', 'Редирект'),
        ];
    }
}
