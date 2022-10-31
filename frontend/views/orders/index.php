<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\helpers\ArrayHelper;
    use dvizh\order\Module;
    
    $this->title = Yii::t('front', 'Заказы');
?>

<div class="container-xxl mt-3">    
    <div class="row justify-content-center justify-content-lg-start">
        <div class="col-sm-3 col-md-3 col-lg-2 col-xl-3 d-none d-md-block">
            <?= $this->render('@frontend/views/user/settings/_menu') ?>
        </div>
        <div class="col-sm-11 col-md-9 col-lg-8 col-xl-6 col-xxl-5">
            <h1 class="gotham font-weight-bold text-uppercase headline mb-3 mb-md-5">
                <?= $this->title ?>
            </h1>
    <?php
        if ($orders) {
    ?>
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center">
                            <?= Yii::t('front', 'Заказ') ?>
                        </th>
                        <th class="text-center">
                            <?= Yii::t('front', 'Дата') ?>
                        </th>
                        <th class="text-center d-none d-sm-table-cell d-lg-none d-xl-table-cell">
                            <?= Yii::t('front', 'Способ доставки') ?>
                        </th>
                        <th class="text-center d-none d-sm-table-cell">
                            <?= Yii::t('front', 'Итого') ?>
                        </th>
                        <th class="text-center">
                            <?= Yii::t('front', 'Статус') ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($orders as $order) {
                ?>
                    <tr onclick="window.location.href='<?= Url::to(['/orders/' . $order->id]) ?>'" class="cursor-pointer">
                        <td class="text-center align-middle">
                            <?= $order->id ?>
                        </td>
                        <td class="text-center align-middle">
                            <?= Yii::$app->formatter->asDate($order->date) ?>
                            <br>
                            <?= Yii::$app->formatter->asTime($order->time) ?>
                        </td>
                        <td class="text-center align-middle d-none d-sm-table-cell d-lg-none d-xl-table-cell">
                            <?= Yii::t('front', ArrayHelper::getValue(ArrayHelper::map($shippingTypes, 'id', 'name'), $order->shipping_type_id)) ?>
                        </td>
                        <td class="text-center align-middle d-none d-sm-table-cell">
                            <?= Yii::$app->formatter->asCurrency($order->cost, Yii::$app->params['currency']) ?>
                        </td>
                        <td class="text-center align-middle">
                            <?= Yii::t('front', Yii::$app->getModule('order')->orderStatuses[$order->status]) ?>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                </tbody>
            </table>
    <?php
        }
    ?>
        </div>
    </div>
</div>