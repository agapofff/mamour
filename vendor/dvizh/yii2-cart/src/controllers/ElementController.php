<?php
namespace dvizh\cart\controllers;

use yii\helpers\Json;
use yii\filters\VerbFilter;
use yii;

class ElementController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    // 'create' => ['post'],
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionDelete()
    {
        $json = ['result' => 'undefined', 'error' => false];
        $elementId = Yii::$app->request->post('elementId');

        $cart = Yii::$app->cart;

        $elementModel = $cart->getElementById($elementId);

        if($cart->deleteElement($elementModel)) {
            $json['result'] = 'success';
        }
        else {
            $json['result'] = 'fail';
        }
        
        $json['lang'] = Yii::$app->request->post('lang');

        return $this->_cartJson($json);
    }

    public function actionCreate()
    {
        $json = ['result' => 'undefined', 'error' => false];

        $cart = Yii::$app->cart;

        $postData = Yii::$app->request->post();

        $model = $postData['CartElement']['model'];
        
        if ($model)
        {
            $productModel = new $model();
            $productModel = $productModel::findOne($postData['CartElement']['item_id']);

            $options = [];
            if(isset($postData['CartElement']['options'])) {
                $options = $postData['CartElement']['options'];
            }
            
            $comment = $postData['CartElement']['comment'];

            if($postData['CartElement']['price'] && $postData['CartElement']['price'] != 'false') {
                $elementModel = $cart->putWithPrice($productModel, $postData['CartElement']['price'], $postData['CartElement']['count'], $options, $comment);
            } else {
                $elementModel = $cart->put($productModel, $postData['CartElement']['count'], $options, $comment);
            }

            $json['elementId'] = $elementModel->getId();
            $json['result'] = 'success';
        } else {
            $json['result'] = 'fail';
            $json['error'] = 'empty model';
        }
        
        $json['lang'] = $postData['lang'];

        return $this->_cartJson($json);
    }

    public function actionUpdate()
    {
        $json = ['result' => 'undefined', 'error' => false];

        $cart = Yii::$app->cart;
        
        $postData = Yii::$app->request->post();

        $elementModel = $cart->getElementById($postData['CartElement']['id']);
        
        if(isset($postData['CartElement']['count'])) {
            $elementModel->setCount($postData['CartElement']['count'], true);
        }
        
        if(isset($postData['CartElement']['options'])) {
            $elementModel->setOptions($postData['CartElement']['options'], true);
        }
        
        $json['elementId'] = $elementModel->getId();
        $json['result'] = 'success';
        
        $json['lang'] = $postData['lang'];

        return $this->_cartJson($json);
    }

    private function _cartJson($json)
    {
        if ($cartModel = Yii::$app->cart) {
            
            if(!$elementsListWidgetParams = Yii::$app->request->post('elementsListWidgetParams')) {
                $elementsListWidgetParams = [];
            }
            
            $lang = \common\models\Languages::find()->where([
                'code' => $json['lang']
            ])->one();

            $elementsListWidgetParams['currency'] = $lang->currency;
            $elementsListWidgetParams['lang'] = $json['lang'];

            $json['elementsHTML'] = \dvizh\cart\widgets\ElementsList::widget($elementsListWidgetParams);
            $json['count'] = $cartModel->getCount();
            $json['clear_price'] = $cartModel->getCost(false);
            
            $json['price'] = Yii::$app->formatter->asCurrency($cartModel->getCost(), $lang->currency);
        } else {
            $json['count'] = 0;
            $json['price'] = 0;
            $json['clear_price'] = 0;
        }
        return Json::encode($json);
    }

}
