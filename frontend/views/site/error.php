<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ContactForm */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('front', 'Ошибка') . ' 404';

// $this->params['breadcrumbs'][] = $this->title;

?>

<div class="row justify-content-center">
    <div class="col-xs-12 col-md-10 col-lg-9 col-xl-8 col-xxl-7">
        <h1 class="mb-5 text-uppercase text-center">
            <?= $this->title ?>
        </h1>
        <p class="text-center">
            <?= Yii::t('front', 'Произошла ошибка! Пожалуйста, попробуйте еще раз чуть позже') ?>.
        </p>
    </div>
</div>