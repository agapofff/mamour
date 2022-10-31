<?php
use yii\helpers\Url;

$currency = Yii::$app->getModule('order')->currency;
?>
<div class=" order-informer">
	<table class="table table-hover table-responsive">
		<thead>
			<tr>
				<th>&nbsp;</th>
				<th><?=Yii::t('back', 'Today');?></th>
				<th><?=Yii::t('back', 'In month');?></th>
				<th><?=Yii::t('back', 'By month');?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?=Yii::t('back', 'Turnover');?></td>
				<td><?=round($today['total']);?><?=$currency; ?></td>
				<td><?=round($inMonth['total'], 2);?><?=$currency; ?></td>
				<td>
					<?=round($byMonth['total'], 2);?><?=$currency; ?>
					<?php
					if($byOldMonth['total']) {
						$cssClass = '';
						$sum = '+0';
						if($byOldMonth['total'] < $inMonth['total']) {
							$cssClass = 'good-result';
							$sum = '+'.($inMonth['total']-$byOldMonth['total']);
						} elseif($byOldMonth['total'] > $inMonth['total']) {
							$cssClass = 'bad-result';
							$sum = '-'.($byOldMonth['total']-$inMonth['total']);
						}
						?>
							<span class="result <?=$cssClass;?>"><?=$sum;?></span>
						<?php
					}
					?>
				</td>
			</tr>
			<tr>
				<td><?=Yii::t('back', 'Orders count');?></td>
				<td><?=round($today['count_orders'], 2);?></td>
				<td><?=round($inMonth['count_orders'], 2);?></td>
				<td><?=round($byMonth['count_orders'], 2);?></td>
			</tr>
			<tr>
				<td><?=Yii::t('back', 'Elements count');?></td>
				<td><?=round($today['count_elements'], 2);?></td>
				<td><?=round($inMonth['count_elements'], 2);?></td>
				<td><?=round($byMonth['count_elements'], 2);?></td>
			</tr>
			<tr>
				<td><?=Yii::t('back', 'Average check');?></td>
				<td><?php if($today['count_orders']) { ?><?=round($today['total']/$today['count_orders'], 2);?><?=$currency; ?><?php } else echo 0; ?></td>
				<td><?php if($inMonth['count_orders']) { ?><?=round($inMonth['total']/$inMonth['count_orders'], 2);?><?=$currency; ?><?php } else echo 0; ?></td>
				<td><?php if($byMonth['count_orders']) { ?><?=round($byMonth['total']/$byMonth['count_orders'], 2);?><?=$currency; ?><?php } else echo 0; ?></td>
			</tr>
		</tbody>
	</table>
</div>