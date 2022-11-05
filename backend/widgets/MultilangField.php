<?php
namespace backend\widgets;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use vova07\imperavi\Widget;

class MultilangField extends \yii\base\Widget
{
    public $model = null;
    public $form = null;
    public $field = null;
    public $type = 'text';
    public $languages = [];
    
    public function init()
    {
        parent::init();
    }
    
    public function run()
    {
        if (empty($this->languages)) {
            $this->languages[] = Yii::$app->language;
        }

        $modelName = strtolower(end(explode('\\', basename(get_class($this->model)))));
        
        $li = '';
        foreach ($this->languages as $language) {
            $li .= Html::tag('li', Html::a(strtoupper($language), '#' . $this->field . '_' . $language . '_tab', [
                'aria-controls' => $this->field . '_' . $language . '_tab',
                'role' => 'tab',
                'data-toggle' => 'tab',
            ]), [
                'class' => ($language == Yii::$app->language ? 'active' : ''),
            ]);
        }
        
        $tabContent = '';
        foreach ($this->languages as $language) {
            if ($this->type == 'html' || $this->type == 'wysiwyg') {
                $input = Widget::widget([
                    'id' => $modelName . '_' . $this->field . '_' . $language,
                    'name' => $modelName . '_' . $this->field . '_' . $language,
                    'value' => json_decode($this->model->{$this->field})->{$language},
                    'settings' => [
                        'lang' => Yii::$app->language,
                        'buttonsHide' => [
                            'file',
                        ],
                        'minHeight' => 200,
                        'maxHeight' => 600,
                        'imageUpload' => Url::toRoute(['/site/image-upload']),
                        'imageDelete' => Url::toRoute(['/site/image-delete']),
                        'imageManagerJson' => Url::to(['/site/images-get']),
                        'plugins' => [
                            'fontsize',
                            'fontcolor',
                            'table',
                            'video',
                            'fullscreen',
                        ],
                        'replaceDivs' => false,
                    ],
                    'plugins' => [
                        'imagemanager' => 'vova07\imperavi\bundles\ImageManagerAsset',
                    ],
                    'options' => [
                        'class' => 'json_field',
                        'data' => [
                            'field' => $modelName . '-' . $this->field,
                            'lang' => $language,
                        ]
                    ]
                ]);
            } else if ($this->type == 'textarea') {
                $input = Html::textArea(
                    $this->field . '_' . $language, 
                    json_decode($this->model->{$this->field})->{$language}, [
                    'id' => $modelName . '_' . $this->field . '_' . $language,
                    'class' => 'form-control json_field',
                    'data' => [
                        'field' => $modelName . '-' . $this->field,
                        'lang' => $language,
                    ]
                ]);
            } else if ($this->type == 'text' || $this->type == 'input') {
                $input = Html::input(
                    'text',
                    $this->field . '_' . $language,
                    json_decode($this->model->{$this->field})->{$language},
                    [
                        'id' => $modelName . '_' . $this->field . '_' . $language,
                        'class' => 'form-control json_field',
                        'data' => [
                            'field' => $modelName . '-' . $this->field,
                            'lang' => $language,
                        ]
                    ]
                );
            }
            
            $tabContent .= Html::tag('div', $input, [
                'id' => $this->field . '_' . $language . '_tab',
                'class' => 'tab-pane' . ($language == Yii::$app->language ? ' active' : ''),
                'role' => 'tabpanel',
            ]);
        }
        
        $content = Html::tag('div', 
            $this->form
                ->field($this->model, $this->field)
                ->hiddenInput([
                    'class' => 'is_json'
                ])
            . Html::tag('ul', $li, [
                'class' => 'nav nav-pills'
            ])
            . Html::tag('div', $tabContent, [
                'class' => 'tab-content'
            ]), 
        [
            'class' => 'form-group-json',
        ]);
        
        return $content;
    }
}
