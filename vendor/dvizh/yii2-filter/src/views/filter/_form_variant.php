<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use agapofff\gallery\widgets\Gallery;
// use backend\widgets\MultilangField;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */

if ($model->isNewRecord) {
?>
    <div class="filter-variant-form-add" style="max-width: 300px">
        <?php 
            $form = ActiveForm::begin([
                'action' => [
                    '/filter/filter-variant/create'
                ]
            ]); 
        ?>

            <?= $form
                    ->field($model, 'filter_id')
                    ->hiddenInput()
                    ->label(false); 
            ?>
            
            <div class="form-group-json">
                <input type="hidden" id="list" name="list" class="is_json">
                <ul class="nav nav-pills">
            <?php
                foreach ($languages as $key => $lang) {
            ?>
                    <li <?php if ($lang->code == Yii::$app->language) {?>class="active"<?php } ?>>
                        <a href="#list_<?= $lang->code ?>_tab" aria-controls="list_<?= $lang->code ?>_tab" role="tab" data-toggle="tab"><?= strtoupper($lang->code) ?></a>
                    </li>
            <?php
                }
            ?>
                </ul>
                <div class="tab-content">
            <?php
                foreach ($languages as $key => $lang) {
            ?>
                    <div role="tabpanel" class="tab-pane <?php if ($lang->code == Yii::$app->language) { ?>active<?php } ?>" id="list_<?= $lang->code ?>_tab">
                        <?= Html::input(
                                'text',
                                'list_'.$lang->code,
                                null,
                                [
                                    'id' => 'list_'.$lang->code,
                                    'class' => 'form-control json_field',
                                    'data' => [
                                        'field' => 'list',
                                        'lang' => $lang->code,
                                    ]
                                ]
                            )
                        ?>
                    </div>
            <?php
                }
            ?>
                </div>
            </div>
        
        <!--
            <div class="form-group field-filter-name required">
                <textarea placeholder="Каждый вариант с новой строки" required name="list" class="form-control" style="width: 400px; height: 160px;" placeholder=""></textarea>
            </div>
        -->

            <div class="form-group">
                <?= Html::submitButton(Yii::t('back', 'Добавить вариант'), [
                        'class' => 'btn btn-success'
                    ]) 
                ?>
            </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php
} else {
?>
    <div class="filter-variant-form">

        <?php 
            $form = ActiveForm::begin([
                'action' => [
                    '/filter/filter-variant/update', 
                    'id' => $model->id
                ], 
                'options' => [
                    'enctype' => 'multipart/form-data'
                ]
            ]); 
        ?>

            <?= $form
                    ->field($model, 'filter_id')
                    ->hiddenInput()
                    ->label(false); 
            ?>

            <?= $form
                    ->field($model, 'value')
                    ->textInput([
                        'placeholder' => 'Каждый вариант с новой строки'
                    ]); 
            ?>

        <?php 
            // echo Gallery::widget([
                // 'model' => $model
            // ]); 
        ?>
        
            <div class="form-group">
                <?= Html::submitButton(Yii::t('back', 'Сохранить'), [
                        'class' => 'btn btn-success'
                    ]) 
                ?>
            </div>

        <?php ActiveForm::end(); ?>

    </div>
<?php } ?>

