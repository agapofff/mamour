<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dvizh\shop\models\Category;
use agapofff\gallery\widgets\Gallery;
use kartik\select2\Select2;
use dvizh\seo\widgets\SeoForm;
use kartik\switchinput\SwitchInput;
use kartik\alert\AlertBlock;
use backend\widgets\MultilangField;
?>

<?php Pjax::begin(); ?>

    <div class="category-form">

        <?= AlertBlock::widget([
                'type' => 'growl',
                'useSessionFlash' => true,
                'delay' => 1,
            ]);
        ?>

        <?php 
            $form = ActiveForm::begin([
                'options' => [
                    'enctype' => 'multipart/form-data',
                ]
            ]);
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

            <?= MultilangField::widget([
                    'model' => $model,
                    'form' => $form,
                    'field' => 'name',
                    'languages' => ArrayHelper::getColumn($languages, 'code'),
                ]);
            ?>
            
            <?= $form
                    ->field($model, 'slug')
                    ->textInput()
            ?>
            
            <?= $form
                    ->field($model, 'parent_id')
                    ->widget(Select2::classname(), [
                        'data' => Category::buildTextTree(null, 1, [$model->id]),
                        'language' => 'ru',
                        'options' => [
                            'placeholder' => Yii::t('back', 'Выберите категорию')
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);
            ?>
            
            
            <?= $form
                    ->field($model, 'sort')
                    ->textInput([
                        'type' => 'number',
                        'min' => 0,
                        'max' => 99,
                    ])
            ?>
            
    <?php 
        if (!$model->isNewRecord) { 
    ?>      
            <label><?= Yii::t('back', 'Изображения') ?></label>
            <?php Pjax::begin(); ?>
                <?= Gallery::widget([
                        'model' => $model,
                        'previewSize' => '200x200',
                        'fileInputPluginOptions' => [
                            'showPreview' => false,
                        ],
                        'containerClass' => 'row',
                        'elementClass' => 'col-xs-4',
                        // 'deleteButtonClass' => 'btn btn-sm btn-danger position-absolute top-0 right-0',
                        'deleteButtonText' => Html::tag('i', '', ['class' => 'fa fa-trash']),
                        // 'editButtonClass' => 'btn btn-sm btn-info position-absolute bottom-0 right-0',
                        'editButtonText' => Html::tag('i', '', ['class' => 'fa fa-edit']),
                    ]);
                ?>
            <?php Pjax::end(); ?>
    <?php
        }
    ?>
            <br>
            <?= MultilangField::widget([
                    'model' => $model,
                    'form' => $form,
                    'field' => 'text',
                    'type' => 'html',
                    'languages' => ArrayHelper::getColumn($languages, 'code'),
                ]);
            ?>
            
            <br>
            <?= SeoForm::widget([
                    'model' => $model, 
                    'form' => $form, 
                    'languages' => ArrayHelper::getColumn($languages, 'code'),
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

    </div>

<?php Pjax::end(); ?>