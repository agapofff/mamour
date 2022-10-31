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

use backend\models\Stores;

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

        Yii::$app->getModule('order')->currency = Yii::$app->params['currency'];
        
        $shippingTypesList = ShippingType::find()
            ->where([
                'active' => 1
            ])
            ->orderBy([
                'order' => SORT_ASC
            ])
            ->all();

        $shippingTypes = [];
        
        foreach ($shippingTypesList as $sht) {
            if ($sht->cost > 0) {
                $currency = Yii::$app->getModule('order')->currency;
                $name = "{$sht->name} ({$sht->cost}{$currency})";
            } else {
                $name = $sht->name;
            }
            $shippingTypes[$sht->id] = $name;
        }
        
        $paymentTypes = [];
        $paymentTypesList = PaymentType::find()
            ->where([
                'active' => 1
            ])
            ->orderBy('order DESC')
            ->all();
        
        foreach($paymentTypesList as $pt) {
            $paymentTypes[$pt->id] = $pt->name;
        }
        
        
        $fields = Field::find()->orderBy('order DESC')->all();
        $fieldValueModel = new FieldValue;
    
        $orderModel = new Order;
        
        if (empty($orderModel->shipping_type_id) && $orderShippingType = Yii::$app->session->get('orderShippingType')){
            if($orderShippingType > 0) {
                $orderModel->shipping_type_id = (int)$orderShippingType;
            }
        }
        
        $this->getView()->registerJs("dvizh.orderForm.updateShippingType = '".Url::toRoute(['/order/tools/update-shipping-type'])."';");

        $store = Stores::findOne([
            'type' => Yii::$app->params['store_type'],
            'lang' => Yii::$app->language,
        ]);

        $countriesJson = Yii::$app->runAction('checkout/get-countries', [
            'store_id' => $store->store_id
        ]);
        
        if ($countriesJson){
            $countriesList = [];
            $countriesOptions = [];
            $countryByLang = null;
            $countries = json_decode($countriesJson);
            $countriesList = ArrayHelper::map($countries, 'id', 'name');
            
            foreach ($countries as $country) {
                $countriesOptions[$country->id] = [
                    'data-code' => $country->code,
                    'data-phonemask' => $country->mask,
                    'data-iso' => $country->isoCode,
                ];
                if ($country->lang == Yii::$app->language && !$countryByLang){
                    $countryByLang = $country->id;
                }
            }
        }
        
        $lastUserOrder = Order::find()
            ->where([
                'user_id' => (int)Yii::$app->user->id
            ])
            ->orderBy('id', SORT_DESC)
            ->one();

        $country_id = $lastUserOrder && $lastUserOrder->country_id ? $lastUserOrder->country_id : $countryByLang;
        $country_name = $countriesList[$country_id];
        
        $citiesJson = Yii::$app->runAction('checkout/get-cities', [
            'country_id' => $country_id,
            'lang' => Yii::$app->language,
        ]);
        
        if ($citiesJson){
            $cities = json_decode($citiesJson, true);
            $citiesList = ArrayHelper::map($cities['results'], 'id', 'text');
        }

        $city_id = $lastUserOrder && $lastUserOrder->city_id ? $lastUserOrder->city_id : $cities['results'][0]['id'];
        $city_name = $citiesList[$city_id];

        $orderModel->client_name = trim($lastUserOrder && $lastUserOrder->client_name ? $lastUserOrder->client_name : implode(' ', [
            !Yii::$app->user->isGuest && Yii::$app->user->identity->profile->first_name ?: null,
            !Yii::$app->user->isGuest && Yii::$app->user->identity->profile->last_name ?: null,
        ]));
        
        $orderModel->phone = $lastUserOrder && $lastUserOrder->phone ? $lastUserOrder->phone : (!Yii::$app->user->isGuest ? Yii::$app->user->identity->profile->phone : null);
        
        $orderModel->email = $lastUserOrder && $lastUserOrder->email ? $lastUserOrder->email : (!Yii::$app->user->isGuest ? Yii::$app->user->identity->profile->email : null);
        
        $shippingsJson = Yii::$app->runAction('checkout/get-delivery', [
            'country_id' => $country_id,
            'city_id' => $city_id,
        ]);
		
		if ($shippingsJson){
			$shippings = json_decode($shippingsJson);
			
			$deliveryList = ArrayHelper::map($shippings->delivery, 'id', 'text');
			$pickupsList = ArrayHelper::map($shippings->pickups, 'id', 'text');
			$courierList = ArrayHelper::map($shippings->courier, 'id', 'text');
        
// echo \yii\helpers\VarDumper::dump($pickupsList, 9999, true); exit;
        
			if ($shippings->delivery){
				$orderModel->shipping_type_id = 1;
				$delivery_id = $shippings->delivery[0]->id;
				$delivery_name = $shippings->delivery[0]->text;
			} else if ($shippings->pickups){
				$orderModel->shipping_type_id = 2;
				$delivery_id = $shippings->pickups[0]->id;
				$delivery_name = $shippings->pickups[0]->text;
			} else if ($shippings->courier){
				$orderModel->shipping_type_id = 3;
				$delivery_id = $shippings->courier[0]->id;
				$delivery_name = $shippings->courier[0]->text;
			}
			
			$delivery_cost = $shippings->details->{$delivery_id}->cost;
			$delivery_price = $shippings->details->{$delivery_id}->price;
			$delivery_time = $shippings->details->{$delivery_id}->time;
			$delivery_image = $shippings->details->{$delivery_id}->image;
			$delivery_comment = $shippings->details->{$delivery_id}->comment;
			$total = $shippings->details->{$delivery_id}->total;
		}
        
        $lang_id = Yii::$app->runAction('checkout/get-lang-id');
        
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
            'citiesList' => $citiesList,
            'delivery' => empty($shippings->delivery),
            'pickups' => empty($shippings->pickups),
            'courier' => empty($shippings->courier),
            'deliveryList' => $deliveryList,
            'pickupsList' => $pickupsList,
            'courierList' => $courierList,
            'fieldsDefaultValues' => [
                'city_id' => $city_id,
                'city_name' => $city_name,
                'country_id' => $country_id,
                'country_name' => $country_name,
                'delivery_id' => $delivery_id,
                'delivery_name' => $delivery_name,
                'delivery_cost' => $delivery_cost,
                'postcode' => '',
                'id_order_sessia' => '',
                'log_request' => '',
                'log_response' => '',
                'delivery_comment' => $delivery_comment,
            ],
            'delivery_price' => $delivery_price,
            'delivery_comment' => $delivery_comment,
            'delivery_image' => $delivery_image,
            'delivery_time' => $delivery_time,
            'total' => $total,
            'lang_id' => $lang_id,
            'store_id' => $store->store_id,
        ]);
    }

}
