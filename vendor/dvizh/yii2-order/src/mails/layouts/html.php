<?php
	use yii\helpers\Url;
?>

<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="font-family: Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;">
	<head>
		<meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<?php $this->head() ?>
		<style>
			* {
				font-family: Helvetica, Arial, sans-serif;
			}
			*, body, h1, h2, h3, h4, a, p, div, table, tr, td {
				color: #000 !important;
			}
			p, td, div {
				font-size: 16px;
			}
			h1 {
				font-size: 30px;
			}
		</style>
	</head>
	<body bgcolor="#fff">
		<div bgcolor="#fff" style="
			position: relative;
			font-family: Helvetica, Arial, sans-serif; 
			font-size: 100%; 
			line-height: 1.6; 
			-webkit-font-smoothing: antialiased; 
			-webkit-text-size-adjust: none; 
			width: 100% !important; 
			height: 100%; 
			margin: 0; 
			padding: 0; 
			background-color: #fff;
		">
			<table class="body-wrap" style="
				font-family: Helvetica, Arial, sans-serif; 
				font-size: 100%; 
				line-height: 1.6; 
				width: 100%; 
				margin: 0; 
				padding: 20px;
			">
				<tr style="
					font-family: Helvetica, Arial, sans-serif; 
					font-size: 100%; 
					line-height: 1.6; 
					margin: 0; 
					padding: 0;
				">
					<td style="
						font-family: Helvetica, Arial, sans-serif; 
						font-size: 100%; 
						line-height: 1.6; 
						margin: 30px 0; 
						padding: 0; 
						text-align: center;
					">
						<img src="<?= Url::to('/images/email/logo.png', true) ?>" style="
							display: block; 
							max-width: 100%; 
							margin: 0 auto;
						">
					</td>
				</tr>
				<tr style="
					font-family: Helvetica, Arial, sans-serif; 
					font-size: 100%; 
					line-height: 1.6; 
					margin: 0; 
					padding: 0;
				">
					<td class="container" style="
						font-family: Helvetica, Arial, sans-serif; 
						font-size: 100%; 
						line-height: 1.6; 
						display: block !important; 
						max-width: 600px !important; 
						clear: both !important; 
						margin: 0 auto; 
						padding: 0; 
						color: #000; 
					">
						<div class="content" style="
							font-family: Helvetica, Arial, sans-serif; 
							font-size: 100%; 
							line-height: 1.6; 
							max-width: 600px; 
							display: block; 
							margin: 0 auto; 
							padding: 20px;
						">
							<table style="
								font-family: Helvetica, Arial, sans-serif; 
								font-size: 100%; 
								line-height: 1.6; 
								width: 100%; 
								margin: 0; 
								padding: 0;
							">
								<tr style="
									font-family: Helvetica, Arial, sans-serif;
									font-size: 100%; 
									line-height: 1.6; 
									margin: 0; 
									padding: 0;
								">
									<td style="
										font-family: Helvetica, Arial, sans-serif; 
										font-size: 100%; 
										line-height: 1.6; 
										margin: 0; 
										padding: 0; 
										color: #000; 
									">
										<?php $this->beginBody() ?>
										<?= $content ?>
										<?php $this->endBody() ?>
									</td>
								</tr>
							</table>
						</div>
					</td>
					<td style="
						font-family: Helvetica, Arial, sans-serif; 
						font-size: 100%; 
						line-height: 1.6; 
						margin: 0; 
						padding: 0;
					"></td>
				</tr>
			</table>
			
			<table class="footer-wrap" style="
				font-family: Helvetica, Arial, sans-serif; 
				font-size: 100%; 
				line-height: 1.6; 
				width: 100%; 
				clear: both !important; 
				margin: 0; 
				padding: 0;
			">
				<tr style="
					font-family: Helvetica, Arial, sans-serif; 
					font-size: 100%; 
					line-height: 1.6; 
					margin: 0; 
					padding: 0;
				">
					<td style="
						font-family: Helvetica, Arial, sans-serif; 
						font-size: 100%; 
						line-height: 1.6; 
						margin: 0; 
						padding: 0;
					"></td>
					<td class="container" style="
						font-family: Helvetica, Arial, sans-serif; 
						font-size: 100%; 
						line-height: 1.6; 
						display: block !important; 
						max-width: 600px !important; 
						clear: both !important; 
						margin: 0 auto; 
						padding: 0;
					">
						<div class="content" style="
							font-family: Helvetica, Arial, sans-serif; 
							font-size: 100%; 
							line-height: 1.6; 
							max-width: 600px; 
							display: block; 
							margin: 0 auto; 
							padding: 20px;
						">
							<table style="
								font-family: Helvetica, Arial, sans-serif; 
								font-size: 100%; 
								line-height: 1.6; 
								width: 100%; 
								margin: 0; 
								padding: 0;
							">
								<tr style="
									font-family: Helvetica, Arial, sans-serif; 
									font-size: 100%; 
									line-height: 1.6; 
									margin: 0; 
									padding: 0;
								">
									<td align="center" style="
										font-family: Helvetica, Arial, sans-serif; 
										font-size: 100%; 
										line-height: 1.6; 
										margin: 0; 
										padding: 0;
									">
										<p style="
											font-family: Helvetica, Arial, sans-serif; 
											font-size: 12px; 
											line-height: 1.6; 
											color: #000; 
											font-weight: normal; 
											margin: 0 0 10px; 
											padding: 0;
										">
											© <?= date('Y') ?> <?= Yii::$app->name ?>
										</p>
									</td>
								</tr>
							</table>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
<?php $this->endPage() ?>
