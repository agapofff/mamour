<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use kartik\alert\AlertBlock;
use kartik\switchinput\SwitchInput;
use backend\widgets\MultilangField;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\Advert */
/* @var $form yii\widgets\ActiveForm */

?>

<?php Pjax::begin(); ?>

    <div class="actions-form">
    
        <?= AlertBlock::widget([
                'type' => 'growl',
                'useSessionFlash' => true,
                'delay' => 1,
            ]);
        ?>

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
            
            <?= MultilangField::widget([
                    'model' => $model,
                    'form' => $form,
                    'field' => 'name',
                    'languages' => $languages,
                ]);
            ?>

            <?= $form
                    ->field($model, 'url')
                    ->textInput()
                    ->hint(Yii::t('back', 'Относительные ссылки, без доменного имени, ничинаются со слеша, например: /catalog'))
            ?>
            
            <?= $form
                    ->field($model, 'alias')
                    ->textInput() 
            ?>
            
            <?= $form
                ->field($model, 'target_blank')
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
                    ->field($model, 'parent')
                    ->hiddenInput([
                        'parent' => $model->parent
                    ])
                    ->label(false) 
            ?>
            
            <?= $form
                    ->field($model, 'sort')
                    ->hiddenInput()
                    ->label(false)
            ?>

            <?= Html::input('hidden', 'saveAndExit', 0) ?>
            
            <p>
                <a href="#linkbuilder" data-toggle="modal">
                    <?= Yii::t('back', 'Построитель ссылок на выборки товаров') ?>
                </a>
            </p>

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


<?php
    Modal::begin([
        'header' => Html::tag('h4', 'Построить ссылку'),
        'id' => 'linkbuilder',
    ]);
?>
        <div class="form-horizontal">
            <div class="form-group">
                <label class="col-sm-3 control-label">
                    <?= Yii::t('back', 'Тип ссылки') ?>
                </label>
                <div class="col-sm-9">
                    <?= Select2::widget([
                            'name' => 'linktype',
                            'data' => [
                                'catalog' => Yii::t('back', 'Список товаров'),
                                'info' => Yii::t('back', 'Список страниц'),
                                'page' => Yii::t('back', 'Страница'),
                                'blog' => Yii::t('back', 'Блог'),
                            ],
                            'options' => [
                                'id' => 'linktype',
                                'onchange' => 'setLinktype(this.value)',
                            ],
                            'pluginOptions' => [
                                'allowClear' => false,
                            ],
                        ]);
                    ?>
                </div>
            </div>
        </div>
        <hr>
        <form class="linkbuilder form-horizontal active" data-type="catalog">
    <?php
        foreach ($filters as $filter) {
            if ($filter->active) {
    ?>
                <div class="form-group">
                    <label class="col-sm-3 control-label">
                        <?= $filter->name ?>
                    </label>
                    <div class="col-sm-9">
                        <?= Select2::widget([
                                'name' => 'filter[' . $filter->id . ']',
                                'data' => ArrayHelper::map($filter->variants, 'latin_value', function ($variant) {
                                    return $variant->active ? json_decode($variant->value)->{Yii::$app->language} : false;
                                }),
                                'options' => [
                                    'placeholder' => Yii::t('back', 'Выберите'),
                                    'multiple' => true,
                                    'data-name' => 'filter[' . $filter->id . '][]',
                                    'data-type' => 0,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                            ]);
                        ?>
                    </div>
                </div>
    <?php
            }
        }
    ?>
            <div class="text-center">
                <?= Html::submitButton(Yii::t('back', 'Создать ссылку'), [
                        'class' => 'btn btn-success',
                    ]) 
                ?>
            </div>
        </form>
<?php 
    Modal::end(); 
?>

<?php
    $this->registerJs("
        getCategoryData = function (id) {
            $.get('/treemenu/default/get-category-data', {
                id: id
            }, function (response) {
                var category = JSON.parse(response),
                    categoryNames = JSON.parse(category.name);
                $('#category_path').val(category.path);
                $.each(categoryNames, function (lang, name) {
                    $('#treemenu_name_' + lang).val(name).trigger('input');
                });
            });
        }
        
        setLinktype = function (type) {
            $('form.linkbuilder').removeClass('active').addClass('hidden');
            $('form.linkbuilder[data-type=\"' + type + '\"]').removeClass('hidden').addClass('active');
        }
        
        $('form.linkbuilder').on('submit', function (e) {
            e.preventDefault();
            
            var type = $(this).data('type'),
                values = [];
            
            values.push(type);
            if (type === 'catalog') {
                $(this).find('select').each(function () {
                    $.each($(this).val(), function (key, value) {
                        values.push(value);
                    });
                });
            }
            
            $('#treemenu-url').val('/' + decodeURIComponent(values.join('/')));
            $('#linkbuilder').modal('hide');
        });
    ", View::POS_READY);
?>