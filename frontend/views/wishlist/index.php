<?php

    use yii\helpers\Url;
    use yii\helpers\Html;
    use yii\widgets\ActiveForm;
    use yii\widgets\Pjax;
    use yii\web\View;

    $this->title = Yii::t('front', 'Избранное');
    
?>

<div class="container-xxl mt-3">    
    <div class="row justify-content-center justify-content-lg-start">
        <div class="col-sm-3 col-md-3 col-lg-2 col-xl-2 offset-xl-1 d-none d-md-block">
            <?= $this->render('@frontend/views/user/settings/_menu') ?>
        </div>
        <div class="col-sm-11 col-md-9 col-lg-8 col-xl-6">
            <div class="row justify-content-center">
                <div class="col-xxl-10">
                    <h1 class="gotham font-weight-bold text-uppercase headline mb-3 mb-md-5">
                        <?= $this->title ?>
                    </h1>
                    
            <?php
                if ($items) {
                    
                    Pjax::begin([
                        'enablePushState' => false,
                    ]);
                    
                    foreach ($items as $item) {
            ?>

                        <div class="row">
                            <div class="col-4">
                                <a href="<?= Url::to(['/product/' . $item['slug']]) ?>">
                                    <img src="<?= $item['image'] ?>" class="img-fluid">
                                </a>
                            </div>
                            <div class="col-5">
                                <div class="row h-100">
                                    <div class="col-12 align-self-start">
                                        <p class="font-weight-bold">
                                            <?= $item['name'] ?> <?= $item['size'] ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="row h-100">
                                    <div class="col-12 align-self-start text-right">
                                        <span class="dvizh-cart-element-price696 font-weight-bold text-nowrap text-center">
                                            <?= Yii::$app->formatter->asCurrency($item['price'], Yii::$app->params['currency']) ?>
                                        </span>
                                    </div>
                                    <div class="col-12 align-self-end text-right mb-0_5">
                                        <a href="<?= Url::to(['/wishlist',
                                            'product_id' => $item['product_id'],
                                            'size' => $item['size']
                                        ]) ?>">
                                            <?= Yii::t('front', 'Удалить') ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr class="my-1_5">

            <?php
                    }
                    
                    Pjax::end();
                }
            ?>
                </div>
            </div>
        </div>
    </div>
</div>