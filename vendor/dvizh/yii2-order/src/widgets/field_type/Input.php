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
                    'class' => 'form-control mb-0 px-0 ' . $this->class,
                    'autocomplete' => rand(),
                    'placeholder' => ' ',
                    'value' => $this->defaultValue,
                    // 'required' => ($this->fieldModel->required == 'yes'),
                    'data-field' => $this->fieldModel->name,
                ],
                'options' => [
                    'class' => 'form-group mb-2 position-relative floating-label',
                ],
                'template' => '{input}{label}{hint}{error}',
            ])
            ->label(Yii::t('front', $this->fieldModel->description))
            // ->label(false)
            ->textInput();
    }
}
