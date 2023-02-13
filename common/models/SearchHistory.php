<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%search_history}}".
 *
 * @property int $id
 * @property string|null $user
 * @property string|null $dateandtime
 * @property string|null $request
 */
class SearchHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%search_history}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['dateandtime'], 'safe'],
            [['user', 'request'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('back', 'ID'),
            'user' => Yii::t('back', 'Пользователь'),
            'dateandtime' => Yii::t('back', 'Дата и время'),
            'request' => Yii::t('back', 'Запрос'),
        ];
    }
}
