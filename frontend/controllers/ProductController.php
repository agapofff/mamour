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
        
        $modifications = $product->modifications;

        $disabledItems = [];
        foreach ($modifications as $modification) {
            if (
                $modification->store_id == Yii::$app->params['store_id'] 
                && (!$modification->available || !$modification->amount)
            ) {
                $disabledItems[] = $modification->getFiltervariants();
            }
        }
        
        $wishlist = Wishlist::findOne([
            'user_id' => (Yii::$app->user->isGuest ? Yii::$app->session->getId() : Yii::$app->user->id),
            'product_id' => $product->id,
        ]);
        
        $this->view->params['model'] = $product;
        
        return $this->render('index', [
            'product' => $product,
            'price' => (float)$modifications[0]->price,
            'priceOld' => (float)$modifications[0]->oldPrice,
            // 'sizes' => $productSizes,
            'wishlist' => $wishlist ? 'remove' : 'add',
            'disabledItems' => $disabledItems,
        ]);
    }
}
