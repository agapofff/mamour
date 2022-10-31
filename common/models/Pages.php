<?php

namespace common\models;

use Yii;

class Pages extends \yii\db\ActiveRecord
{
    function behaviors()
    {
        return [
            'slug' => [
                'class' => 'Zelenin\yii\behaviors\Slug',
            ],
            'images' => [
                'class' => 'agapofff\gallery\behaviors\AttachImages',
                'mode' => 'single',
                'quality' => 80,
                'galleryId' => 'actions',
                'allowExtensions' => ['jpg', 'jpeg', 'png'],
            ],
            'seo' => [
                'class' => 'dvizh\seo\behaviors\SeoFields',
            ],
        ];
    }
    
    public static function tableName()
    {
        return '{{%pages}}';
    }

    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['name', 'text'], 'string'],
            [['active'], 'integer'],
            [['slug'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('back', 'ID'),
            'active' => Yii::t('back', 'Активно'),
            'name' => Yii::t('back', 'Название'),
            'slug' => Yii::t('back', 'Алиас'),
            'text' => Yii::t('back', 'Текст'),
        ];
    }
}
