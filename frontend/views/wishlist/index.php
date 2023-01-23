<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii\web\View;

$this->title = Yii::t('front', 'Избранное');
?>

<div class="container-fluid mt-5 mt-lg-8">    
    <div class="row justify-content-center justify-content-lg-start">
        <div class="col-sm-11 col-md-9 col-lg-8 col-xl-6">
            <h1 class="montserrat font-weight-bold text-uppercase headline mb-4 mb-lg-6">
                <?= $this->title ?>
            </h1>
                    
        <?php
            Pjax::begin([
                'enablePushState' => false,
            ]);
            if ($wishlist) {
        ?>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 justify-content-center">

            <?php
                foreach ($wishlist as $product) {
            ?>
                    <div class="col mb-3">
                        <?= $this->render('@frontend/views/catalog/_product', [
                                'product' => $product,
                                'productName' => json_decode($product->name)->{Yii::$app->language},
                                'oldPrice' => $oldPrices[$product->id],
                                'price' => $prices[$product->id],
                                'wishlist' => 'remove',
                            ]);
                        ?>
                    </div>
            <?php
                }
            ?>
                </div>
        <?php
            }
            Pjax::end();
        ?>
        </div>
    </div>
</div>