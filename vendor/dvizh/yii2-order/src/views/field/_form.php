<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use kartik\alert\AlertBlock;
use yii\widgets\Pjax;
use dvizh\order\models\FieldValueVariant;
use dvizh\order\models\tools\FieldValueVariantSearch;
use dvizh\order\models\FieldType;
use yii\helpers\ArrayHelper;
use backend\widgets\MultilangField;
use kartik\switchinput\SwitchInput;
?>

<?php Pjax::begin(); ?>

    <div class="field-form">
    
        <?= AlertBlock::widget([
                'type' => 'growl',
                'useSessionFlash' => true,
                'delay' => 1,
            ]);
        ?>

        <?php $form = ActiveForm::begin(); ?>
        
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
            
            <?= $form
                    ->field($model, 'name')
                    ->textInput() 
            ?>
            
            <?= $form
                    ->field($model, 'type_id')
                    ->dropDownList(ArrayHelper::map($fieldTypes, 'id', 'name')) 
            ?>
            
            <?= $form
                    ->field($model, 'required')
                    ->widget(SwitchInput::classname(), [
                        'pluginOptions' => [
                            'onText' => Yii::t('back', 'Да'),
                            'offText' => Yii::t('back', 'Нет'),
                            'onColor' => 'success',
                            'offColor' => 'danger',
                        ],
                    ]);
            ?>
            
            <?= $form
                    ->field($model, 'sort')
                    ->hiddenInput() 
                    ->label(false)
            ?>
            
            <?= MultilangField::widget([
                    'model' => $model,
                    'form' => $form,
                    'field' => 'description',
                    'languages' => $languages,
                ]);
            ?>
        
            <?= Html::input('hidden', 'saveAndExit', 0) ?>

            <br>
            <div class="form-group text-center">
                <?= Html::submitButton(Html::tag('span', '', [
                        'class' => 'glyphicon glyphicon-floppy-saved'
                    ]) . '&nbsp;' . Yii::t('back', 'Сохранить'), [
                        'class' => 'btn btn-success btn-lg'
                    ]) 
                ?>
                
                <?= Html::submitButton(Html::tag('span', '', [
                        'class' => 'glyphicon glyphicon-floppy-remove'
                    ]) . '&nbsp;' . Yii::t('back', 'Сохранить и закрыть'), [
                        'class' => 'btn btn-default btn-lg saveAndExit' . ($model->id ? '' : ' hidden')
                    ])
                ?>
            </div>

        <?php ActiveForm::end(); ?>

        
        <?php if(!$model->isNewRecord && $model->type->have_variants == 'yes') { ?>
        
            <?php
            $variantModel = new FieldValueVariant();
            
            $searchModel = new FieldValueVariantSearch();
            
            $params = Yii::$app->request->queryParams;
            if(empty($params['FieldValueVariantSearch'])) {
                $params = ['FieldValueVariantSearch' => ['field_id' => $model->id]];
            }

            $dataProvider = $searchModel->search($params); 
            ?>
            <div class="dvizh-variants">
                <h3><?=Yii::t('back', 'Variants');?></h3>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        [
                            'class' => \dosamigos\grid\columns\EditableColumn::className(),
                            'attribute' => 'value',
                            'url' => ['/order/field-value-variant/editable'],
                            'filter' => false,
                            'editableOptions' => [
                                'mode' => 'inline',
                            ]
                        ],
                        ['class' => 'yii\grid\ActionColumn', 'controller' => '/order/field-value-variant', 'template' => '{delete}',  'buttonOptions' => ['class' => 'btn btn-default'], 'options' => ['style' => 'width: 75px;']],
                    ],
                ]); ?>
                
                <h3><?=Yii::t('back', 'New variant');?></h3>
                <?php $form = ActiveForm::begin(['action' =>['/order/field-value-variant/create'], 'id' => 'forum_post', 'method' => 'post',]); ?>
                    <?= $form->field($variantModel, 'field_id')->hiddenInput(['value' => $model->id])->label(false) ?>
                
                    <?= $form->field($variantModel, 'value')->textInput() ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('back', 'Create'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
            </div>
        <?php } ?>
        
    </div>

<?php Pjax::end(); ?>