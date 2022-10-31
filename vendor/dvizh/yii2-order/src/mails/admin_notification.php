<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\helpers\ArrayHelper;
    use yii\grid\GridView;
    use yii\widgets\DetailView;

    $address = [];
    $delivery_cost = null;
    $delivery_name = null;
    $delivery_comment = null;
	
	$userId = null;
	$paymentUrl = null;
    
    if ($fields = $model->fields) {
        foreach ($fields as $fieldModel) {
            if ($fieldModel->field->name == 'postcode'){
                $address[] = $fieldModel->value;
                break;
            }
        }
        foreach ($fields as $fieldModel) {
            if ($fieldModel->field->name == 'country_name'){
                $address[] = $fieldModel->value;
                break;
            }
        }
        foreach ($fields as $fieldModel) {
            if ($fieldModel->field->name == 'city_name'){
                $address[] = $fieldModel->value;
                break;
            }
        }
        foreach ($fields as $fieldModel) {
            if ($fieldModel->field->name == 'delivery_cost'){
                $delivery_cost = $fieldModel->value;
                break;
            }
        }
        foreach ($fields as $fieldModel) {
            if ($fieldModel->field->name == 'delivery_name'){
                $delivery_name = $fieldModel->value;
                break;
            }
        }
        foreach ($fields as $fieldModel) {
            if ($fieldModel->field->name == 'delivery_comment'){
                $delivery_comment = $fieldModel->value;
                break;
            }
        }
		
        foreach ($fields as $fieldModel) {
            if ($fieldModel->field->name == 'log_response'){
				$logResponse = json_decode($fieldModel->value);
				$paymentUrl = $logResponse->payment_url;
                break;
            }
        }		
    }
    
    if ($model->address){
        $address[] = $model->address;
    }

?>

<h3 style="text-align: center">
	<?= Yii::t('front', 'Заказ'); ?> <?= $model->id ?>
</h3>

<ul>
    <li>
        <strong><?= Yii::t('front', 'Дата') ?></strong>: <?= $model->date;?> <?= $model->time; ?>
    </li>
    
<?php if ($model->client_name) { ?>
    <li>
        <strong><?= Yii::t('front', 'Имя') ?></strong>: <?= $model->client_name; ?>
    </li>
<?php } ?>

<?php if ($model->phone) { ?>
    <li>
        <strong><?= Yii::t('front', 'Телефон') ?></strong>: <?= $model->phone; ?>
    </li>
<?php } ?>

 <?php if ($model->email) { ?>
    <li>
        <strong><?= Yii::t('front', 'E-mail') ?></strong>: <?= Html::a($model->email, 'mailto:'.$model->email); ?>
    </li>
<?php } ?>

<?php if ($model->paymentType) { ?>
    <li>
        <strong><?= Yii::t('front', 'Способ оплаты') ?></strong>: <?= Yii::t('front', $model->paymentType->name) ?>
    </li>
<?php } ?>

<?php    
    if ($delivery_name){
?>
        <li>
            <strong><?= Yii::t('front', 'Способ доставки') ?></strong>: <?= $delivery_name ?><?php if ($delivery_cost){ ?> (<?= Yii::$app->formatter->asCurrency($delivery_cost, $currency) ?>)<?php } ?>
        </li>
<?php
    } else if ($model->shipping) {
?>
    <li>
        <strong><?= Yii::t('front', 'Способ доставки') ?></strong>: <?= Yii::t('front', $model->shipping->name) ?><?php if ($delivery_cost){ ?> (<?= Yii::$app->formatter->asCurrency($delivery_cost, $currency) ?>)<?php } ?>
    </li>
<?php
    }
?>

<?php if ($model->delivery_type == 'totime') { ?>
    <?= Yii::t('front', 'Delivery to time'); ?>
    <?= $model->delivery_time_date;?> <?= $model->delivery_time_hour; ?>:<?= $model->delivery_time_min;?>
<?php } ?>

<?php    
    if (!empty($address)){
?>
        <li>
            <strong><?= Yii::t('front', 'Адрес') ?></strong>: <?= implode(', ', $address) ?>
        </li>
<?php
    }
?>
    
<?php
    if ($model->comment){
?>
        <li>
            <strong><?= Yii::t('front', 'Комментарий') ?></strong>: <?= $model->comment ?>
        </li>
<?php
    }
?>

	<li>
		<a href="https://crm.sessia.com/shop/orders/edit/<?= $model->id ?>">
			<?= Yii::t('front', 'Открыть в CRM') ?>
		</a>
	</li>
	
<?php
    if ($paymentUrl){
?>
        <li>
            <strong><?= Yii::t('front', 'Ссылка на оплату') ?></strong>: <a href="<?= $paymentUrl ?>"><?= $paymentUrl ?></a>
        </li>
<?php
    }
?>

</ul>

<hr>

<?php
    if ($model->elements){
?>
    <table width="100%">
        <thead>
            <tr>
                <td colspan="2">
                    <?= Yii::t('front', 'Товар') ?>
                </td>
                <td style="text-align: center" align="right">
                    <?= Yii::t('front', 'Количество') ?>
                </td>
                <td style="text-align: right" align="center">
                    <?= Yii::t('front', 'Цена') ?>
                </td>
            </tr>
        </thead>
    <?php
        foreach ($model->elements as $element){
            $product_link = Url::to(['/product/' . $element->product->slug], true);
    ?>
            <tr>
                <td>
                    <a href="<?= $product_link ?>">
                        <img src="<?= Url::to($element->product->getImage()->getUrl('50x50'), true) ?>">
                    </a>
                </td>
                <td>
                    <p>
                        <a href="<?= $product_link ?>">
                            <?= json_decode($element->product->getCartName())->{Yii::$app->language} ?>
                        </a>
                    <?php
                        if ($options = json_decode($element->options)){
                            $cartOptions = $element->product->getCartOptions();
                    ?>
                            <br>
							<small>
								<strong><?= Yii::t('front', 'Размер') ?></strong>: <?= $cartOptions[1]['variants'][$options->{1}] ?>
							</small>
                    <?php
                        }
                    ?>
					</p>
                </td>
                <td style="text-align: center" align="center">
                    <?= $element->count ?>
                </td>
                <td style="text-align: right" align="right">
                    <?= Yii::$app->formatter->asCurrency($element->price, $currency) ?>
                </td>
            </tr>
			<tr>
				<td colspan="4">
					<hr>
				</td>
			</tr>
    <?php
        }
    ?>
    </table>
<?php
    }
?>

<h3 style="text-align: right">
    <?= Yii::t('front', 'Итого') ?>: <?= Yii::$app->formatter->asCurrency($model->cost, $currency) ?>
</h3>
