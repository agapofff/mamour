<?php
namespace dvizh\order\models;

use yii;

class Payment extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%order_payment}}';
    }

    public function rules()
    {
        return [
            [['order_id', 'amount', 'description', 'date', 'payment_type_id', 'ip'], 'required'],
            [['order_id', 'user_id', 'payment_type_id'], 'integer'],
            [['amount'], 'number'],
            [['date'], 'safe'],
            [['description'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 55],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => Yii::t('back', 'Заказ'),
            'amount' => Yii::t('back', 'Сумма'),
            'description' => Yii::t('back', 'Описание'),
            'user_id' => Yii::t('back', 'Пользователь'),
            'date' => Yii::t('back', 'Дата'),
            'payment_type_id' => Yii::t('back', 'Способ оплаты'),
            'ip' => Yii::t('back', 'IP'),
        ];
    }
    
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }
    
    public function getPayment()
    {
        return $this->hasOne(PaymentType::className(), ['id' => 'payment_type_id']);
    }
}
