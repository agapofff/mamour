<?php

namespace frontend\controllers;

use Yii;
use common\models\Stores;
use common\models\Languages;
use common\models\Countries;
use dvizh\shop\models\Product;
use dvizh\shop\models\Category;
use dvizh\order\models\Order;
use dvizh\filter\models\FilterVariant;
use dektrium\user\models\User;
use dektrium\user\models\Profile;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use linslin\yii2\curl;

class CheckoutController extends \yii\web\Controller
{
    
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    
    public function actionSuccess()
    {
        Yii::$app->cart->truncate();
        
        Yii::$app->session->setFlash('success', Yii::t('front', 'Ваш заказ успешно оформлен') . '. ' . Yii::t('front', 'Мы свяжемся с Вами в ближайшее время'));
        
        return $this->render('success');
    }
    
    
    public function actionError()
    {
        Yii::$app->session->setFlash('error', Yii::t('front', 'Произошла ошибка! Пожалуйста, попробуйте еще раз чуть позже'));
        return $this->redirect(['/checkout']);
    }
    
    
    public function actionPay($id)
    {
        $order = Order::findOne($id);
        
        return $this->render('pay', [
            'order' => $order
        ]);
    }
    
    
    public function actionGetProducts()
    {
        $elements = Yii::$app->cart->elements;
        $products = [];
        
        foreach ($elements as $element) {
            // убрать подарочный товар из списка
            if (Yii::$app->params['gift']) {
                if ($element->item_id == Yii::$app->params['gift']['product_id']) {
                    continue;
                }
            }
            
            $products[] = [
                'goods' => $element->getComment(),
                'quantity' => $element->getCount()
            ];
        }
        return json_encode($products);
    }
    
    
    public function actionGetCountries($store_id)
    {        
        $countries = Countries::find()
            ->where([
                'active' => 1
            ])
            ->all();
            
        return ArrayHelper::map($countries, 'id', function ($country) {
            return json_decode($country->name)->{Yii::$app->language};
        });
    }
    
    
    
}