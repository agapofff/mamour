<?php

    use dvizh\cart\widgets\CartInformer;
    use dvizh\cart\widgets\ElementsList;
    use dvizh\order\widgets\OrderForm;
  
    use yii\web\View;
    
    $this->registerCss('
        @media (min-width: 992px) {
            body {
                background: linear-gradient(to right, white 50%, #F9F9F9 50%);
            }
        }
    ');
    
    $this->title = Yii::t('front', 'Оформить заказ');
?>

<div class="container-fluid mb-md-7 mt-1_5 px-lg-2 px-xl-3 px-xxl-5">
    <div class="row justify-content-center">
        <div class="col col-sm-10 col-md-8 col-lg-6 pr-lg-1_5">
            <?= OrderForm::widget();?>
        </div>
        <div class="col-lg-6 d-none d-lg-block pl-1_5">
            <div class="sticky-top" style="top:120px">
                <div class="row">
                    <div class="col-12">
                        <?= ElementsList::widget([
                                'type' => 'checkout',
                                'currency' => $currency,
                                'lang' => Yii::$app->language,                
                            ]);
                        ?>
                    </div>
                    <div class="col-12 text-right">
                        <?= CartInformer::widget([
                                'currency' => $currency
                            ]);
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
            




<?php
    // $this->registerJs("
        // $(document).ajaxComplete(function (event, xhr) {
            // var url = xhr && xhr.getResponseHeader('X-Redirect');
            // if (url) {
                // window.location.assign(url);
            // }
        // });
    // ",
    // View::POS_READY,
    // 'ajax-redirect');
?>