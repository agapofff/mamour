<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%galleries}}".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $description
 * @property string|null $text
 * @property string|null $slug
 */
class Galleries extends \yii\db\ActiveRecord
{
    function behaviors()
    {
        return [
            'slug' => [
                'class' => 'Zelenin\yii\behaviors\Slug',
            ],
            'images' => [
                'class' => 'agapofff\gallery\behaviors\AttachImages',
                'mode' => 'gallery',
                'quality' => 80,
                'galleryId' => 'galleries',
                'allowExtensions' => ['jpg', 'jpeg', 'png'],
            ],
            'seo' => [
                'class' => 'dvizh\seo\behaviors\SeoFields',
            ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%galleries}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['active', 'sort'], 'integer'],
            [['name', 'description', 'text', 'video',], 'string'],
            [['slug'], 'string', 'max' => 255],
            [['active', 'name',], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('back', 'ID'),
            'active' => Yii::t('back', 'Активно'),
            'name' => Yii::t('back', 'Название'),
            'video' => Yii::t('back', 'Видео'),
            'description' => Yii::t('back', 'Описание'),
            'text' => Yii::t('back', 'Текст'),
            'slug' => Yii::t('back', 'Алиас'),
            'sort' => Yii::t('back', 'Порядок'),
        ];
    }
}
