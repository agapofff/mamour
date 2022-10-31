<?php

namespace common\models;

use Yii;

class News extends \yii\db\ActiveRecord
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
                'galleryId' => 'news',
                'allowExtensions' => ['jpg', 'jpeg', 'png'],
            ],
            'seo' => [
                'class' => 'dvizh\seo\behaviors\SeoFields',
            ],
        ];
    }
    
    public static function tableName()
    {
        return '{{%news}}';
    }

    public function rules()
    {
        return [
            [['active', 'sort'], 'integer'],
            [['date_published'], 'safe'],
            [['name', 'description', 'text', 'publisher', 'category',], 'string'],
            [['category'], 'required'],
            [['slug'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('back', 'ID'),
            'active' => Yii::t('back', 'Активно'),
            'date_published' => Yii::t('back', 'Дата публикации'),
            'name' => Yii::t('back', 'Название'),
            'category' => Yii::t('back', 'Категория'),
            'description' => Yii::t('back', 'Описание'),
            'text' => Yii::t('back', 'Текст'),
            'publisher' => Yii::t('back', 'Издание'),
            'slug' => Yii::t('back', 'Алиас'),
            'sort' => Yii::t('back', 'Порядок'),
        ];
    }
}
