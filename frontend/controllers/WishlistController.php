<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use common\models\Wishlist;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use dvizh\shop\models\Product;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class WishlistController extends \yii\web\Controller
{
    
    // public function behaviors()
    // {
        // return [
            // 'access' => [
                // 'class' => AccessControl::className(),
                // 'only' => ['index', 'add', 'remove', 'check'],
                // 'rules' => [
                    // [
                        // 'actions' => ['index', 'add', 'remove', 'check'],
                        // 'allow' => true,
                        // 'roles' => ['@'],
                    // ],
                // ],
            // ],
        // ];
    // }
    
    public function actionIndex($product_id = null)
    {
        $userID = Yii::$app->user->isGuest ? Yii::$app->session->getId() : Yii::$app->user->id;
        
        if ($product_id) {
            $model = Wishlist::findOne([
                'user_id' => $userID,
                'product_id' => $product_id,
            ]);
            $model->delete();
        }
        
        $wishlist = Wishlist::find()
            ->where([
                'user_id' => $userID
            ])
            ->orderBy([
                'id' => SORT_DESC
            ])
            ->all();
        
        $products = Product::find()->all();
            
        $modifications = Product::getAllProductsPrices();
        $prices = ArrayHelper::map($modifications, 'product_id', 'price');
        $oldPrices = ArrayHelper::map($modifications, 'product_id', 'price_old');

        return $this->render('index', [
            'wishlist' => $items,
            'prices' => $prices,
            'oldPrices' => $oldPrices,
        ]);
    }
    
    public function actionCheck($product_id)
    {
        $model = Wishlist::findOne([
            'user_id' => (Yii::$app->user->isGuest ? Yii::$app->session->getId() : Yii::$app->user->id),
            'product_id' => $product_id,
        ]);
        
        return $this->renderPartial('product', [
            'action' => $model ? 'remove' : 'add',
            'product_id' => $product_id,
        ]);
    }
    
    public function actionAdd($product_id)
    {
        if (!$model = Wishlist::findOne([
            'user_id' => (Yii::$app->user->isGuest ? Yii::$app->session->getId() : Yii::$app->user->id),
            'product_id' => $product_id,
        ])) {
            $model = new Wishlist();
            $model->user_id = Yii::$app->user->isGuest ? Yii::$app->session->getId() : Yii::$app->user->id;
            $model->product_id = $product_id;
            if (!$model->save()){
                return print_r($model->getErrors());
            }
        }
        
        return $this->actionCheck($product_id);
    }
    
    public function actionRemove($product_id)
    {
        $model = Wishlist::findOne([
            'user_id' => (Yii::$app->user->isGuest ? Yii::$app->session->getId() : Yii::$app->user->id),
            'product_id' => $product_id,
        ]);
        $model->delete();
        return $this->actionCheck($product_id);
    }

}