<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\switchinput\SwitchInput;

?>

<div class="filter-form">

    <?php 
        $form = ActiveForm::begin(); 
    ?>
    
            <?= $form
                    ->field($model, 'active')
                    ->widget(SwitchInput::classname(), [
                        'pluginOptions' => [
                            'onText' => Yii::t('back', 'Да'),
                            'offText' => Yii::t('back', 'Нет'),
                            'onColor' => 'success',
                            'offColor' => 'danger',
                        ],
                    ]);
            ?>

            <div class="relationModels hidden">
                <?= $form->field($model, 'relation_field_value')->checkboxList(
                        Yii::$app->getModule('filter')->relationFieldValues) ?>
            </div>

            <?= $form
                    ->field($model, 'relation_field_name')
                    ->hiddenInput([
                        'value' => Yii::$app->getModule('filter')->relationFieldName
                    ])
                    ->label(false); 
            ?>

            <?= $form
                    ->field($model, 'name')
                    ->textInput() 
            ?>
            
            <?= $form
                    ->field($model, 'slug')
                    ->textInput([
                        'label' => 'Идентификатор'
                    ]) 
            ?>
            
            <?= $form
                    ->field($model, 'sort')
                    ->hiddenInput()
                    ->label(false)
            ?>
            
            <div class="row">
                <div class="col-md-6">
                    <?= $form
                            ->field($model, 'is_option')
                            ->dropdownList([
                                'no' => 'Нет', 
                                'yes' => 'Да'
                            ]) 
                    ?>
                </div>
                <div class="col-md-6">
                    <?= $form
                            ->field($model, 'is_filter')
                            ->dropdownList([
                                'no' => 'Нет',
                                'yes' => 'Да'
                            ]) 
                    ?>
                </div>
            </div>
            
            <?= $form
                    ->field($model, 'type')
                    ->dropdownList(Yii::$app->getModule('filter')->types)
                    ->label('Тип фильтрации на сайте') 
            ?>
            
            <?= $form
                    ->field($model, 'description')
                    ->textArea([
                        'maxlength' => true
                    ]) 
            ?>
            
            <div class="form-group text-center">
                <?= Html::submitButton(Html::tag('span', '', [
                        'class' => 'glyphicon glyphicon-floppy-saved'
                    ]) . '&nbsp;' . Yii::t('back', 'Сохранить'), [
                        'class' => 'btn btn-success btn-lg'
                    ]) 
                ?>
            </div>

    <?php ActiveForm::end(); ?>

</div>
<style>
.relationModels label {
    display: block;
}
</style>