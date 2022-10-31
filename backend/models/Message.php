<?php

namespace backend\models;

use Yii;

class Message extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%message}}';
    }

    public function rules()
    {
        return [
            [['id', 'language'], 'required'],
            [['id'], 'integer'],
            [['translation'], 'string'],
            [['language'], 'string', 'max' => 16],
            [['id', 'language'], 'unique', 'targetAttribute' => ['id', 'language']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => SourceMessage::className(), 'targetAttribute' => ['id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('back', 'ID'),
            'sourceMessage' => Yii::t('back', 'Константа'),
            'language' => Yii::t('back', 'Язык'),
            'translation' => Yii::t('back', 'Перевод'),
        ];
    }

    public function getSourceMessage()
    {
        return $this->hasOne(SourceMessage::className(), ['id' => 'id']);
    }
}
