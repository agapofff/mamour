<?php

namespace frontend\controllers;

use Yii;

use common\models\Stores;
use common\models\Languages;
use dvizh\shop\models\Price;
use dvizh\shop\models\Modification;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class SynchroController extends \yii\web\Controller
{
    
    public function actionIndex()
    {
        Price::updateAll([
            'price' => 0,
            'price_old' => 0,
            'available' => 'no',
        ]);
        
        Modification::updateAll([
            'available' => 0,
            'amount' => 0,
        ]);
        
        $modifications = Modification::find()->all();
        
        $prices = Price::find()->all();
        
        $stores = Stores::find()
            ->where([
                'active' => 1
            ])
            ->all();
        
        if ($stores) {
            foreach ($stores as $store) {
                $request = @file_get_contents('https://api.sessia.com/api/market/' . $store->store_id . '/showcase-tree');
                if ($request) {
                    $request = json_decode($request, true);
                    foreach ($request as $val) {
                        if (isset($val['goods_list'])) {
                            foreach ($val['goods_list'] as $good) {
                                foreach ($modifications as $modification) {
                                    if ($modification->sku == $good['id']) {
                                        $modification->available = $good['is_purchasable'] ? 1 : 0;
                                        $modification->amount = $good['is_purchasable'] ? 99 : 0;
                                        $modification->code = $good['vendor_code'];
                                        $modification->save();
                                        
                                        foreach ($prices as $price) {
                                            if ($price->item_id == $modification->id) {
                                                $price->price = isset($good['price']) ? (int)$good['price'] : 0;
                                                // $price->price_old = isset($good['retail_price']) ? (int)$good['retail_price'] : 0;
                                                $price->available = $good['is_purchasable'] ? 'yes' : 'no';
                                                $price->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        
    }
    
}