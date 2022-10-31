<?php

namespace common\models;

use Yii;

class NewsCategories extends \yii\db\ActiveRecord
{
    function behaviors()
    {
        return [
            'slug' => [
                'class' => 'Zelenin\yii\behaviors\Slug',
            ],
            'seo' => [
                'class' => 'dvizh\seo\behaviors\SeoFields',
            ],
        ];
    }
    
    public static function tableName()
    {
        return '{{%news_categories}}';
    }

    public function rules()
    {
        return [
            [['name'], 'string'],
            [['active', 'sort'], 'integer'],
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
            'sort' => Yii::t('back', 'Порядок'),
        ];
    }

    public function getNews()
    {
        return $this->hasMany(News::className(), ['category_id' => 'id']);
    }
}
