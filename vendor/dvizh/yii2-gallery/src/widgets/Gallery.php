<?php
namespace dvizh\gallery\widgets;

use yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\Draggable;
use kartik\file\FileInput;

class Gallery extends \yii\base\Widget
{
    public $model = null;
    public $previewSize = '140x140';
    public $fileInputPluginLoading = true;
    public $fileInputPluginOptions = [];
    public $label = null;
 
    public function init()
    {
        if(!$this->label) {
            $this->label = Yii::t('back', 'Изображения');
        }
        
        $view = $this->getView();
        $view->on($view::EVENT_END_BODY, function($event) {
            echo $this->render('modal');
        });

        \dvizh\gallery\assets\GalleryAsset::register($this->getView());
    }

    public function run()
    {
        $model = $this->model;
        $params = [];
        $img = '';
        $label = '<label class="control-label">'. $this->label .'</label>';
        $cart = '';
        $script = '';
        
        if($model->getGalleryMode() == 'single') {
            if($model->hasImage()) {
                $image = $this->model->getImage();
                $img = $this->getImagePreview($image);
                $params = $this->getParams($image->id);

            }

            return $label . '<br style="clear: both;" />' . Html::tag('div', $img, $params) . '<br style="clear: both;" />' . $this->getFileInput();
        }

        if (  $this->model->hasImage() ){
            $elements = $this->model->getImages();
            $cart = Html::ul(
                $elements,
                [
                    'item' => function($item) {
                        return $this->row($item);
                    },
                    'class' => 'dvizh-gallery row',
                    'style' => 'padding: 0; margin-bottom: 0;'
                ]);
                
            $script = Html::script("
                        document.addEventListener('DOMContentLoaded', function(event){
                            $('.dvizh-gallery').sortable({
                                containment: '.content-wrapper',
                                // placeholder: 'dvizh-gallery-sortable-placeholder col-xs-6 col-md-4 col-lg-3',
                                helper: 'clone',
                                opacity: 0.5,
                                revert: 300,
                                update: dvizh.gallery.setSort,
                                scroll: false,
                                cursor: 'move',
                                start: function(event, ui) {
                                    ui.placeholder.html(ui.item.html());
                                }
                            });
                        });
                    ");
        }

        return Html::tag( 'div', $label . $cart . $this->getFileInput() . $script);
    }

    private function row($image)
    {
        if($image instanceof \dvizh\gallery\models\PlaceHolder) {
            return '';
        }

        $class = ' col-xs-6 col-md-4 col-lg-3 dvizh-gallery-row-';

        if($image->isMain) {
            $class .= ' main';
        }

        $liParams = $this->getParams($image->id);
        $liParams['class'] .=  $class;

        return Html::tag('li', $this->getImagePreview($image), $liParams);
    }

    private function getFileInput()
    {
        return FileInput::widget([
            'name' => $this->model->getInputName() . '[]',
            'options' => [
                'accept' => 'image/*',
                'multiple' => $this->model->getGalleryMode() == 'gallery',
            ],
            'pluginOptions' => $this->fileInputPluginOptions,
            'pluginLoading' => $this->fileInputPluginLoading
        ]);
    }

    private function getParams($id)
    {
        $model = $this->model;

        return  [
            'class' => 'dvizh-gallery-item',
            'data-model' => $model::className(),
            'data-id' => $model->id,
            'data-image' => $id
        ];
    }

    private function getImagePreview($image)
    {
        $size = (explode('x', $this->previewSize));

        $delete = Html::a(
            Html::tag('span', '', [
                'class' => 'fa fa-trash fa-xl'
            ]),
            '#',
            [
                'data-action' => Url::toRoute([
                    '/gallery/default/delete',
                    'id' => $image->id
                ]),
                'data-confirm' => Yii::t('back', 'Вы уверены, что хотите удалить этот элемент?'),
                'class' => 'btn btn-danger delete'
            ]);
        
        $write = Html::a(
            Html::tag('span', '', [
                'class' => 'fa fa-pencil fa-xl'
            ]),
            '#',
            [
                'data-action' => Url::toRoute([
                    '/gallery/default/modal',
                    'id' => $image->id
                ]), 
                'class' => 'btn btn-primary write'
            ]);
        
        $img = Html::img($image->getUrl($this->previewSize), [
            'data-action' => Url::toRoute([
                '/gallery/default/setmain',
                'id' => $image->id
            ]),
            'width' => $size[0],
            'height' => $size[1],
            'class' => 'thumb img-responsive'
        ]);
        
        $a = Html::a($img, $image->getUrl(), [
            'class' => 'thumbnail'
        ]);

        return $delete.$write.$a;
    }
}
