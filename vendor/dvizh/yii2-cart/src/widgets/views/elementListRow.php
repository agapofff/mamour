<?php
use yii\helpers\Html;
use yii\helpers\Url;
use dvizh\cart\widgets\ChangeCount;
use dvizh\cart\widgets\DeleteButton;
use dvizh\cart\widgets\ElementPrice;
use dvizh\cart\widgets\ElementCost;

if ($options && !empty($allOptions)) {
	$productOptions = '';
	foreach ($options as $optionId => $valueId)
	{
		if ($optionId == 1){
			if ($optionData = $allOptions[$optionId]) {
				$optionName = $optionData['name'];
				$optionValue = $valueId == 1 ? '' : $optionData['variants'][$valueId];
				// $productOptions .= Html::tag('div', Html::tag('strong', Yii::t('front', $optionName)) . ': ' . Html::tag('span', $optionValue, [
					// 'class' => 'cart-product-variant'
				// ]));
			}
		}
	}
	// echo Html::tag('div', $productOptions, [
		// 'class' => 'dvizh-cart-show-options'
	// ]);
}

?>

<div class="cart-product" data-product-id="<?= $model->item_id ?>" data-currency="<?= $currency ?>" data-id="<?= $model->comment ?>" data-name="<?= $name ?>" data-price="<?= round($model->price) ?>">
    <div class="row">
        <div class="col-4">
			<a href="<?= $url ?>">
				<img src="<?= $image ?>" class="img-fluid">
			</a>
		</div>
		<div class="col-5">
			<div class="row h-100">
				<div class="col-12 align-self-start">
					<p class="font-weight-bold">
						<?= $name ?> <?= $optionValue ?>
					</p>
                    
                <?php
                    if ($model->item_id == Yii::$app->params['gift']['product_id']) {
                ?>
                        <p class="text-danger"><?= Yii::t('front', 'Подарок') ?></p>
                <?php
                    }
                ?>
                    
                    <?php 
                        if (!empty($otherFields))
                        {
                            foreach ($otherFields as $fieldName => $field)
                            {
                                if (isset($product->$field)){
                                    echo Html::tag('p', $fieldName . ': ' . Html::tag('strong', $product->$field));
                                }
                            }
                        }
                    ?>
				</div>
				<div class="col-12 align-self-end">
                    <?= ChangeCount::widget([
							'model' => $model,
							'showArrows' => $showCountArrows,
							'actionUpdateUrl' => Url::to([$controllerActions['update']]),
						]);
					?>
				</div>
			</div>
		</div>
		<div class="col-3">
			<div class="row h-100">
				<div class="col-12 align-self-start text-right">
                    <?= ElementPrice::widget([
							'model' => $model,
							'currency' => $currency,
							'htmlTag' => 'span',
							'cssClass' => 'font-weight-bold text-nowrap',
						]);
                    ?>
				</div>
				<div class="col-12 align-self-end text-right mb-0_5">
					<?= DeleteButton::widget([
							'model' => $model,
							'deleteElementUrl' => Url::to([$controllerActions['delete']]),
							'lineSelector' => 'list-group-item',
							'cssClass' => 'delete',
							'text' => Yii::t('front', 'Удалить'),
						])
					?>
				</div>
			</div>
		</div>
	</div>
</div>

<hr class="my-1_5">