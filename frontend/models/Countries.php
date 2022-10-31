<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "{{%countries}}".
 *
 * @property int $id
 * @property int $publish
 * @property int $selected
 * @property int $code
 * @property int $lang_id
 * @property int $country_id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Registration[] $registrations
 */
class Countries extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%countries}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['publish', 'selected', 'code', 'lang_id', 'country_id'], 'integer'],
            [['code', 'lang_id', 'country_id', 'name', 'iso'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'iso'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('front', 'ID'),
            'publish' => Yii::t('front', 'Publish'),
            'selected' => Yii::t('front', 'Selected'),
            'code' => Yii::t('front', 'Code'),
            'lang_id' => Yii::t('front', 'Lang ID'),
            'country_id' => Yii::t('front', 'Country ID'),
            'name' => Yii::t('front', 'Name'),
            'iso' => Yii::t('front', 'ISO'),
            'created_at' => Yii::t('front', 'Created At'),
            'updated_at' => Yii::t('front', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[Registrations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRegistrations()
    {
        return $this->hasMany(Registration::className(), ['country' => 'id']);
    }
}
