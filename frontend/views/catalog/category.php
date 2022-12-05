<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;

?>

<div class="container-xxl mt-3">    
    <div class="row justify-content-center justify-content-lg-start">
        <div class="col-sm-3 col-md-3 col-lg-2 col-xl-2 offset-xl-1 d-none d-md-block">
            
        </div>
        <div class="col-sm-11 col-md-9 col-lg-8 col-xl-6">
            <div class="row justify-content-center">
                <div class="col-xxl-10">
                    <h1 class="gotham font-weight-bold text-uppercase headline mb-3 mb-md-5">
                        <?= json_decode($category->name)->{Yii::$app->language} ?>
                    </h1>
                </div>
            </div>
        </div>
    </div>
</div>
