<?php

namespace frontend\controllers;
use Yii;
use yii\web\Controller;
use common\models\Stores;
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
        $product = Product::find()
            ->where('slug = :slug', [
                ':slug' => $slug
            ])
            ->one();
        
        if (!$product) {
            throw new NotFoundHttpException(Yii::t('front', 'Товар не найден'));
        }
        
        $modifications = Product::getAllProductsPrices($product->id);
        $modificationsSizes = Product::getAllProductsSizes($product->id);
        $modificationsPrices = ArrayHelper::map($modifications, 'product_id', 'price');
        $modificationsOldPrices = ArrayHelper::map($modifications, 'product_id', 'price_old');
        $productsSizes = array_unique(ArrayHelper::map($modificationsSizes, 'id', 'value'));
        $productsPrices = array_unique($modificationsPrices);

        $productSizes = array_filter($modificationsSizes, function ($modificationsSizes) use ($product) {
            return $modificationsSizes['product_id'] == $product->id;
        });
        
        return $this->render('index', [
            'model' => $product,
            'price' => (float)$modificationsPrices[$product->id],
            'priceOld' => (float)$modificationsOldPrices[$product->id],
        ]);
    }
}
