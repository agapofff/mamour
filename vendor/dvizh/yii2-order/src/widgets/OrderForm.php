<?php
namespace dvizh\order\widgets;

use yii\helpers\Url;
use dvizh\order\models\Order;
use dvizh\order\models\PaymentType;
use dvizh\order\models\ShippingType;
use dvizh\order\models\Field;
use dvizh\order\models\FieldValue;
use yii;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use common\models\Stores;
use common\models\Countries;

class OrderForm extends \yii\base\Widget
{
    
    public $view = 'order-form/form';
    public $elements = [];
    
    public function init()
    {
        \dvizh\order\assets\OrderFormAsset::register($this->getView());
        
        return parent::init();
    }
    
    public function run()
    {
        if (Yii::$app->cart->getCount() <= 0){
            Yii::$app->getResponse()->redirect(['/catalog']);
            Yii::$app->end();
        }
// echo VarDumper::dump(Yii::$app->cart, 99, true);
        Yii::$app->getModule('order')->currency = Yii::$app->params['currency'];
        
        // способы доставки
        $shippingTypes = [];
        $shippingTypesList = ShippingType::find()
            ->where([
                'active' => 1,
                'country_id' => Yii::$app->params['country_id'],
            ])
            ->orderBy([
                'sort' => SORT_ASC
            ])
            ->all();
        if ($shippingTypesList) {
            foreach ($shippingTypesList as $s => $sht) {
                $shippingTypes[$sht->id] = json_decode($sht->name)->{Yii::$app->language};
            }
        }
        
        // способы оплаты
        $paymentTypes = [];
        $paymentTypesList = PaymentType::find()
            ->where([
                'active' => 1
            ])
            ->orderBy([
                'sort' => SORT_ASC
            ])
            ->all();
        if ($paymentTypesList) {
            foreach ($paymentTypesList as $pt) {
                $paymentTypes[$pt->id] = json_decode($pt->name)->{Yii::$app->language};
            }
        }
        
        // страны
        $countriesList = $countriesOptions = [];
        $countries = Countries::find()
            ->where([
                'active' => 1,
            ])
            ->orderBy('sort DESC')
            ->all();
        if ($countries) {
            foreach ($countries as $country) {
                $countriesList[$country->id] = json_decode($country->name)->{Yii::$app->language};
                $countriesOptions[$country->id] = [
                    'data-phonemask' => $country->phone,
                    'data-iso' => $country->iso,
                ];
            }
        }
        
        // дополнительные поля
        $fields = Field::findAll([
            'active' => 1
        ]);
        $fieldValueModel = new FieldValue;
    
        // новый заказ
        $orderModel = new Order;
        
        if (empty($orderModel->shipping_type_id) && $orderShippingType = Yii::$app->session->get('orderShippingType')){
            if ($orderShippingType > 0) {
                $orderModel->shipping_type_id = (int)$orderShippingType;
            }
        }
        
        $this->getView()->registerJs("dvizh.orderForm.updateShippingType = '".Url::toRoute(['/order/tools/update-shipping-type'])."';");
        
        $store = Yii::$app->params['store'];
        $country = Yii::$app->params['country'];
        $country_id = Yii::$app->params['country_id'];
        $country_name = json_decode($country->name)->{Yii::$app->language};
        $city_id = null;
        $city_name = null;

        // последний заказ пользователя
        if (!Yii::$app->user->isGuest) {
            $lastUserOrder = Order::find()
                ->where([
                    'user_id' => Yii::$app->user->id
                ])
                ->orderBy('id', SORT_DESC)
                ->one();
                
            if ($lastUserOrder) {
                $country_id = $lastUserOrder->country_id;
                $country_name = $lastUserOrder->country_name;
                $city_id = $lastUserOrder->city_id;
                $city_name = $lastUserOrder->city_name;
                $orderModel->client_name = $lastUserOrder->client_name ?: implode(' ', [Yii::$app->user->identity->profile->first_name, Yii::$app->user->identity->profile->last_name]);
                $orderModel->phone = $lastUserOrder->phone ?: Yii::$app->user->identity->profile->phone;
                $orderModel->email = $lastUserOrder->email ?: Yii::$app->user->identity->profile->email;
            }
        }
        
        
        return $this->render($this->view, [
            'orderModel' => $orderModel,
            'fields' => $fields,
            'paymentTypes' => $paymentTypes,
            'elements' => $this->elements,
            'shippingTypes' => $shippingTypes,
            'shippingTypesList' => $shippingTypesList,
            'fieldValueModel' => $fieldValueModel,
            'country_id' => $country_id,
            'countriesList' => $countriesList,
            'countriesOptions' => $countriesOptions,
            'fieldsDefaultValues' => [
                'city_id' => $city_id,
                'city_name' => $city_name,
                'country_id' => $country_id,
                'country_name' => $country_name,
                'delivery_id' => $delivery_id,
                'delivery_name' => $delivery_name,
                'delivery_cost' => $delivery_cost,
                'postcode' => '',
                'order_id' => '',
                'log_request' => '',
                'log_response' => '',
                'delivery_comment' => $delivery_comment,
            ],
            'delivery_price' => $delivery_price,
            'delivery_comment' => $delivery_comment,
            'delivery_time' => $delivery_time,
            
            'lang_id' => '1',
        ]);
    }

}
