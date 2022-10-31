<?php
namespace dvizh\order\models;

use yii;

class PaymentType extends \yii\db\ActiveRecord
{
    
    public $saveAndExit;
    
    public static function tableName()
    {
        return '{{%order_payment_type}}';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['sort', 'active', 'saveAndExit'], 'integer'],
            [['widget'], 'validateWidget'],
            [['description'], 'string'],
        ];
    }

    public function validateWidget($attribute, $param)
    {
        $widget = $this->widget;
        
        if(trim($widget) == '') {
            return true;
        }
        
        if (class_exists($widget)) {
            $widgetEx = new $widget();
            if (!$widgetEx instanceof \yii\base\Widget) {
                $this->addError($attribute, 'Widget type error');
            }
        } else {
            $this->addError($attribute, 'Widget not exists');
        }
        
        return true;
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('back', 'ID'),
            'name' => Yii::t('back', 'Название'),
            'sort' => Yii::t('back', 'Порядок'),
            'widget' => Yii::t('back', 'Виджет'),
            'active' => Yii::t('back', 'Активно'),
            'description' => Yii::t('back', 'Описание'),
        ];
    }
}
