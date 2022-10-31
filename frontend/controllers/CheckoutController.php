<?php

namespace frontend\controllers;

use Yii;
use common\models\Stores;
use common\models\Languages;
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
        $currency = Languages::findOne([
            'code' => Yii::$app->language
        ])->currency;
        
        return $this->render('index', [
            'currency' => $currency,
        ]);
    }
    
    
    public function actionSuccess()
    {
        $products = [];
        $sum = 0;
        foreach(Yii::$app->cart->elements as $element) {
            $products[] = [
                'id' => $element->getComment(),
                'name' => json_decode($element->getName())->{Yii::$app->language},
                'quantity' => $element->getCount(),
                'price' => round($element->getPrice()),
                'variant' => FilterVariant::findOne([
                    'id' => $element->getOptions()[1],
                    'filter_id' => 1
                ])->value
            ];
            $sum += (int)$element->getCount() * (float)$element->getPrice();
        }
        
        Yii::$app->cart->truncate();
        
        Yii::$app->session->setFlash('success', Yii::t('front', 'Ваш заказ успешно оформлен') . '. ' . Yii::t('front', 'Мы свяжемся с Вами в ближайшее время'));
        
        return $this->render('success', [
            'currency' => Yii::$app->params['currency'],
            'products' => $products,
            'sum' => round($sum),
        ]);
    }
    
    
    public function actionError()
    {
        Yii::$app->session->setFlash('error', Yii::t('front', 'Произошла ошибка! Пожалуйста, попробуйте еще раз чуть позже'));
        return $this->redirect(['/checkout']);
    }
    
    
    public function actionPay($id)
    {
        $order = Order::find()
            ->where('id = :id', [
                ':id' => $id
            ])
            ->one();
            
        $log_response = $order->getField(3);
        $paymentUrl = json_decode($log_response)->payment_url;
        return $this->redirect($paymentUrl);
    }
    
    
    public function actionGetLangId()
    {
        $langId = 1;
        $langsJson = Yii::$app->runAction('curl', [
            'url' => 'https://api.sessia.com/api/language',
            'cache' => true
        ]);
        $langs = json_decode($langsJson);
        
        foreach ($langs as $lang) {
            if ($lang->iso_code == Yii::$app->language) {
                $langId = $lang->id;
                break;
            }
        }
        return $langId;
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
        $countries = Yii::$app->runAction('curl', [
            'url' => 'https://api.sessia.com/api/market/delivery-countries/' . $store_id,
            'cache' => true
        ]);
        return $countries ?: false;
    }
    
    
    public function actionGetCities($country_id, $lang = null, $q = null)
    {
        $language = $lang ?: Yii::$app->language;
        $citiesJson = Yii::$app->runAction('curl', [
            'url' => 'https://www.sessia.com/api/directory/cities/' . $country_id,
            'params' => json_encode([
                '_format' => 'json',
                'limit' => 10,
                'offset' => 0,
                'lang' => $lang ?: Yii::$app->language,
                'q' => $q
            ]),
            'cache' => true
        ]);

        if ($citiesJson) {
            $citiesList = ['results' => []];
            $cities = json_decode($citiesJson);
            foreach ($cities as $city) {
                $citiesList['results'][] = [
                    'id' => $city->id,
                    'text' => $city->ru_name
                ];
            }
            return json_encode($citiesList);
        }
        return false;
    }
    
    
    public function actionGetDelivery($country_id, $city_id, $type = null, $shipping_id = null, $q = null)
    {
        $total = 0;
        $products = [];
        $elements = Yii::$app->cart->elements;

        if ($elements) {
            foreach ($elements as $element) {
                $products[] = [
                    'goods' => $element->getComment(),
                    'quantity' => $element->getCount()
                ];
                $total += ($element->getCount() * $element->getPrice());
            }

            $deliveryJson = Yii::$app->runAction('curl', [
                'url' => 'https://www.sessia.com/api/market/delivery-cost',
                'post' => true,
                'params' => json_encode([
                    'country' => $country_id,
                    'city' => $city_id,
                    'products' => $products,
                ]),
                'cache' => true,
            ]);

            if ($deliveryJson) {
                $deliveries = [
                    'courier' => [],
                    'delivery' => [],
                    'pickups' => []
                ];
                $details = [];
                $deliveryJson = str_replace('\r\n', '<br>', $deliveryJson);
                $shippings = json_decode($deliveryJson);

// echo \yii\helpers\VarDumper::dump($shippings, 99, true);

                foreach ($shippings as $shipping) {
                    $operator = substr($shipping->delivery_type->delivery_service->name, 0, strpos($shipping->delivery_type->delivery_service->name, ' '));
                    
                    if ($shipping->delivery_type->pickup) {
                        $text = $shipping->comment;
                    } elseif ($shipping->delivery_type->delivery_service->id == 8150) {
                        $text = $shipping->delivery_type->delivery_service->name;
                    } else {
                        $text = $shipping->delivery_type->name . (
                            strpos($shipping->delivery_type->name, $operator) !== false ? '' : ' ' . $operator
                        );
                    }
                    
                    $text = str_replace('<br>', ' ', $text);
                    
                    $deliveryType = $shipping->delivery_type->pickup ? 'pickups' : ($shipping->delivery_type->delivery_service->id == 8150 ? 'courier' : 'delivery');
                    
                    if ($q) {
                        if (mb_stripos($text, $q) === false) {
                            continue;
                        }
                    }
                    
                    // убрать дубли
                    $alreadySet = false;
                    foreach ($deliveries[$deliveryType] as $delivery) {
                        if ($delivery['text'] == $text) {
                            $alreadySet = true;
                        }
                    }
                    
                    // убрать Никольскую
                    if (strpos($text, 'Никольская') !== false) {
                        $alreadySet = true;
                    }
                    
                    // убрать все пункты самовывоза, кроме склада - и переименовать склад
                    if ($deliveryType == 'pickups') {
                        if (strpos($text, 'Склад Freedom International Group') !== false) {
                            $text = 'г. Москва, ул. Краснобогатырская, д.89, стр.1 (метро Преображенская площадь)';
                        } else {
                            continue;
                        }
                    }
                    
                    if (!$alreadySet) {
                        $deliveries[$deliveryType][] = [
                            'id' => $shipping->id,
                            'text' => $text
                        ];
                    }
                    
                    $details[$shipping->id] = [
                        'cost' => $shipping->cost,
                        'price' => Yii::$app->formatter->asCurrency($shipping->cost, Yii::$app->params['currency']),
                        'image' => isset($shipping->image) ? 'https://sessia.com' . $shipping->image : '',
                        'comment' => str_replace('Freedom International Group', 'NRK87', 
                            $shipping->comment
                        ),
                        'time' => (isset($shipping->delivery_time_from) ? Yii::t('front', 'от {0} до {1} дней', [
                            $shipping->delivery_time_from,
                            $shipping->delivery_time_to
                        ]) : ''),
                        'delivery_service' => [
                            'id' => $shipping->delivery_type->delivery_service->id,
                            'name' => str_replace('FIG', 'NRK87.', 
                                $shipping->delivery_type->delivery_service->name
                            ),
                        ],
                        'sum' => ($total + $shipping->cost),
                        'total' => Yii::$app->formatter->asCurrency(($total + $shipping->cost), Yii::$app->params['currency']),
                        'lat' => $shipping->lat,
                        'lon' => $shipping->lng,
                        'text' => $text,
                    ];
                }
                
                // сначала показывать курьера с примеркой в Москве
                if ($country_id == 1 && $city_id == 3) {
                    ArrayHelper::multisort($deliveries['delivery'], 'id', SORT_DESC);
                }
                
                $return = [
                    'delivery' => $deliveries['delivery'],
                    'pickups' => $deliveries['pickups'],
                    'courier' => $deliveries['courier'],
                    'details' => $details,
                ];
                
                switch ($type) {
                    case 'pickups':
                        return json_encode([
                            'results' => $return['pickups']
                        ]);
                        break;
                    case 'delivery':
                        return json_encode([
                            'results' => $return['delivery']
                        ]);
                        break;
                    case 'courier':
                        return json_encode([
                            'results' => $return['courier']
                        ]);
                        break;
                    case 'details':
                        return json_encode($return['details'][$shipping_id]);
                        break;
                    default:
                        return json_encode($return);
                        break;
                }
            }
        }
        
        return false;
    }

    
}