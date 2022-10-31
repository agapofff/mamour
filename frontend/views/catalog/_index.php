<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

$this->title = Yii::$app->params['title'] ?: Yii::t('front', 'Каталог');
$h1 = Yii::$app->params['h1'] ?: $this->title;

// \yii\web\YiiAsset::register($this);

?>

<?= $this->render('@frontend/views/site/cover-socials') ?>


<h1 class="display-3 acline text-center my-5">
    <?= $h1 ?>
</h1>


<?php
    foreach ($categories as $key => $category)
    {
        if ($key == 0 || $key == 2) {
    ?>
            <div id="collection_<?= $key ? '2020' : '2021' ?>" class="container">
                <div class="row mt-4 mt-lg-5 <?php if ($key == 2) {?>pt-4 pt-lg-5<?php } ?>">
                    <div class="col-12 text-center pt-4 pt-lg-5">
                        <h2 class="display-1 acline">
                            <?= Yii::t('front', 'Коллекция') ?> <?= $key ? '2020' : '2021' ?>
                        </h2>
                    </div>
                </div>
            </div>
    <?php
        }
?>

        <div class="container-fluid position-relative min-vh-75 <?= $category->slug ?>">

            <div class="row justify-content-center my-5">
            
                <div class="col-12 col-lg-11 col-xl-10 my-5 py-5">
                
                    <div class="row align-items-center">
                    <?php
                        if ($images[$category->id]) {
                    ?>
                        <div class="col-12 col-sm-8 p-0 position-absolute h-100 cover">
                        <?php
                            foreach ($images[$category->id] as $image) {
                        ?>
                                <img src="<?= $image->getUrl() ?>" alt="<?= json_decode($category->name)->{Yii::$app->language} ?> <?= Yii::$app->name ?>" loading="lazy">
                        <?php
                            }
                        ?>
                        </div>
                    <?php
                        }
                    ?>
                        
                        <div class="col-12 col-sm-8 col-md-6 col-lg-5 my-5 cover-menu">
                            
                            <h3 class="display-1 acline my-5 text-nowrap">
                                <?= Html::a(json_decode($category->name)->{Yii::$app->language}, [
                                        '/catalog/' . $category->slug
                                    ], [
                                        'class' => 'acline',
                                    ]
                                ) ?>
                            </h3>
                            
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
                            if ($subCategories[$category->id]) {
                        ?>
                            <div class="row">
                            <?php
                                foreach ($subCategories[$category->id] as $subCategory) {
                            ?>
                                <div class="col-12 col-sm-6 my-2">
                                    <?= Html::a(json_decode($subCategory->name)->{Yii::$app->language}, [
                                            '/catalog/' . $subCategory->slug
                                        ], [
                                            'class' => 'h4 font-weight-light mb-0',
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

<?php
    }
?>
