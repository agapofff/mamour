<?php
    use yii\helpers\Html;
    use yii\web\View;
    
    $name = json_decode($model->name)->{Yii::$app->language};
    $text = json_decode($model->text)->{Yii::$app->language};
    
    $this->title = Yii::$app->params['title'] ?: $name;
    
    $h1 = Yii::$app->params['h1'] ?: $name;
?>

<div class="container-xxl mt-3">    
    <div class="row justify-content-center">
        <div class="col-sm-11 col-md-10 col-lg-9 col-xl-6 col-xxl-5">
            <h1 class="gotham font-weight-bold text-uppercase headline mb-5">
            <?= $h1 ?>
            </h1>
            <div id="page-content">
                <?= $text ?>
            </div>
        </div>
    </div>
</div>
