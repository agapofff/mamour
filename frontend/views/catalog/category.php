<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->title = Yii::$app->params['title'] ?: Yii::t('front', 'Каталог');
$h1 = Yii::$app->params['h1'] ?: $this->title;

// \yii\web\YiiAsset::register($this);

?>

<h1 class="display-3 acline text-center my-5">
    <?= $h1 ?>
</h1>


<div class="container-fluid position-relative <?= $cover ?>">

    <div class="row justify-content-center my-2 my-lg-5">
    
        <div class="col-12 col-lg-11 col-xl-10 my-2 my-lg-5 py-2 py-lg-5">
        
            <div class="row align-items-center">
            <?php
                if ($images) {
            ?>
                <div class="col-12 col-sm-8 p-0 position-absolute h-100 cover">
                <?php
                    foreach ($images as $image) {
                ?>
                        <img data-src="<?= $image->getUrl() ?>" alt="<?= $title ?> <?= Yii::$app->name ?>" class="lazyload pointer-events-none">
                <?php
                    }
                ?>
                </div>
            <?php
                }
            ?>
                
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 my-5 cover-menu">
                    
                    <h2 class="display-1 acline my-5 text-nowrap">
                        <?= $title ?>
                    </h2>
                    
                    <?php
                        if ($category->id == 17) {
                    ?>
                            <h3 class="display-3 acline my-5">
                                <?= Yii::t('front', 'Coming soon...') ?>
                            </h3>
                    <?php
                        }
                    ?>
                    
                <?php
                    if ($subCategories) {
                ?>
                    <div class="row">
                    <?php
                        foreach ($subCategories as $subCategory) {
                    ?>
                        <div class="col-12 col-sm-6 my-2">
                            <?= Html::a(json_decode($subCategory->name)->{Yii::$app->language}, [
                                    '/catalog/' . $subCategory->slug
                                ], [
                                    'class' => 'h4 font-weight-light mb-0' . ($subCategory->slug == $slug ? ' text-underline' : ''),
                                ]
                            ) ?>
                        </div>
                    <?php
                        }
                    ?>
                    </div>
                <?php
                    }
                ?>
                    
                </div>
            
            </div>
        
        </div>
    
    </div>

</div>


<div class="container-fluid">

    <div class="products-view px-3">


    </div>

</div>