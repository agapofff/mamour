<?php
use yii\helpers\Html;
use yii\web\View;

if (!$this->title) {
    $this->title = json_decode($model->name)->{Yii::$app->language};
}
?>

<div class="container-xxl mt-3 mt-sm-4 mt-md-5 mt-lg-6 mt-xl-7">    
    <div class="row justify-content-center">
        <div class="col-sm-11 col-md-10 col-lg-9 col-xl-8 col-xxl-7">
            <h1 class="gotham font-weight-bold text-uppercase headline mb-5">
            <?= json_decode($model->name)->{Yii::$app->language} ?>
            </h1>
            <div id="page-content">
                <?= json_decode($model->text)->{Yii::$app->language} ?>
            </div>
        </div>
    </div>
</div>
