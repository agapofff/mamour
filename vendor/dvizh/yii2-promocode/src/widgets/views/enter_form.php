<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

?>

<?php 
    Pjax::begin([
        'id' => 'pjax_promo_code',
    ]);
?>

    <div class="form-group row mb-0">
        <label for="promocode" class="col-sm-3 col-form-label">
            <?= Yii::t('front', 'Промокод') ?>
        </label>
        <div class="col-sm-9 promo-code-enter">

            <?php 
                $form = ActiveForm::begin([
                    'action' => [
                        '/promocode/promo-code-use/enter'
                    ],
                    'options' => [
                        'data-role' => 'promocode-enter-form',
                    ]
                ]); 
            ?>
                <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
                
                <div class="form-group mb-0 position-relative floating-label">                
                    <?= Html::input('text', 'promocode', Yii::$app->promocode->getCode(), [
                            'id' => 'promocode',
                            'class' => 'form-control py-1 px-0 bg-transparent border-top-0 border-left-0 border-right-0 border-dark outline-0 shadow-none montserrat font-weight-bold' . (Yii::$app->promocode->has() ? ' disabled pointer-events-none' : ''), 
                            'placeholder' => Yii::t('front', 'Промокод'),
                            // 'disabled' => (Yii::$app->promocode->has() ? 'disabled' : 'false')
                        ]) 
                    ?>
                    
                    <span class="position-absolute top-0 right-0" style="z-index: 3">
                        <?= Html::submitButton('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2" viewBox="0 0 16 16"><path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/></svg>', [
                                'class' => 'btn btn-link promo-code-enter-btn px-0',
                                'style' => 'display: ' . (Yii::$app->promocode->has() ? 'none' : 'block')
                            ]) 
                        ?>
                        <?= Html::submitButton('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16"><path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/></svg>', [
                                'class' => 'btn btn-link promo-code-clear-btn px-0',
                                'style' => 'display: ' . (Yii::$app->promocode->has() ? 'block' : 'none')
                            ]) 
                        ?>
                    </span>
                                
                <?php 
                    if (Yii::$app->promocode->has()) { 
                ?>
                        <div class="help-block promo-code-discount small font-weight-light">
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
                        </div>
                <?php 
                    } else { 
                ?>
                        <div class="help-block promo-code-discount" style="display: none;"></div>
                <?php 
                    } 
                ?>
                </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
<?php
    Pjax::end();
?>