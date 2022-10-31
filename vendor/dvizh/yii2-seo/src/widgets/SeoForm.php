<?php
namespace dvizh\seo\widgets;

use dvizh\seo\models\Seo;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use Yii;
use common\models\Languages;
use backend\widgets\MultilangField;

class SeoForm extends \yii\base\Widget
{
    public $model = null;
    public $modelName = null;
    public $form = null;
    public $title = 'SEO';
    public $toggle = true;
    public $modal = true;
    public $languages = [];
    
    public function init()
    {
        if (empty($this->modelName)) {
            $this->modelName = $this->model->getSeoClassName();
        }
        
        \dvizh\seo\assets\FormAsset::register($this->getView());
        
        parent::init();
    }

    public function run()
    {
        if (!$this->model->isNewRecord) {
            if (($this->model = Seo::findOne(['item_id' => $this->model->id, 'modelName' => $this->modelName])) === null) {
                $this->model = new Seo;
            }
        } else {
            $this->model = new Seo;
        }
        
        if (empty($this->languages)) {
            $this->languages[] = Yii::$app->language;
        }

        $content = '';

        $content .= $this->form->field($this->model, 'modelName')->hiddenInput(['value' => $this->modelName])->label(false);
        
        $fields = [
            'title',
            'description',
            'keywords',
            // 'h1',
            // 'text',
            // 'meta_index',
            'redirect_301',
        ];
        
        foreach ($fields as $field) {
            $content .= MultilangField::widget([
                'model' => $this->model,
                'form' => $this->form,
                'field' => $field,
                'languages' => $this->languages,
                'type' => (in_array($field, ['description', 'keywords', 'text']) ? 'textarea' : 'input'),
            ]);
        }
        
        if ($this->toggle) {
            if ($this->modal) {
                $heading = Html::tag('p', Html::a($this->title, '#seo-body', [
                    'data-toggle' => 'modal'
                ]));
                
                $body = Html::tag('div', Html::tag('div', Html::tag('div', Html::tag('div', Html::button(Html::tag('span', '&times;', [
                    'aria-hidden' => 'true',
                ]), [
                    'type' => 'button',
                    'class' => 'close',
                    'data-dismiss' => 'modal',
                    'aria-label' => 'Close',
                ]) . Html::tag('h5', 'SEO', [
                    'id' => 'seoModelLabel',
                    'class' => 'model-title',
                ]), [
                    'class' => 'modal-header',
                ]) . Html::tag('div', $content, [
                    'class' => 'modal-body',
                ]) . Html::tag('div', Html::tag('div', Html::submitButton(Yii::t('back', 'Сохранить'), [
                    'class' => 'btn btn-success btn-lg',
                ]), [
                    'class' => 'text-center',
                ]), [
                    'class' => 'modal-footer',
                ]), [
                    'class' => 'modal-content',
                ]), [
                    'class' => 'modal-dialog',
                ]), [
                    'id' => 'seo-body',
                    'class' => 'modal fade',
                    'tabindex' => '-1',
                    'aria-labelledby' => 'seoModalLabel',
                    'aria-hidden' => 'true',
                ]);
            } else {
                $heading = Html::tag('div', Html::a($this->title, ['#seo'], [
                    'data-toggle' => 'collapse'
                ]));
                $body = Html::tag('div', $content, [
                    'id' => 'seo',
                    'class' => 'collapse',
                ]);
            }
        } else {
            $heading = Html::tag('div', Html::tag('label', $this->title));
            $body = Html::tag('div', $content);
        }
        
        $view = Html::tag('div', $heading . $body, [
            'class' => 'dvizh-seo'
        ]);
        
        return $view;
    }
}
