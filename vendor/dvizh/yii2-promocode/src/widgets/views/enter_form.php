<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="promo-code-enter">

    <?php $form = ActiveForm::begin([
        'action' => ['/promocode/promo-code-use/enter'],
        'options' => [
            'data-role' => 'promocode-enter-form',
        ]
    ]); ?>
        <?php if(Yii::$app->promocode->has()) { ?>
            <p class="promo-code-discount">
                Ваша скидка: <?php
                    if (Yii::$app->promocode->get()->promocode->type === 'cumulative' && Yii::$app->promocode->get()->promocode->getTransactions()->all()) {
                    echo 0;
                } else {
                    echo Yii::$app->promocode->get()->promocode->discount;
                }
                ?>
                <?php if (Yii::$app->promocode->get()->promocode->type != 'quantum') {
                    echo '%';
                } else {
                    echo ' рублей';
                } ?>
            </p>
        <?php } else { ?>
            <p class="promo-code-discount" style="display: none;"></p>
        <?php } ?>
        <div class="input-group">
            <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
            <?=Html::input('text', 'promocode', Yii::$app->promocode->getCode(), ['class' => 'form-control', 'placeholder' => 'Промокод']) ?>
            <span class="input-group-btn">
                <?= Html::submitButton('<i class="glyphicon glyphicon-ok"></i>', ['class' => 'btn btn-success promo-code-enter-btn']) ?>
                <?= Html::submitButton('<i class="glyphicon glyphicon-remove"></i>', ['class' => 'btn btn-danger promo-code-clear-btn']) ?>
            </span>
        </div>
    <?php ActiveForm::end(); ?>

</div>
