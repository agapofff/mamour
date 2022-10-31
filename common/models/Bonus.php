<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%bonus}}".
 *
 * @property int $id
 * @property int $active
 * @property int|null $user_id
 * @property int|null $type
 * @property int|null $amount
 * @property int|null $reason
 * @property string|null $description
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property User $user
 */
class Bonus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bonus}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['active', 'user_id', 'type', 'amount', 'reason'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['description'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'user_id' => Yii::t('back', 'Пользователь'),
            'type' => Yii::t('back', 'Операция'),
            'amount' => Yii::t('back', 'Значение'),
            'reason' => Yii::t('back', 'Основание'),
            'description' => Yii::t('back', 'Пояснение'),
            'created_at' => Yii::t('back', 'Дата создания'),
            'updated_at' => Yii::t('back', 'Дата изменения'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    public static function getUserBonus($user_id)
    {            
        $total = 0;
        $reasons = [
            0 => Yii::$app->params['bonus']['minus'],
            1 => Yii::$app->params['bonus']['plus'],
        ];
        $history = [];
        
        $bonuses = Bonus::find()
            ->where([
                'user_id' => $user_id,
                'active' => 1
            ])
            ->all();
            
        if ($bonuses) {
            foreach ($bonuses as $bonus) {
                if ($bonus->type) {
                    $total += $bonus->amount;
                    $reasons[$bonus->reason] += $bonus->amount;
                } else {
                    $total -= $bonus->amount;
                }
            }
        }
        
        return [
            'total' => $total,
            'reasons' => $reasons,
            'bonuses' => $bonuses,
        ];
    }

}
