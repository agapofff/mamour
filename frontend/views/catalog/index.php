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

<div class="container-xxl mt-3 mt-sm-4 mt-md-5 mt-lg-6 mt-xl-7">    
    <div class="row justify-content-center justify-content-lg-start">
        <div id="category-menu" class="col-sm-3 col-md-3 col-lg-2 col-xl-3 d-none d-md-block">
        <?php 
            $menu = ArrayHelper::index(Yii::$app->params['menu'], 'id');
            $rootID = 0;
            foreach ($menu as $key => $item) {
                if ($item['current']) {
                    $rootID = $item['parent_id'] == '0' ? $item['id'] : $item['parent_id'];
                }
                if (in_array($item['id'], [41, 42])) {
                    unset($menu[$key]);
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
                if (!$category->parent_id) {
                    $image = $category->getImage();
                    $imageCachePath = '/images/cache/Slides/Slides' . $image->itemId . '/' . $image->urlAlias . '.' . $image->extension;
                    $imageSrc = file_exists(Yii::getAlias('@frontend') . '/web' . $imageCachePath) ? $imageCachePath : $image->getUrl();
    ?>
                    <a href="<?= Url::to(['/catalog/' . $category->slug]) ?>">
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
            }
    ?>
            </div>

            <div class="owl-carousel owl-theme" data-items="2-2-3-3-4-4" data-margin="20" data-nav="true" data-dots="true" data-loop="true" data-random="true">
    <?php
            foreach ($categories as $category) {
                if ($category->parent_id) {
                    $image = $category->getImage();
                    $imageCachePath = '/images/cache/Slides/Slides' . $image->itemId . '/' . $image->urlAlias . '.' . $image->extension;
                    $imageSrc = file_exists(Yii::getAlias('@frontend') . '/web' . $imageCachePath) ? $imageCachePath : $image->getUrl();
                    $url = Category::getAllParents($categories, $category->id, 'slug', true);
    // print_r($url);
    ?>
                    <a href="<?= Url::to(['/catalog/' . join('/', array_reverse($url))]) ?>">
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
            }
    ?>
            </div>
    <?php
        }
    ?>
            
        </div>
    </div>
</div>