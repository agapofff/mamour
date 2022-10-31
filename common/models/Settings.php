<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%settings}}".
 *
 * @property int $id
 * @property int $active
 * @property string|null $category
 * @property string|null $name
 * @property string|null $value
 */
class Settings extends \yii\db\ActiveRecord
{    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%settings}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['active', 'type'], 'integer'],
            [['category', 'name', 'description'], 'string', 'max' => 255],
            [['value'], 'safe'],
            [['value', 'category', 'name', 'description'], 'default', 'value' => null],
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
            'category' => Yii::t('back', 'Категория'),
            'name' => Yii::t('back', 'Параметр'),
            'value' => Yii::t('back', 'Значение'),
            'type' => Yii::t('back', 'Тип значения'),
            'description' => Yii::t('back', 'Описание'),
        ];
    }
    
}
