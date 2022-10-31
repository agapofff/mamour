<?php
namespace dvizh\cart\controllers;

use dvizh\cart\models\Cart;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii;

class DefaultController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'truncate' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $elements = Yii::$app->cart->elements;

        return $this->render('index', [
            'elements' => $elements,
        ]);
    }

    public function actionTruncate()
    {
        $json = ['result' => 'undefined', 'error' => false];

        $cartModel = Yii::$app->cart;
        
        if ($cartModel->truncate()) {
            $json['result'] = 'success';
        } else {
            $json['result'] = 'fail';
            $json['error'] = $cartModel->getCart()->getErrors();
        }

        return $this->_cartJson($json);
    }

    public function actionInfo() {
        $this->enableCsrfValidation = false;
        $json = [
            'result' => 'success',
            'error' => false,
            'lang' => Yii::$app->request->get('lang')
        ];
        return $this->_cartJson($json);
    }
    
    private function _cartJson($json)
    {            
        if ($cartModel = Yii::$app->cart) {
            $lang = \common\models\Languages::find()->where([
                'code' => $json['lang']
            ])->one();

            $elementsListWidgetParams['currency'] = $lang->currency;
            $elementsListWidgetParams['lang'] = $json['lang'];

            $json['elementsHTML'] = \dvizh\cart\widgets\ElementsList::widget($elementsListWidgetParams);
            $json['count'] = $cartModel->getCount();
            $json['price'] = Yii::$app->formatter->asCurrency($cartModel->getCost(), $lang->currency);
        } else {
            $json['count'] = 0;
            $json['price'] = 0;
        }
        return Json::encode($json);
    }
}
