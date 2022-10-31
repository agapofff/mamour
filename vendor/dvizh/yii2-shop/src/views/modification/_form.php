<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use dvizh\shop\models\PriceType;
use dvizh\shop\models\Price;
use kartik\switchinput\SwitchInput;
use kartik\alert\AlertBlock;
use kartik\select2\Select2;
use backend\models\Stores;

$priceTypes = PriceType::find()->all();
$priceModel = new Price;

$stores = Stores::find()->all();

if (!$model->id){
    $model->available = 1;
}

?>

<?php Pjax::begin(); ?>

    <div class="product-add-modification-form">

        <?= AlertBlock::widget([
                'type' => 'growl',
                'useSessionFlash' => true,
                'delay' => 1,
            ]);
        ?>

        <?php $form = ActiveForm::begin([
                'options' => [
                    'enctype' => 'multipart/form-data',
                    'onsubmit' => 'window.parent.showLoader()',
                ]
            ]);
        ?>
        
            <div class="row">
                <div class="col-xs-6">
                    <?= $form
                            ->field($model, 'synchro')
                            ->widget(SwitchInput::classname(), [
                                'pluginOptions' => [
                                    'onText' => Yii::t('back', 'Да'),
                                    'offText' => Yii::t('back', 'Нет'),
                                    'onColor' => 'success',
                                    'offColor' => 'danger',
                                ],
                            ]);
                    ?>
                </div>
                <div class="col-xs-6">
                    <?= $form
                            ->field($model, 'available')
                            ->widget(SwitchInput::classname(), [
                                'pluginOptions' => [
                                    'onText' => Yii::t('back', 'Да'),
                                    'offText' => Yii::t('back', 'Нет'),
                                    'onColor' => 'success',
                                    'offColor' => 'danger',
                                ],
                            ]);
                    ?>
                </div>
            </div>

            <?= $form
                    ->field($model, 'store_id')
                    ->dropdownList(ArrayHelper::map($stores, 'id', 'name'))
            ?>

        <?php 
            if ($filters = $productModel->getOptions()) { 
        ?>
                <div class="filters">
                    <?php foreach ($filters as $filter) { ?>
                        <?php if ($variants = $filter->variants) { ?>
                            <div class="form-group required">
                                <label for="filterValue<?= $filter->id; ?>" class="control-label"><?= $filter->name; ?></label>
                                <select id="filterValue<?= $filter->id; ?>" name="filterValue[<?= $filter->id; ?>]" class="form-control">
                                        <option value="">-</option>
                                    <?php foreach ($variants as $variant) { ?>
                                        <option <?php if (in_array($variant->id, $model->filtervariants)) echo ' selected="selected"'; ?>
                                            value="<?= $variant->id; ?>"><?= json_decode($variant->value)->{Yii::$app->language} ?></option>
                                    <?php } ?>
                                </select>
                                <div class="help-block"><i><?= $filter->description; ?></i></div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
        <?php 
            } else { 
        ?>
                <p>Значения задаются в <?= Html::a('фильтрах', ['/filter/filter/index'], ['target' => '_blank']); ?>. В
                    настоящий момент к категории продукта не привязано ни одного фильтра.</p>
        <?php 
            } 
        ?>
            
            
            <?= $form
                    ->field($model, 'code')
                    ->textInput([
                        'type' => 'hidden'
                    ])
                    ->label(false)
            ?>
            
            <?= $form
                    ->field($model, 'sku')
                    ->textInput([
                        'maxlength' => true
                    ])
            ?>

            <?= $form
                    ->field($model, 'product_id')
                    ->textInput([
                        'type' => 'hidden'
                    ])
                    ->label(false)
            ?>
            
            <?= $form
                    ->field($model, 'name')
                    ->textInput([
                        'type' => 'hidden'
                    ])
                    ->label(false)
            ?>
            
            <?= $form
                    ->field($model, 'barcode')
                    ->textInput([
                        'type' => 'hidden'
                    ])
                    ->label(false)
            ?>
            
            <?= $form
                    ->field($model, 'amount')
                    ->textInput([
                        'type' => 'number'
                    ])
            ?>
            
            <?= $form
                    ->field($model, 'sort')
                    ->textInput([
                        'type' => 'hidden'
                    ])
                    ->label(false)
            ?>
            
            <div class="form-group">
                <?php 
                    if (isset($priceTypes) && !empty($priceTypes)) {
                        $i = 1;
                        foreach ($priceTypes as $priceType) { 
                ?>
                            <div class="col-12">
                                <?= $form
                                        ->field($priceModel, "[{$priceType->id}]price")
                                        ->textInput([
                                            'type' => 'number'
                                        ])
                                        ->label($priceType->name) 
                                ?>
                            </div>
                <?php 
                            $i++;
                        } 
                    } 
                ?>
            </div>

            <div class="container row form-group hidden">
                <?= \agapofff\gallery\widgets\Gallery::widget([
                        'model' => $model,
                        'previewSize' => '150x150',
                        'fileInputPluginLoading' => true,
                        'fileInputPluginOptions' => []
                    ])
                ?>
            </div>

            <br>
            <div class="form-group text-center">
                <?= Html::submitButton(Html::tag('span', '', [
                    'class' => 'glyphicon glyphicon-floppy-saved'
                ]) . '&nbsp;' . Yii::t('back', 'Сохранить'), [
                    'class' => 'btn btn-success btn-lg'
                ]) ?>
            </div>

        <?php ActiveForm::end(); ?>

    </div>

<?php Pjax::end() ?>

<?php
    $this->registerJS("
        $('#filterValue2').change(function(){
            $('#modification-name').val($(this).find('option:selected').text());
        });
    ",
    \yii\web\View::POS_READY,
    'change_options');
?>
