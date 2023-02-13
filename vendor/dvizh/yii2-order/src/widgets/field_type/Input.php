<?php
namespace dvizh\order\widgets\field_type;
use Yii;
use dvizh\order\models\FieldValue;

class Input extends \yii\base\Widget
{
    public $fieldValueModel = null;
    public $fieldModel = null;
    public $form = null;
    public $defaultValue = '';
    public $class = null;
    // public $hidden = false;
    
    public function run()
    {
        $fieldValueModel = new FieldValue;
        $fieldValueModel->value = $this->defaultValue;
        
        return $this->form
            ->field($fieldValueModel, 'value['.$this->fieldModel->id.']', [
                'inputOptions' => [
                    'class' => 'form-control ' . $this->class,
                    'autocomplete' => rand(),
                    'placeholder' => ' ',
                    'value' => $this->defaultValue,
                    // 'required' => ($this->fieldModel->required == 'yes'),
                    'data-field' => $this->fieldModel->name,
                ],
            ])
            ->label(Yii::t('front', json_decode($this->fieldModel->description)->{Yii::$app->language}))
            // ->label(false)
            ->textInput();
    }
}
