<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use PELock\ImgOpt\ImgOpt;
use dvizh\shop\models\Category;
// use yii\widgets\ListView;
// use yii\widgets\Pjax;
// use yii\web\View;
// use frontend\widgets\FilterPanel\FilterPanel;

if (!$this->title) {
    $this->title = Yii::t('front', 'Каталог');
}

// \yii\web\YiiAsset::register($this);
?>

<div class="container-xxl mt-2">    
    <div class="row justify-content-center justify-content-lg-start">
        <div id="category-menu" class="col-sm-3 col-md-3 col-lg-2 col-xl-3 d-none d-md-block">
        <?php 
            $menu = ArrayHelper::index(Yii::$app->params['menu'], 'id');
            $rootID = 0;
            foreach ($menu as $item) {
                if ($item['current']) {
                    $rootID = $item['parent_id'] == '0' ? $item['id'] : $item['parent_id'];
                }
            }

            Category::renderMenu(Category::buildTreeArray($menu, $rootID), 'list-unstyled pl-2', null, 'd-inline-block montserrat font-weight-bold text-uppercase mb-0_5 fs15px', 'text-decoration-underline') ?>
        </div>
        <div class="col-sm-11 col-md-9 col-lg-8 col-xl-6">
            <h1 class="gotham font-weight-bold text-uppercase headline mb-2 mb-lg-3">
                <?= $this->title ?>
            </h1>
            
            <?php
                if ($categories) {
            ?>
                    <div class="owl-carousel owl-theme mb-1_25" data-items="2-2-2-2-2-2" data-margin="20" data-nav="true" data-dots="true" data-loop="true">
            <?php
                    foreach ($categories as $category) {
                        $image = $category->getImage();
                        $imageCachePath = '/images/cache/Slides/Slides' . $image->itemId . '/' . $image->urlAlias . '.' . $image->extension;
                        $imageSrc = file_exists(Yii::getAlias('@frontend') . '/web' . $imageCachePath) ? $imageCachePath : $image->getUrl();
            ?>
                        <a href="<?= Url::to([$category->link]) ?>">
                            <?= ImgOpt::widget([
                                    'src' => $imageSrc, 
                                    'alt' => $this->title,
                                    'loading' => 'lazy',
                                    'css' => 'img-fluid',
                                ])
                            ?>
                        </a>
            <?php
                    }
            ?>
                    </div>
            <?php
                }

                if ($subCategories) {
            ?>
                    <div class="owl-carousel owl-theme" data-items="2-2-3-3-4-4" data-margin="20" data-nav="true" data-dots="true" data-loop="true">
            <?php
                    foreach ($subCategories as $subCategory) {
                        $image = $subCategory->getImage();
                        $imageCachePath = '/images/cache/Slides/Slides' . $image->itemId . '/' . $image->urlAlias . '.' . $image->extension;
                        $imageSrc = file_exists(Yii::getAlias('@frontend') . '/web' . $imageCachePath) ? $imageCachePath : $image->getUrl();
            ?>
                        <a href="<?= Url::to([$subCategory->link]) ?>">
                            <?= ImgOpt::widget([
                                    'src' => $imageSrc, 
                                    'alt' => $this->title,
                                    'loading' => 'lazy',
                                    'css' => 'img-fluid',
                                ])
                            ?>
                        </a>
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