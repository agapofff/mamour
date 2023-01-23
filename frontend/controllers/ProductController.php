<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use common\models\Stores;
use common\models\Wishlist;
use dvizh\shop\models\Product;
use dvizh\shop\models\Modification;
use yii\db\Query;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use Facebook\Facebook;
use linslin\yii2\curl;

class ProductController extends \yii\web\Controller
{
    public function actionIndex($slug)
    {
        $product = Product::find()
            ->where('slug = :slug', [
                ':slug' => $slug
            ])
            ->one();
        
        if (!$product) {
            throw new NotFoundHttpException(Yii::t('front', 'Товар не найден'));
        }
        
        $productModifications = $product->modifications;

        $disabledItems = [];
        if ($productModifications) {
            foreach ($productModifications as $productModification) {
                if (
                    $productModification->store_id == Yii::$app->params['store_id'] 
                    && (!$productModification->available || !$productModification->amount)
                ) {
                    $disabledItems[] = $productModification->getFiltervariants();
                }
            }
        }
        
        $wishlist = Wishlist::findAll([
            'user_id' => (Yii::$app->user->isGuest ? Yii::$app->session->getId() : Yii::$app->user->id)
        ]);
        $wishlist = ArrayHelper::getColumn($wishlist, 'product_id');
            
        $modifications = Product::getAllProductsPrices();
        $prices = array_unique(ArrayHelper::map($modifications, 'product_id', 'price'));
        $oldPrices = array_unique(ArrayHelper::map($modifications, 'product_id', 'price_old'));
echo VarDumper::dump($pries, 99, true);        
        $this->view->params['model'] = $product;
        
        
        
        return $this->render('index', [
            'product' => $product,
            'prices' => $prices,
            'oldPrices' => $oldPrices,
            // 'price' => (float)$productModifications[0]->price,
            // 'priceOld' => (float)$productModifications[0]->oldPrice,
            // 'sizes' => $productSizes,
            'wishlist' => $wishlist,
            'disabledItems' => $disabledItems,
        ]);
    }
}
