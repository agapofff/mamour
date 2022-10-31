<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%actions}}".
 *
 * @property int $id
 * @property int $active
 * @property string $published
 * @property int $type
 * @property string|null $name
 * @property string|null $title
 * @property string|null $description
 * @property string|null $text
 */
class Actions extends \yii\db\ActiveRecord
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
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%actions}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['active', 'type'], 'integer'],
            [['published'], 'required'],
            [['published'], 'safe'],
            [['name', 'title', 'description', 'text', 'slug'], 'string'],
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
            'published' => Yii::t('back', 'Дата публикации'),
            'type' => Yii::t('back', 'Тип акции'),
            'name' => Yii::t('back', 'Название'),
            'slug' => Yii::t('back', 'Алиас'),
            'title' => Yii::t('back', 'Заголовок'),
            'description' => Yii::t('back', 'Описание'),
            'text' => Yii::t('back', 'Условия'),
        ];
    }
}
