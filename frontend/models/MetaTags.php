<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%meta_tags}}".
 *
 * @property int $id
 * @property int $active
 * @property string $link
 * @property string $title
 * @property string $description
 * @property string $h1
 */
class MetaTags extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%meta_tags}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['active'], 'integer'],
            [['link', 'title', 'description', 'h1'], 'required'],
            [['description'], 'string'],
            [['link', 'title', 'h1'], 'string', 'max' => 255],
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
            'link' => Yii::t('front', 'Link'),
            'title' => Yii::t('front', 'Title'),
            'description' => Yii::t('front', 'Description'),
            'h1' => Yii::t('front', 'H1'),
        ];
    }
}
