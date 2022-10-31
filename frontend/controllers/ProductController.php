<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;
// use backend\models\Stores;
use dvizh\shop\models\Product;
use dvizh\shop\models\Modification;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use Facebook\Facebook;
use linslin\yii2\curl;

class ProductController extends \yii\web\Controller
{
    
    public function actionIndex($slug)
    {
        
        $product = Product::find()->where('slug = :slug', [
            ':slug' => $slug
        ])->one();
        
        if (!$product) {
            throw new NotFoundHttpException(Yii::t('front', 'Товар не найден'));
        }
        
        $categoryName = null;
        $collections = [
            16,
            17,
            9
        ];
        if ($productCategories = $product->categories) {
            foreach ($productCategories as $productCategory) {
                if (!in_array($productCategory->id, $collections)) {
                    $categoryName = $productCategory->name;
                    break;
                }
            }
        }
        
        
        $prices = (new Query())
            ->select([
                'p.price',
                'p.price_old',
            ])
            ->from([
                'm' => '{{%shop_product_modification}}',
                'p' => '{{%shop_price}}',
            ])
            ->where([
                'm.available' => 1,
                'm.product_id' => $product->id,
            ])
            ->andWhere(['like', 'm.name', Yii::$app->language])
            ->andWhere(['like', 'm.name', Yii::$app->params['store_types'][Yii::$app->params['store_type']]])
            ->andWhere('m.id = p.item_id')
            ->one();
            
        // $facebookViewProduct = Yii::$app->runAction('curl', [
            // 'url' => 'https://graph.facebook.com/v12.0/' . Yii::$app->params['facebookPixelID'] . '/events',
            // 'post' => true,
            // 'params' => [
                // 'access_token' => Yii::$app->params['facebookAccessToken'],
                // 'data' => [
                    // 'event_name' => 'ViewContent',
                    // 'event_time' => time(),
                    // 'action_source' => 'website',
                    // 'user_data' => [
                        // 'external_id' => [
                            // hash('sha256', Yii::$app->session->getId())
                        // ]
                    // ],
                    // 'custom_data' => [
                        // 'currency' => Yii::$app->params['currency'],
                        // 'value' => (float)$prices['price'],
                        // 'contents' => [
                            // 'id' => $product->id,
                            // 'quantity' => 1,
                            // 'item_price' => (float)$prices['price']
                        // ],
                        // 'content_type' => 'product',
                        // 'variant' => null,
                        // 'name' => json_decode($product->name)->{Yii::$app->language},
                        // 'test_event_code' => 'TEST74439'
                    // ]
                // ]
            // ]
        // ]);
        
        $productsPrices = null;
        $productsPricesOld = null;
        
        $relations = $product->getRelations();
        
        if ($relations) {
            $modifications = (new Query())
                ->select([
                    'product_id' => 'm.product_id',
                    'price' => 'p.price',
                    'price_old' => 'p.price_old',
                ])
                ->from([
                    'm' => '{{%shop_product_modification}}',
                    'p' => '{{%shop_price}}',
                ])
                ->where([
                    'm.available' => 1,
                    // 'm.lang' => Yii::$app->language,
                    // 'm.store_type' => Yii::$app->params['store_type'],
                ])
                ->andWhere(['like', 'm.name', Yii::$app->language])
                ->andWhere(['like', 'm.name', Yii::$app->params['store_types'][Yii::$app->params['store_type']]])
                ->andWhere('m.id = p.item_id')
                ->groupBy([
                    'product_id',
                    'price',
                    'price_old'
                ])
                ->all();
        
            $productsPrices = ArrayHelper::map($modifications, 'product_id', 'price');
            $productsPricesOld = ArrayHelper::map($modifications, 'product_id', 'price_old');
        }
        
        return $this->render('index', [
            'model' => $product,
            'price' => $prices['price'],
            'priceOld' => $prices['price_old'],
            'categoryName' => $categoryName,
            'relations' => $relations,
            'prices' => $productsPrices,
            'pricesOld' => $productsPricesOld,
        ]);
    }

}