<?php

use yii\web\View;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::t('front', 'Новости');

?>

<div class="container-xxl mt-3">    
    <div class="row justify-content-center">
        <div class="col-sm-11 col-md-9 col-lg-8 col-xl-6">
            <div class="row justify-content-center">
                <div class="col-xxl-10">
                    <h1 class="gotham font-weight-bold text-uppercase headline mb-5">
                        <?= $this->title ?>
                    </h1>
                    <div id="news" class="row mb-4">
            <?php
                if ($posts) {
                    foreach ($posts as $post) {
                        $image = $post->getImage();
                        $cachedImage = '/images/cache/News/News' . $image->itemId . '/' . $image->urlAlias . '_500x500.' . $image->getExtension();
                        $name = json_decode($post->name)->{Yii::$app->language};
            ?>
                        <div class="col-sm-6 col-lg-4">
                            <?= $this->render('/news/_post', [
                                    'post' => $post
                                ])
                            ?>
                        </div>
            <?php
                    }
                }
            ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>