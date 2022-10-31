<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use dvizh\order\models\Order;
use dvizh\order\models\Element;
use dvizh\order\models\ShippingType;
use dvizh\order\models\Field;
use dvizh\order\models\FieldValue;
use dvizh\shop\models\Modification;
use common\models\Languages;

class OrdersController extends \yii\web\Controller
{
    
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/login']);
        }
        
        $orders = Order::find()
            ->where([
                'user_id' => Yii::$app->user->id,
                'is_deleted' => 0,
            ])
            ->orderBy([
                'id' => SORT_DESC,
            ])
            ->all();
            
        $shippingTypes = ShippingType::find()->all();
        
        return $this->render('index', [
            'orders' => $orders,
            'shippingTypes' => $shippingTypes,
        ]);
    }
    
    
    public function actionView($id)
    {
        $order = Order::find()
            ->where('id = :id', [
                ':id' => $id
            ])
            ->one();
        
        if ($order) {
            if ($order->user_id != Yii::$app->user->id) {
                throw new ForbiddenHttpException(Yii::t('front', 'У Вас нет доступа к этой странице'));
            }
            
            $allowedFields = [5, 7, 8, 10, 11, 12];
            
            $shippingTypes = ShippingType::find()->all();
            
            $fieldValues = FieldValue::find()
                ->where([
                    'order_id' => $order->id
                ])
                ->andWhere([
                    'in', 'field_id', $allowedFields
                ])
                ->all();
            
            $fields = Field::find()
                ->where([
                    'in', 'id', $allowedFields
                ])
                ->orderBy([
                    'order' => SORT_ASC
                ])
                ->all();
                
            $elements = Element::findAll([
                'order_id' => $order->id
            ]);
            
            $sizes = [];
            $lang = Yii::$app->language;
            
            if ($elements) {
                foreach ($elements as $key => $element) {
                    $modification = Modification::findOne([
                        'sku' => $element->description
                    ]);
                    if ($modification) {
                        $sizes[$key] = explode('|', $modification->name)[0];
                        $lang = $modification->lang;
                    }
                }
            }
            
            $currency = Languages::findOne([
                'code' => $lang
            ])->currency;
            
            return $this->render('view', [
                'order' => $order,
                'shippingTypes' => $shippingTypes,
                'fieldValues' => $fieldValues,
                'fields' => $fields,
                'elements' => $elements,
                'sizes' => $sizes,
                'currency' => $currency,
                'lang' => $lang,
            ]);
            
        } else {
            throw new NotFoundHttpException(Yii::t('front', 'Не найдено'));
        }
    }
    
}
