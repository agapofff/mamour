<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%pages}}".
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $text
 */
class Pages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%pages}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['text'], 'string'],
            [['active'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('front', 'ID'),
            'active' => Yii::t('front', 'Active'),
            'name' => Yii::t('front', 'Name'),
            'slug' => Yii::t('front', 'Slug'),
            'text' => Yii::t('front', 'Text'),
        ];
    }
}
