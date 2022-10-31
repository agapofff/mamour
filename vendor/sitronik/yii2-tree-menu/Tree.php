<?php

namespace sitronik\treemenu;

use Yii;
use \yii\base\Widget;
use sitronik\treemenu\models\TreeMenu;
use yii\helpers\Html;
use yii\helpers\Url;

class Tree extends Widget
{
    public $isAdmin = false;

    public function init()
    {
        parent::init();
        $this->registerAssets();
    }

    public function run()
    {
        return $this->renderWidget();
    }

    public function registerAssets() {
        $view = $this->getView();
        TreeMenuAsset::register($view);
    }

    public $mainTemplate = '{tree-menu}<br>{button}';


    public function renderTree() {
        $content = $this->getContent();
        if (empty($content)) {
            $out = 'Tree menu is empty. Please add root item. ';
            if (!$this->isAdmin)
                $out .= Html::a('Меню', ['/treemenu']);
            return $out;
        }

        return $this->recurseTree($content, 0);

    }

    public function recurseTree($content, $parent_id) 
    {
        $out = '';
        if (isset($content[$parent_id])) {
            if ($parent_id == 0) {
                $out .= Html::beginTag('ul', [
                    'class' => 'nav nav-list treemenu sortable',
                    'data-sort' => Url::to(['/treemenu/default/sort'])
                ]) . "\n";
            } else {
                $out .= Html::beginTag('ul', [
                    'class' => 'nav nav-list tree sortable',
                    'data-sort' => Url::to(['/treemenu/default/sort'])
                ]) . "\n";
            }

            $parents_count = count($content[$parent_id]);
            $count = 0;
            foreach ($content[$parent_id] as $a) {
                $count++;
                $url = Html::a(json_decode($a->name)->{Yii::$app->language}, [
                    '/treemenu/default/update',
                    'id' => $a->id, 
                ], [
                    'data-pjax' => 0
                ]);
                if ($this->isAdmin) {
                    $adminButtons = $this->adminButtons($a);
                   $url .= $adminButtons;
                } else {
                    $adminButtons = '';
                }
                
                $sort = Html::tag('sort', '', [
                    'class' => 'fa fa-sort text-info sort-handler pull-left',
                    'style' => 'padding: 5px 10px'
                ]);
                
                $out .= '<li data-key="' . $a->id . '">' . $sort . Html::tag('big', $url);

                // if ($a->parent == 1) {
                    // $out .= '<li data-key="' . $a->id . '">' . $sort . Html::tag('big', $url);
                // } else {
                    // $out .= '<li data-key="' . $a->id . '">' . $sort   . '<big>' . json_decode($a->name)->{Yii::$app->language}. $adminButtons. '</big>';
                // }

                $out .= $this->recurseTree($content, $a->id);
                $out .= '</li>';

                // if ($a->parent_id == 0 && $parents_count != $count)
                    // $out .= '<li class="nav-divider"></li>';
            }

          $out .= '</ul>';

        }

        return $out;
    }

    public function adminButtons($a) 
    {
        $active = Html::a('', [
                '/treemenu/default/active',
                'id' => $a->id, 
            ], [
                'class' => 'glyphicon glyphicon-' . ($a->active ? 'ok text-success' : 'remove text-danger') . ' pull-right',
                'title' => Yii::t('back', 'Активно'),
                'style' => '
                    margin-right: 5px;
                    padding: 4px 5px;
                ',
            ]);
            
        $insert = Html::a('', [
                '/treemenu/default/insert',
                'id' => $a->id, 
            ], [
                'class' => 'glyphicon glyphicon-plus btn btn-success btn-xs pull-right',
                'title' => Yii::t('back', 'Добавить'),
                'data-pjax' => 0,
                'style' => 'margin-right: 5px;',
            ]);
            
        $update = Html::a('', [
                '/treemenu/default/update',
                'id' => $a->id, 
            ], [
                'class' => 'glyphicon glyphicon-pencil btn btn-primary btn-xs pull-right',
                'title' => Yii::t('back', 'Изменить'),
                'data-pjax' => 0,
                'style' => 'margin-right: 5px;',
            ]);
            
        $copy = Html::a('', [
                '/treemenu/default/copy',
                'id' => $a->id, 
            ], [
                'class' => 'glyphicon glyphicon-duplicate btn btn-info btn-xs pull-right',
                'title' => Yii::t('back', 'Скопировать'),
                'data-pjax' => 0,
                'style' => 'margin-right: 5px;',
            ]);
            
        $delete = Html::a('', [
                '/treemenu/default/delete',
                'id' => $a->id, 
            ], [
                'class' => 'glyphicon glyphicon-trash btn btn-danger btn-xs pull-right',
                'title' => Yii::t('back', 'Удалить'),
                'data' => [
                    'confirm' => Yii::t('back', 'Вы уверены, что хотите удалить этот элемент?'),
                    'method' => 'post',
                ],
                'style' => 'margin-right: 5px;',
            ]);
            
        return $delete . $update . $copy . $insert . $active;
    }

    public function getContent() 
    {
        $model = TreeMenu::find()
            ->orderBy([
                'sort' => SORT_ASC
            ])
            ->all();
            
        $arr = array();

        foreach ($model as $content) {
            $arr[$content->parent_id][$content->id] = $content;
        }

        return $arr;
    }

    public function renderButton() 
    {
        if ($this->isAdmin) {
            $button = Html::a('', [
                '/treemenu/default/insert',
                'id' => 'root',
            ], [
                'class' => 'btn btn-success glyphicon glyphicon-plus',
                'data-pjax' => 0,
            ]);
        } else {
            $button = '';
        }

        return $button;
    }

    public function renderWidget()
    {
        $content = strtr(
            $this->mainTemplate, [
                '{tree-menu}' => $this->renderTree(),
                '{button}' =>  $this->renderButton()
            ]
        );
        return $content;
    }

}
