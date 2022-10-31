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
    
    public function actionIndex($product_id = null, $size = null)
    {
        if ($product_id && $size) {
            $this->actionRemove($product_id, $size);
        }
        
        $wishlist = Wishlist::find()
            ->where([
                'user_id' => (Yii::$app->user->isGuest ? Yii::$app->session->getId() : Yii::$app->user->id)
            ])
            ->orderBy([
                'id' => SORT_DESC
            ])
            ->all();
        
        $products = Product::find()->all();
            
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
            
        Yii::$app->params['currency'] = \common\models\Languages::findOne([
            'code' => Yii::$app->language
        ])->currency;
    
        $prices = ArrayHelper::map($modifications, 'product_id', 'price');
        $pricesOld = ArrayHelper::map($modifications, 'product_id', 'price_old');

        $items = [];
        
        if ($wishlist) {
            foreach ($wishlist as $wish) {
                $product = array_values(array_filter($products, function ($prod) use ($wish) {
                    return $prod->id == $wish->product_id;
                }))[0];

                $image = $product->getImage();
                $cachedImage = '/images/cache/Products/Product' . $image->itemId . '/' . $image->urlAlias . '_200x200.jpg';
                $productImage = file_exists(Yii::getAlias('@frontend') . '/web' . $cachedImage) ? $cachedImage : $image->getUrl('200x200');
                
                $items[] = [
                    'id' => $wish->id,
                    'product_id' => $wish->product_id,
                    'size' => $wish->size,
                    'name' => json_decode($product->name)->{Yii::$app->language},
                    'image' => $productImage,
                    'slug' => $product->slug,
                    'price' => $prices[$wish->product_id],
                    'priceOld' => $pricesOld[$wish->product_id],
                ];
            }
        }

        return $this->render('index', [
            'items' => $items,
        ]);
    }
    
    public function actionCheck($product_id, $size = null)
    {
        $model = Wishlist::findOne([
            'user_id' => (Yii::$app->user->isGuest ? Yii::$app->session->getId() : Yii::$app->user->id),
            'product_id' => $product_id,
            'size' => $size,
        ]);
        
        return $this->renderPartial('product', [
            'check' => $model ? true : false,
            'product_id' => $product_id,
            'size' => $size,
        ]);
    }
    
    public function actionAdd($product_id, $size = null)
    {
        if (!$model = Wishlist::findOne([
            'user_id' => (Yii::$app->user->isGuest ? Yii::$app->session->getId() : Yii::$app->user->id),
            'product_id' => $product_id,
            'size' => $size,
        ])) {
            $model = new Wishlist();
            $model->user_id = Yii::$app->user->isGuest ? Yii::$app->session->getId() : Yii::$app->user->id;
            $model->product_id = $product_id;
            $model->size = $size;
            $model->save();
        }
    }
    
    public function actionRemove($product_id, $size = null)
    {
        $model = Wishlist::findOne([
            'user_id' => (Yii::$app->user->isGuest ? Yii::$app->session->getId() : Yii::$app->user->id),
            'product_id' => $product_id,
            'size' => $size,
        ]);
        $model->delete();
    }

}