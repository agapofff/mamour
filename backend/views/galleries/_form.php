<?php
use yii\web\View;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use kartik\alert\AlertBlock;
use kartik\switchinput\SwitchInput;
use agapofff\gallery\widgets\Gallery;
use dvizh\seo\widgets\SeoForm;
use backend\widgets\MultilangField;

/* @var $this yii\web\View */
/* @var $model backend\models\Galleries */
/* @var $form yii\widgets\ActiveForm */
?>

<?php Pjax::begin(); ?>

    <div class="galleries-form">
    
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
                    'languages' => $languages,
                ]);
            ?>
            
            <?= $form
                    ->field($model, 'slug')
                    ->textInput([
                        'maxlength' => true
                    ])
            ?>
            
            <div class="form-group">
                <?= Gallery::widget([
                        'model' => $model,
                        'label' => Yii::t('back', 'Изображения'),
                        'previewSize' => '200x200',
                        'fileInputPluginOptions' => [
                            'showPreview' => false,
                        ],
                        'containerClass' => 'row',
                        'elementClass' => 'col-xs-6 col-sm-4 col-md-3 col-lg-4',
                        'deleteButtonText' => Html::tag('i', '', ['class' => 'fa fa-trash']),
                        'editButtonText' => Html::tag('i', '', ['class' => 'fa fa-edit']),
                    ]);
                ?>
            </div>  
            
            <?= $form
                    ->field($model, 'video')
                    ->textInput()
                    ->hint(Yii::t('back', 'Ссылка на видео с Youtube'))
            ?>
        <?php 
            if ($model->video) {
                parse_str(parse_url($model->video, PHP_URL_QUERY), $videoVars);
                $this->registerJs("
                    $('.video').append('<iframe src=\"https://www.youtube.com/embed/" . $videoVars['v'] . "?controls=0&showinfo=0&rel=0&autoplay=0&loop=1&mute=1&playlist=" . $videoVars['v'] . "&modestbranding=1&iv_load_policy=3&autohide=1&fs=0&cc_load_policy=0&disablekb=0&origin=" . Url::home(true)  . "\" frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen\"></iframe>');
                ", View::POS_LOAD);
        ?>
                <div style="max-width: 400px">
                    <div class="video"></div>
                </div>
                <br>
        <?php
            }
        ?>

            <?= MultilangField::widget([
                    'model' => $model,
                    'form' => $form,
                    'field' => 'description',
                    'languages' => $languages,
                ]);
            ?>

            <?= MultilangField::widget([
                    'model' => $model,
                    'form' => $form,
                    'field' => 'text',
                    'type' => 'html',
                    'languages' => $languages,
                ]);
            ?>
            
            <br>
            <?= SeoForm::widget([
                    'model' => $model, 
                    'form' => $form, 
                    'languages' => $languages,
                ]); 
            ?>
            
            <?= $form
                    ->field($model, 'sort')
                    ->hiddenInput()
                    ->label(false)
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
