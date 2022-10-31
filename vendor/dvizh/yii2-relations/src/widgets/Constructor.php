<?php

namespace dvizh\relations\widgets;

use yii\helpers\Html;
use yii\helpers\Url;
use yii;

class Constructor extends \yii\base\Widget
{
    public $model = null;
    public $inAttribute = 'relations';
    public $view = 'constructor';
    
    public function init()
    {
        \dvizh\relations\assets\RelationsAsset::register($this->getView());
    }

    public function run()
    {
        $js = '';
        
        if($relations = $this->model->getRelations()) {
            foreach($relations->all() as $related) {
				$imgSrc = null;
				$image = $related->getImage();
				if ($image){
					$cachedImage = '/images/cache/Products/Product' . $image->itemId . '/' . $image->urlAlias . '_50x50.jpg';
					$imgSrc = file_exists(Yii::getAlias('@frontend') . '/web' . $cachedImage) ? $cachedImage : $image->getUrl('50x50');
				}
				
                $js .= 'dvizh.relations.renderRow("'.str_replace('\\', '\\\\', $related::className()).'", "'.Html::encode($related->getId()).'", "'.Html::encode(json_decode($related->getName())->{Yii::$app->language}).'", "'.$imgSrc.'");';
            }
        }
        
        $this->getView()->registerJs($js);
        
        return $this->render($this->view, ['model' => $this->model]);
    }
}
