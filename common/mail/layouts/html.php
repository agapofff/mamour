<?php
	use yii\helpers\Url;
?>

<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="font-family: Montserrat, Helvetica, Arial, sans-serif; font-size: 100%; line-height: 1.6; margin: 0; padding: 0;">
	<head>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <meta name="viewport" content="width=device-width" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<?php $this->head() ?>
		<style>
			* {
				font-family: Montserrat, Helvetica, Arial, sans-serif;
			}
			*, body, h1, h2, h3, h4, a, p, div, table, tr, td {
				color: #000 !important;
			}
			p, td, div {
				font-size: 16px;
			}
			h1 {
				font-size: 25px;
			}
		</style>
	</head>
	<body bgcolor="#fff">
		<div bgcolor="#fff" style="
			position: relative;
			font-family: Montserrat, Helvetica, Arial, sans-serif; 
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
				font-family: Montserrat, Helvetica, Arial, sans-serif; 
				font-size: 100%; 
				line-height: 1.6; 
				width: 100%; 
				margin: 0; 
				padding: 20px;
			">
				<tr style="
					font-family: Montserrat, Helvetica, Arial, sans-serif; 
					font-size: 100%; 
					line-height: 1.6; 
					margin: 0; 
					padding: 0;
				">
					<td style="
						font-family: Montserrat, Helvetica, Arial, sans-serif; 
						font-size: 100%; 
						line-height: 1.6; 
						margin: 30px 0; 
						padding: 0; 
						text-align: center;
					">
						<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTcwIiBoZWlnaHQ9IjEyMiIgdmlld0JveD0iMCAwIDE3MCAxMjIiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGQ9Ik0xMDUuNTcxIDc3LjQxNzZDMTEwLjc4NCA3MS4yOTQyIDExNS44MSA2NC44MjcxIDExOS44MDUgNTcuNzk3NkMxMjEuODAzIDU0LjMyOTggMTIzLjM5NSA1MC43MzY5IDEyNC40ODggNDYuODYyOUMxMjUuODMgNDIuMDUxNyAxMjYuNjczIDM3LjA4NDIgMTI2Ljc5OCAzMi4xMTY3QzEyNi45MjMgMjcuNTI0MSAxMjYuNTQ4IDIyLjcxMjkgMTI1LjE3NCAxOC4zMDc4QzEyNC4xMTMgMTQuODcxMSAxMjIuMjcxIDExLjQ2NTggMTE5LjIxMiA5LjQzNTAyQzExMS41OTYgNC40MDUwNiAxMDIuMzI1IDExLjQzNDUgOTcuNDg2MiAxNy4xODNDOTIuMjczMiAyMy40MDAyIDg5LjMwNzcgMzEuMjQxOSA4OC4zNDAxIDM5LjIzOTlDODguMjE1MiA0MC4yMzk2IDg4LjEyMTYgNDEuMjA4MSA4OC4wOTAzIDQyLjIwNzlDODguNDMzNyA0Mi4xNDU0IDg4Ljc3NzEgNDIuMTE0MiA4OS4xNTE3IDQyLjA1MTdDODYuNDA0NyAzNC4yMDk5IDgzLjU5NTMgMjYuMjc0NSA3OC44NTA1IDE5LjM3Qzc2LjcyNzkgMTYuMzA4MyA3NC4yOTMxIDEzLjQzNCA3MS40NTI0IDExLjAyODRDNjguNTQ5NCA4LjU2MDI1IDY1LjExNTcgNi4yMTcxIDYxLjMzODYgNS4zMTEwOEM1Ny42ODY0IDQuNDM2MyA1NC4xMjc4IDUuMzQyMzIgNTEuMzE4NCA3Ljg0MTY4QzQ4LjY5NjMgMTAuMTg0OCA0Ny4wNzMxIDEzLjQwMjggNDUuOTgwNSAxNi42ODMyQzQzLjM4OTYgMjQuNDYyNCA0NC4xMDc2IDMyLjg5NzggNDYuMDc0MiA0MC43Mzk1QzQ3Ljk0NzEgNDguMjA2MyA1MC45MTI2IDU1LjQyMzIgNTQuMjUyNyA2Mi4zMjc3QzU3LjQ5OTEgNjkuMDEzNSA2MS4yNzYyIDc1LjQ0OTQgNjUuNTIxNSA4MS41NzI4QzY5Ljc5OCA4Ny42OTYyIDc0LjUxMTYgOTMuNTA3MiA3OS42NjIxIDk4Ljk0MzRDODQuODc1MSAxMDQuNDQyIDkwLjQ5MzkgMTA5LjUzNCA5Ni40NTYxIDExNC4yMjFDOTcuNzM2IDExNS4yMiA5OS4wMTU4IDExNi4yODMgMTAwLjM1OCAxMTcuMTU3QzEwMS4yMDEgMTE3LjcyIDEwMi4yNjIgMTE4LjA5NSAxMDMuMjkyIDExOC4wNjNDMTA0LjU0MSAxMTguMDMyIDEwNS45MTQgMTE3LjEyNiAxMDYuMTMzIDExNS44MTRDMTA2LjMyIDExNC43ODMgMTA1LjQ3NyAxMTQuMDAyIDEwNC42MzUgMTEzLjUzM0MxMDMuNjA0IDExMy4wMDIgMTAyLjQ4MSAxMTIuNzUyIDEwMS4zNTcgMTEyLjQ3MUM5OS4zMjggMTExLjk0IDk3LjI2NzcgMTExLjM3OCA5NS4yMzg3IDExMC44NDdDOTQuNzcwNSAxMTAuNzIyIDk0LjMzMzUgMTEwLjU5NyA5My44NjUyIDExMC40NzJDOTMuMjA5NyAxMTAuMjg0IDkyLjkyODggMTExLjMxNSA5My41ODQzIDExMS41MDNDOTYuNDg3MyAxMTIuMjg0IDk5LjM5MDQgMTEzLjA5NiAxMDIuMjkzIDExMy44MTVDMTAzLjEwNSAxMTQuMDMzIDEwNS41NCAxMTQuNjI3IDEwNC45NzggMTE1Ljg0NUMxMDQuMzg1IDExNy4xMjYgMTAyLjg4NyAxMTcuMDk1IDEwMS43NjMgMTE2LjYyNkMxMDAuNzk1IDExNi4yNTEgMTAwLjAxNSAxMTUuNTMzIDk5LjIwMzEgMTE0LjkwOEM5My4zNjU4IDExMC41MzQgODcuODcxOCAxMDUuNjkyIDgyLjc1MjUgMTAwLjUwNUM3Ny42OTU2IDk1LjQxMyA3My4wMTMyIDg5LjkxNDQgNjguNzM2NyA4NC4xMzQ2QzY0LjUyMjYgNzguNDE3NCA2MC43NDU1IDcyLjM4NzcgNTcuNDA1NCA2Ni4xMDhDNTQuMDY1NCA1OS43OTcxIDUxLjEzMTEgNTMuMjA1MSA0OC44ODM2IDQ2LjM5NDNDNDYuMzg2MyAzOC45NTg3IDQ0LjcwMDcgMzEuMDIzMiA0NS42MDU5IDIzLjE1MDNDNDYuMDQzIDE5LjQ2MzcgNDcuMDczMSAxNS42ODM0IDQ4LjkxNDggMTIuNDM0M0M1MC41MDY4IDkuNjUzNzIgNTIuODc5MiA3LjEyMzEyIDU2LjAzMTkgNi4yNDgzNEM1OS44NzE1IDUuMTU0ODcgNjMuNzczNCA2LjkzNTY2IDY2Ljk1NzQgOC45NjYzOUM2OS45ODUzIDEwLjkwMzQgNzIuNzAxMSAxMy4zNDAzIDc1LjA0MjIgMTYuMDg5NkM4MC4xNjE2IDIyLjA1NjggODMuMzc2OCAyOS4zMzYyIDg2LjA5MjUgMzYuNjQ2OEM4Ni43NzkzIDM4LjUyMTMgODcuNDM0OCA0MC4zNjQ2IDg4LjA5MDMgNDIuMjM5MUM4OC4yNzc2IDQyLjgwMTUgODkuMDg5MiA0Mi43MDc4IDg5LjE1MTcgNDIuMDgyOUM4OS42MTk5IDM0LjMzNDkgOTEuOTI5OSAyNi42MTgxIDk2LjM5MzcgMjAuMjQ0OEM5OC42NDEyIDE2Ljk5NTYgMTAxLjQ4MiAxNC4xMjEzIDEwNC43NTkgMTEuODcxOUMxMDguMjI0IDkuNDk3NTEgMTEyLjcxOSA3LjcxNjcxIDExNi45MDIgOS4yNzg4MUMxMjAuMjQyIDEwLjUyODUgMTIyLjQyNyAxMy43MTUyIDEyMy42NDUgMTYuOTMzMUMxMjUuMjA2IDIwLjk2MzMgMTI1LjcwNSAyNS40MzA5IDEyNS43NjggMjkuNzExMUMxMjUuODYxIDM0LjUyMjMgMTI1LjIzNyAzOS4zNjQ5IDEyNC4xMTMgNDQuMDUxMkMxMjMuNjE0IDQ2LjExMzEgMTIzLjA1MiA0OC4yMDYzIDEyMi4zMDMgNTAuMjA1OEMxMjEuNjQ3IDUxLjkyNDEgMTIwLjgzNSA1My42MTEyIDExOS45OTMgNTUuMjM1OEMxMTYuMjc4IDYyLjIwMjggMTExLjQ0IDY4LjYwNzQgMTA2LjQxNCA3NC42OTk2QzEwNS44ODMgNzUuMzI0NCAxMDUuMzg0IDc1Ljk0OTIgMTA0Ljg1MyA3Ni41NzQxQzEwNC4zODUgNzcuMTk4OSAxMDUuMTM0IDc3Ljk0ODcgMTA1LjU3MSA3Ny40MTc2WiIgZmlsbD0iI0I4OTk0RiIvPgo8cGF0aCBkPSJNMTUuNDIwNSA3My42Mzc0SDEzLjgyODVMOC40MjgyMSA2MS4zOTA1SDguMTQ3MjdWNzguMTA1SDEwLjczODJDMTEuMDgxNSA3OC4xMDUgMTEuMyA3OC4xNjc1IDExLjQ1NjEgNzguMjkyNEMxMS42MTIyIDc4LjQxNzQgMTEuNjc0NiA3OC42MDQ4IDExLjY3NDYgNzguNzkyM0MxMS42NzQ2IDc4Ljk3OTggMTEuNjEyMiA3OS4xNjcyIDExLjQ1NjEgNzkuMjkyMkMxMS4zIDc5LjQxNzEgMTEuMDgxNSA3OS40Nzk2IDEwLjczODIgNzkuNDc5Nkg1LjU4NzU5QzUuMjQ0MjIgNzkuNDc5NiA1LjAyNTcxIDc5LjQxNzEgNC44Njk2MyA3OS4yOTIyQzQuNzEzNTUgNzkuMTY3MiA0LjY1MTEyIDc4Ljk3OTggNC42NTExMiA3OC43OTIzQzQuNjUxMTIgNzguNTczNiA0LjcxMzU1IDc4LjQxNzQgNC44Njk2MyA3OC4yOTI0QzUuMDI1NzEgNzguMTY3NSA1LjI0NDIyIDc4LjEwNSA1LjU4NzU5IDc4LjEwNUg2Ljc3Mzc4VjYxLjM5MDVINS44OTk3NUM1LjU1NjM3IDYxLjM5MDUgNS4zMzc4NyA2MS4zMjggNS4xODE3OSA2MS4yMDMxQzUuMDI1NzEgNjEuMDc4MSA0Ljk2MzI4IDYwLjg5MDYgNC45NjMyOCA2MC43MDMyQzQuOTYzMjggNjAuNDg0NSA1LjAyNTcxIDYwLjMyODMgNS4xODE3OSA2MC4yMDMzQzUuMzM3ODcgNjAuMDc4MyA1LjU1NjM3IDYwLjAxNTkgNS44OTk3NSA2MC4wMTU5SDkuMzMzNDZMMTQuNjQwMSA3Mi4xMDY1TDE5LjgyMTkgNTkuOTg0NkgyMy4yNTU2QzIzLjU5OSA1OS45ODQ2IDIzLjg0ODcgNjAuMDQ3MSAyMy45NzM2IDYwLjE3MjFDMjQuMTI5NiA2MC4yOTcgMjQuMTkyMSA2MC40ODQ1IDI0LjE5MjEgNjAuNjcxOUMyNC4xOTIxIDYwLjg5MDYgMjQuMTI5NiA2MS4wNDY4IDIzLjk3MzYgNjEuMTcxOEMyMy44MTc1IDYxLjI5NjggMjMuNTk5IDYxLjM1OTMgMjMuMjU1NiA2MS4zNTkzSDIyLjM4MTZWNzguMTA1SDIzLjUzNjVDMjMuODc5OSA3OC4xMDUgMjQuMTI5NiA3OC4xNjc1IDI0LjI1NDUgNzguMjkyNEMyNC40MTA2IDc4LjQxNzQgMjQuNDczIDc4LjYwNDggMjQuNDczIDc4Ljc5MjNDMjQuNDczIDc4Ljk3OTggMjQuNDEwNiA3OS4xNjcyIDI0LjI1NDUgNzkuMjkyMkMyNC4wOTg0IDc5LjQxNzEgMjMuODc5OSA3OS40Nzk2IDIzLjUzNjUgNzkuNDc5NkgxOC40MTcyQzE4LjA3MzggNzkuNDc5NiAxNy44NTUzIDc5LjQxNzEgMTcuNjk5MiA3OS4yOTIyQzE3LjU0MzIgNzkuMTY3MiAxNy40ODA3IDc4Ljk3OTggMTcuNDgwNyA3OC43OTIzQzE3LjQ4MDcgNzguNTczNiAxNy41NDMyIDc4LjQxNzQgMTcuNjk5MiA3OC4yOTI0QzE3Ljg1NTMgNzguMTY3NSAxOC4wNzM4IDc4LjEwNSAxOC40MTcyIDc4LjEwNUgyMS4wMDgxVjYxLjM5MDVIMjAuNjk1OUwxNS40MjA1IDczLjYzNzRaIiBmaWxsPSIjQjg5OTRGIi8+CjxwYXRoIGQ9Ik00Ni40MTc2IDY2LjQ4MjlIMzcuMjA5TDM1LjMzNiA3MS42MDY2SDM4LjAyMDZDMzguMzYzOSA3MS42MDY2IDM4LjU4MjUgNzEuNjY5MSAzOC43Mzg1IDcxLjc5NEMzOC44OTQ2IDcxLjkxOSAzOC45NTcgNzIuMTA2NCAzOC45NTcgNzIuMjkzOUMzOC45NTcgNzIuNDgxMyAzOC44OTQ2IDcyLjY2ODggMzguNzM4NSA3Mi43OTM4QzM4LjU4MjUgNzIuOTE4NyAzOC4zNjM5IDcyLjk4MTIgMzguMDIwNiA3Mi45ODEySDMyLjc3NjRDMzIuNDMzIDcyLjk4MTIgMzIuMjE0NSA3Mi45MTg3IDMyLjA1ODQgNzIuNzkzOEMzMS45MDIzIDcyLjY2ODggMzEuODM5OSA3Mi40ODEzIDMxLjgzOTkgNzIuMjkzOUMzMS44Mzk5IDcyLjA3NTIgMzEuOTAyMyA3MS45MTkgMzIuMDU4NCA3MS43OTRDMzIuMjE0NSA3MS42NjkxIDMyLjQzMyA3MS42MDY2IDMyLjc3NjQgNzEuNjA2NkgzMy44Njg5TDQwLjA0OTYgNTQuODkyMUgzNS44OTc5QzM1LjU1NDUgNTQuODkyMSAzNS4zMzYgNTQuODI5NiAzNS4xOCA1NC43MDQ3QzM1LjAyMzkgNTQuNTc5NyAzNC45NjE0IDU0LjM5MjIgMzQuOTYxNCA1NC4yMDQ4QzM0Ljk2MTQgNTMuOTg2MSAzNS4wMjM5IDUzLjgyOTkgMzUuMTggNTMuNzA0OUMzNS4zMzYgNTMuNTc5OSAzNS41NTQ1IDUzLjUxNzUgMzUuODk3OSA1My41MTc1SDQyLjk1MjZMNDkuNzg4OCA3MS42Mzc4SDUwLjg4MTRDNTEuMjI0OCA3MS42Mzc4IDUxLjQ0MzMgNzEuNzAwMyA1MS41OTkzIDcxLjgyNTNDNTEuNzU1NCA3MS45NTAyIDUxLjgxNzkgNzIuMTM3NyA1MS44MTc5IDcyLjMyNTFDNTEuODE3OSA3Mi41MTI2IDUxLjc1NTQgNzIuNyA1MS41OTkzIDcyLjgyNUM1MS40NDMzIDcyLjk1IDUxLjIyNDggNzMuMDEyNSA1MC44ODE0IDczLjAxMjVINDUuNjY4NEM0NS4zMjUgNzMuMDEyNSA0NS4wNzUzIDcyLjk1IDQ0Ljk1MDQgNzIuODI1QzQ0Ljc5NDQgNzIuNyA0NC43MzE5IDcyLjUxMjYgNDQuNzMxOSA3Mi4zMjUxQzQ0LjczMTkgNzIuMTA2NCA0NC43OTQ0IDcxLjk1MDIgNDQuOTUwNCA3MS44MjUzQzQ1LjEwNjUgNzEuNzAwMyA0NS4zMjUgNzEuNjM3OCA0NS42Njg0IDcxLjYzNzhINDguMzIxN0w0Ni40MTc2IDY2LjQ4MjlaTTQ1Ljg4NjkgNjUuMDc3TDQyLjAxNjIgNTQuODkyMUg0MS40ODU1TDM3LjczOTYgNjUuMDc3SDQ1Ljg4NjlaIiBmaWxsPSIjQjg5OTRGIi8+CjxwYXRoIGQ9Ik03NC40MTc5IDczLjYzNzRINzIuODI1OUw2Ny40MjU2IDYxLjM5MDVINjcuMTQ0N1Y3OC4xMDVINjkuNzM1NkM3MC4wNzkgNzguMTA1IDcwLjI5NzUgNzguMTY3NSA3MC40NTM1IDc4LjI5MjRDNzAuNjA5NiA3OC40MTc0IDcwLjY3MjEgNzguNjA0OSA3MC42NzIxIDc4Ljc5MjNDNzAuNjcyMSA3OC45Nzk4IDcwLjYwOTYgNzkuMTY3MiA3MC40NTM1IDc5LjI5MjJDNzAuMjk3NSA3OS40MTcxIDcwLjA3OSA3OS40Nzk2IDY5LjczNTYgNzkuNDc5Nkg2NC41NTM4QzY0LjIxMDQgNzkuNDc5NiA2My45OTE5IDc5LjQxNzEgNjMuODM1OCA3OS4yOTIyQzYzLjY3OTggNzkuMTY3MiA2My42MTczIDc4Ljk3OTggNjMuNjE3MyA3OC43OTIzQzYzLjYxNzMgNzguNTczNiA2My42Nzk4IDc4LjQxNzQgNjMuODM1OCA3OC4yOTI0QzYzLjk5MTkgNzguMTY3NSA2NC4yMTA0IDc4LjEwNSA2NC41NTM4IDc4LjEwNUg2NS43NFY2MS4zOTA1SDY0Ljg2NkM2NC41MjI2IDYxLjM5MDUgNjQuMzA0MSA2MS4zMjggNjQuMTQ4IDYxLjIwMzFDNjMuOTkxOSA2MS4wNzgxIDYzLjkyOTUgNjAuODkwNiA2My45Mjk1IDYwLjcwMzJDNjMuOTI5NSA2MC40ODQ1IDYzLjk5MTkgNjAuMzI4MyA2NC4xNDggNjAuMjAzM0M2NC4zMDQxIDYwLjA3ODQgNjQuNTIyNiA2MC4wMTU5IDY0Ljg2NiA2MC4wMTU5SDY4LjI5OTdMNzMuNjA2MyA3Mi4xMDY1TDc4LjgxOTMgNjAuMDE1OUg4Mi4yNTNDODIuNTk2NCA2MC4wMTU5IDgyLjg0NjEgNjAuMDc4NCA4Mi45NzEgNjAuMjAzM0M4My4xMjcxIDYwLjMyODMgODMuMTg5NSA2MC41MTU3IDgzLjE4OTUgNjAuNzAzMkM4My4xODk1IDYwLjkyMTkgODMuMTI3MSA2MS4wNzgxIDgyLjk3MSA2MS4yMDMxQzgyLjgxNDkgNjEuMzI4IDgyLjU5NjQgNjEuMzkwNSA4Mi4yNTMgNjEuMzkwNUg4MS4zNzlWNzguMTA1SDgyLjUzNEM4Mi44NzczIDc4LjEwNSA4My4xMjcxIDc4LjE2NzUgODMuMjUxOSA3OC4yOTI0QzgzLjQwOCA3OC40MTc0IDgzLjQ3MDQgNzguNjA0OSA4My40NzA0IDc4Ljc5MjNDODMuNDcwNCA3OC45Nzk4IDgzLjQwOCA3OS4xNjcyIDgzLjI1MTkgNzkuMjkyMkM4My4wOTU5IDc5LjQxNzEgODIuODc3MyA3OS40Nzk2IDgyLjUzNCA3OS40Nzk2SDc3LjQxNDZDNzcuMDcxMyA3OS40Nzk2IDc2Ljg1MjcgNzkuNDE3MSA3Ni42OTY3IDc5LjI5MjJDNzYuNTQwNiA3OS4xNjcyIDc2LjQ3ODIgNzguOTc5OCA3Ni40NzgyIDc4Ljc5MjNDNzYuNDc4MiA3OC41NzM2IDc2LjU0MDYgNzguNDE3NCA3Ni42OTY3IDc4LjI5MjRDNzYuODUyNyA3OC4xNjc1IDc3LjA3MTMgNzguMTA1IDc3LjQxNDYgNzguMTA1SDgwLjAwNTVWNjEuMzkwNUg3OS42OTM0TDc0LjQxNzkgNzMuNjM3NFoiIGZpbGw9IiNCODk5NEYiLz4KPHBhdGggZD0iTTExMC41MzQgNzguMTY3NEMxMTAuNTM0IDgwLjA0MTkgMTEwLjE2IDgxLjc2MDIgMTA5LjQxMSA4My4zNTM2QzEwOC42NjEgODQuOTQ2OSAxMDcuNiA4Ni4xOTY2IDEwNi4yODkgODcuMDcxNEMxMDQuOTQ3IDg3Ljk0NjIgMTAzLjU0MiA4OC40MTQ4IDEwMi4wMTIgODguNDE0OEM5OS43NjUgODguNDE0OCA5Ny43NjcyIDg3LjQ0NjMgOTYuMDUwMyA4NS41NDA1Qzk0LjMzMzUgODMuNjM0OCA5My40NTk0IDgxLjE2NjYgOTMuNDU5NCA3OC4xNjc0QzkzLjQ1OTQgNzUuMTY4MiA5NC4zMzM1IDcyLjcwMDEgOTYuMDUwMyA3MC43NjMxQzk3Ljc2NzIgNjguODU3MyA5OS43NjUgNjcuODg4OCAxMDIuMDEyIDY3Ljg4ODhDMTAzLjU0MiA2Ny44ODg4IDEwNC45NDcgNjguMzI2MiAxMDYuMjg5IDY5LjIzMjJDMTA3LjYgNzAuMTA3IDEwOC42NjEgNzEuMzU2NyAxMDkuNDExIDcyLjk1QzExMC4xNiA3NC41NzQ2IDExMC41MzQgNzYuMjkyOSAxMTAuNTM0IDc4LjE2NzRaTTEwOS4wOTggNzguMTY3NEMxMDkuMDk4IDc1Ljg4NjcgMTA4LjQ0MyA3My44NTYgMTA3LjEwMSA3Mi4wMTI3QzEwNS43OSA3MC4yMDA3IDEwNC4wNzMgNjkuMjk0NyAxMDEuOTgxIDY5LjI5NDdDMTAwLjAxNSA2OS4yOTQ3IDk4LjMyOSA3MC4xNjk1IDk2LjkyNDMgNzEuODg3OEM5NS41MTk2IDczLjYwNjEgOTQuODMyOSA3NS42OTkzIDk0LjgzMjkgNzguMTY3NEM5NC44MzI5IDgwLjc2MDUgOTUuNTUwOSA4Mi44ODUgOTcuMDE4IDg0LjU0MDhDOTguNDg1MSA4Ni4xOTY2IDEwMC4xNCA4Ny4wMDg5IDEwMS45NSA4Ny4wMDg5QzEwNC4wNDEgODcuMDA4OSAxMDUuNzU4IDg2LjEwMjkgMTA3LjA2OSA4NC4yOTA4QzEwOC40NDMgODIuNTEgMTA5LjA5OCA4MC40NzkzIDEwOS4wOTggNzguMTY3NFoiIGZpbGw9IiNCODk5NEYiLz4KPHBhdGggZD0iTTEzNy4xOTIgNTkuNDg0OFY3MS4yMDA1QzEzNy4xOTIgNzMuMTY4OCAxMzYuNTM3IDc0LjgyNDYgMTM1LjIyNiA3Ni4xNjhDMTMzLjkxNSA3Ny41MTE0IDEzMi4zMjMgNzguMTY3NSAxMzAuNDgxIDc4LjE2NzVDMTI5LjIzMiA3OC4xNjc1IDEyOC4xNCA3Ny44ODYzIDEyNy4xNzIgNzcuMzU1MkMxMjYuMjA1IDc2LjgyNDEgMTI1LjM5MyA3NS45ODA1IDEyNC43MDYgNzQuODg3MUMxMjQuMDE5IDczLjc5MzYgMTIzLjY3NiA3Mi41NzUyIDEyMy42NzYgNzEuMjMxOFY1OS41MTZIMTIyLjQ5QzEyMi4xNDcgNTkuNTE2IDEyMS45MjggNTkuNDUzNSAxMjEuNzcyIDU5LjMyODVDMTIxLjYxNiA1OS4yMDM2IDEyMS41NTMgNTkuMDE2MSAxMjEuNTUzIDU4LjgyODdDMTIxLjU1MyA1OC42MSAxMjEuNjE2IDU4LjQ1MzggMTIxLjc3MiA1OC4yOTc2QzEyMS44OTcgNTguMTcyNiAxMjIuMTQ3IDU4LjE0MTQgMTIyLjQ1OSA1OC4xNDE0SDEyNy42NEMxMjcuOTg0IDU4LjE0MTQgMTI4LjIwMiA1OC4yMDM4IDEyOC4zNTggNTguMzI4OEMxMjguNTE1IDU4LjQ1MzggMTI4LjU3NyA1OC42NDEyIDEyOC41NzcgNTguODI4N0MxMjguNTc3IDU5LjA0NzQgMTI4LjUxNSA1OS4yMDM2IDEyOC4zNTggNTkuMzI4NUMxMjguMjAyIDU5LjQ1MzUgMTI3Ljk4NCA1OS41MTYgMTI3LjY0IDU5LjUxNkgxMjUuMDVWNzEuMjMxOEMxMjUuMDUgNzIuNzYyNiAxMjUuNTggNzQuMDc0OCAxMjYuNjQyIDc1LjE2ODJDMTI3LjcwMyA3Ni4yNjE3IDEyOC45NTIgNzYuNzkyOCAxMzAuMzg3IDc2Ljc5MjhDMTMxLjMyNCA3Ni43OTI4IDEzMi4xNjcgNzYuNTc0MSAxMzIuOTE2IDc2LjE2OEMxMzMuNjY1IDc1LjczMDYgMTM0LjM1MiA3NS4xMDU4IDEzNC45MTQgNzQuMjMxQzEzNS40NzYgNzMuMzU2MiAxMzUuNzg4IDcyLjM1NjUgMTM1Ljc4OCA3MS4yNjNWNTkuNTQ3MkgxMzMuMTk3QzEzMi44NTMgNTkuNTQ3MiAxMzIuNjM1IDU5LjQ4NDggMTMyLjQ3OSA1OS4zNTk4QzEzMi4zMjMgNTkuMjM0OCAxMzIuMjYgNTkuMDQ3NCAxMzIuMjYgNTguODU5OUMxMzIuMjYgNTguNjQxMiAxMzIuMzIzIDU4LjQ4NSAxMzIuNDc5IDU4LjM2MDFDMTMyLjYzNSA1OC4yMzUxIDEzMi44NTMgNTguMTcyNiAxMzMuMTk3IDU4LjE3MjZIMTM4LjM3OUMxMzguNzIyIDU4LjE3MjYgMTM4Ljk0MSA1OC4yMzUxIDEzOS4wOTcgNTguMzYwMUMxMzkuMjUzIDU4LjQ4NSAxMzkuMzE1IDU4LjY3MjUgMTM5LjMxNSA1OC44NTk5QzEzOS4zMTUgNTkuMDc4NiAxMzkuMjUzIDU5LjIzNDggMTM5LjA5NyA1OS4zNTk4QzEzOC45NDEgNTkuNDg0OCAxMzguNzIyIDU5LjU0NzIgMTM4LjM3OSA1OS41NDcySDEzNy4xOTJWNTkuNDg0OFoiIGZpbGw9IiNCODk5NEYiLz4KPHBhdGggZD0iTTE1MC44NjUgNzAuNzk0NFY3OC4xMDVIMTUzLjQ1NkMxNTMuNzk5IDc4LjEwNSAxNTQuMDQ5IDc4LjE2NzUgMTU0LjE3NCA3OC4yOTI0QzE1NC4zMyA3OC40MTc0IDE1NC4zOTIgNzguNjA0OSAxNTQuMzkyIDc4Ljc5MjNDMTU0LjM5MiA3OC45Nzk4IDE1NC4zMyA3OS4xNjcyIDE1NC4xNzQgNzkuMjkyMkMxNTQuMDE4IDc5LjQxNzEgMTUzLjc5OSA3OS40Nzk2IDE1My40NTYgNzkuNDc5NkgxNDcuNTg3QzE0Ny4yNDQgNzkuNDc5NiAxNDcuMDI1IDc5LjQxNzEgMTQ2Ljg2OSA3OS4yOTIyQzE0Ni43MTMgNzkuMTY3MiAxNDYuNjUxIDc4Ljk3OTggMTQ2LjY1MSA3OC43OTIzQzE0Ni42NTEgNzguNTczNiAxNDYuNzEzIDc4LjQxNzQgMTQ2Ljg2OSA3OC4yOTI0QzE0Ny4wMjUgNzguMTY3NSAxNDcuMjQ0IDc4LjEwNSAxNDcuNTg3IDc4LjEwNUgxNDkuNDZWNjEuMzkwNUgxNDcuNTg3QzE0Ny4yNDQgNjEuMzkwNSAxNDcuMDI1IDYxLjMyOCAxNDYuODY5IDYxLjIwMzFDMTQ2LjcxMyA2MS4wNzgxIDE0Ni42NTEgNjAuODkwNiAxNDYuNjUxIDYwLjcwMzJDMTQ2LjY1MSA2MC40ODQ1IDE0Ni43MTMgNjAuMzI4MyAxNDYuODY5IDYwLjIwMzNDMTQ3LjAyNSA2MC4wNzg0IDE0Ny4yNDQgNjAuMDE1OSAxNDcuNTg3IDYwLjAxNTlIMTU2LjA0N0MxNTcuNzk1IDYwLjAxNTkgMTU5LjI2MiA2MC41NzgyIDE2MC40NDggNjEuNjcxN0MxNjEuNjM0IDYyLjc2NTIgMTYyLjIyNyA2NC4wMTQ4IDE2Mi4yMjcgNjUuNDIwN0MxNjIuMjI3IDY2LjQyMDUgMTYxLjg1MyA2Ny4zNTc3IDE2MS4xMDQgNjguMjYzOEMxNjAuMzU0IDY5LjEzODUgMTU5LjEzNyA2OS44ODgzIDE1Ny4zODkgNzAuNDgxOUMxNTguMzg4IDcxLjE2OTMgMTU5LjIzMSA3MS45NTAzIDE1OS45NDkgNzIuODI1MUMxNjAuNjY3IDczLjY5OTkgMTYxLjc5IDc1LjQ4MDcgMTYzLjM1MSA3OC4xNjc1SDE2NC40MTJDMTY0Ljc1NiA3OC4xNjc1IDE2NC45NzQgNzguMjMgMTY1LjEzIDc4LjM1NDlDMTY1LjI4NiA3OC40Nzk5IDE2NS4zNDkgNzguNjY3MyAxNjUuMzQ5IDc4Ljg1NDhDMTY1LjM0OSA3OS4wNDIyIDE2NS4yODYgNzkuMjI5NyAxNjUuMTMgNzkuMzU0N0MxNjQuOTc0IDc5LjQ3OTYgMTY0Ljc1NiA3OS41NDIxIDE2NC40MTIgNzkuNTQyMUgxNjIuNTM5QzE2MC43OTEgNzYuNDQ5MiAxNTkuNDQ5IDc0LjM1NTkgMTU4LjUxMyA3My4yOTM3QzE1Ny41NzYgNzIuMjMxNSAxNTYuNDg0IDcxLjQxOTIgMTU1LjIzNSA3MC44MjU2SDE1MC44NjVWNzAuNzk0NFpNMTUwLjg2NSA2OS4zODg1SDE1NC42NzNDMTU1Ljg5MSA2OS4zODg1IDE1Ny4wMTQgNjkuMTY5OCAxNTguMDEzIDY4LjczMjRDMTU5LjAxMiA2OC4yOTUgMTU5LjczIDY3Ljc2MzkgMTYwLjE2NyA2Ny4xNzAzQzE2MC42MDQgNjYuNTc2NyAxNjAuODU0IDY1Ljk1MTggMTYwLjg1NCA2NS4zMjdDMTYwLjg1NCA2NC4zODk3IDE2MC4zODYgNjMuNTE1IDE1OS40NDkgNjIuNjcxNEMxNTguNTEzIDYxLjgyNzkgMTU3LjM1OCA2MS4zOTA1IDE1Ni4wMTUgNjEuMzkwNUgxNTAuODY1VjY5LjM4ODVWNjkuMzg4NVoiIGZpbGw9IiNCODk5NEYiLz4KPHBhdGggZD0iTTExNS4yNDggMTAxLjMxOEwxMTYuNDAzIDEwMS40NzRDMTE2LjIxNiAxMDIuMTYxIDExNS44NzIgMTAyLjY2MSAxMTUuNDA0IDEwMy4wMzZDMTE0LjkwNSAxMDMuNDExIDExNC4yOCAxMDMuNTk4IDExMy41MzEgMTAzLjU5OEMxMTIuNTYzIDEwMy41OTggMTExLjgxNCAxMDMuMzE3IDExMS4yNTIgMTAyLjcyNEMxMTAuNjkgMTAyLjEzIDExMC40MDkgMTAxLjI4NyAxMTAuNDA5IDEwMC4yMjRDMTEwLjQwOSA5OS4xMzA4IDExMC42OSA5OC4yNTYxIDExMS4yNTIgOTcuNjMxMkMxMTEuODE0IDk3LjAwNjQgMTEyLjU2MyA5Ni43MjUyIDExMy40NjkgOTYuNzI1MkMxMTQuMzQzIDk2LjcyNTIgMTE1LjA2MSA5Ny4wMzc2IDExNS42MjIgOTcuNjMxMkMxMTYuMTg0IDk4LjIyNDggMTE2LjQ2NSA5OS4wNjg0IDExNi40NjUgMTAwLjE2MkMxMTYuNDY1IDEwMC4yMjQgMTE2LjQ2NSAxMDAuMzE4IDExNi40NjUgMTAwLjQ3NEgxMTEuNTY0QzExMS41OTYgMTAxLjE5MyAxMTEuODE0IDEwMS43NTUgMTEyLjE4OSAxMDIuMTNDMTEyLjU2MyAxMDIuNTA1IDExMyAxMDIuNjkyIDExMy41NjIgMTAyLjY5MkMxMTMuOTY4IDEwMi42OTIgMTE0LjMxMSAxMDIuNTk5IDExNC42MjQgMTAyLjM4QzExNC44NDIgMTAyLjEzIDExNS4wNjEgMTAxLjc4NiAxMTUuMjQ4IDEwMS4zMThaTTExMS41OTYgOTkuNTM3SDExNS4yNzlDMTE1LjIxNyA5OC45NzQ2IDExNS4wOTIgOTguNTY4NSAxMTQuODczIDk4LjI4NzNDMTE0LjUzIDk3Ljg0OTkgMTE0LjA2MiA5Ny42MzEyIDExMy41IDk3LjYzMTJDMTEzIDk3LjYzMTIgMTEyLjU2MyA5Ny43ODc0IDExMi4yMiA5OC4xMzExQzExMS44MTQgOTguNDc0OCAxMTEuNjI3IDk4Ljk0MzQgMTExLjU5NiA5OS41MzdaIiBmaWxsPSIjQjg5OTRGIi8+CjxwYXRoIGQ9Ik0xMTguNDMyIDEwMy40NDJWOTYuODUwMkgxMTkuNDMxVjk3Ljc4NzRDMTE5Ljg5OSA5Ny4wNjg5IDEyMC42MTcgOTYuNjk0IDEyMS41MjIgOTYuNjk0QzEyMS45MjggOTYuNjk0IDEyMi4yNzEgOTYuNzU2NSAxMjIuNjE1IDk2LjkxMjdDMTIyLjk1OCA5Ny4wNjg5IDEyMy4yMDggOTcuMjU2MyAxMjMuMzY0IDk3LjQ3NUMxMjMuNTIgOTcuNjkzNyAxMjMuNjQ1IDk3Ljk3NDkgMTIzLjcwNyA5OC4yODczQzEyMy43MzkgOTguNTA2IDEyMy43NyA5OC44NDk3IDEyMy43NyA5OS4zODA4VjEwMy40NDJIMTIyLjY3N1Y5OS40NDMzQzEyMi42NzcgOTguOTc0NiAxMjIuNjQ2IDk4LjY2MjIgMTIyLjU1MiA5OC40MTIzQzEyMi40NTkgOTguMTkzNiAxMjIuMzAzIDk4LjAwNjEgMTIyLjA4NCA5Ny44ODEyQzEyMS44NjYgOTcuNzU2MiAxMjEuNjE2IDk3LjY5MzcgMTIxLjMwNCA5Ny42OTM3QzEyMC44MzUgOTcuNjkzNyAxMjAuNDMgOTcuODQ5OSAxMjAuMDg2IDk4LjEzMTFDMTE5Ljc0MyA5OC40NDM1IDExOS41NTYgOTkuMDA1OSAxMTkuNTU2IDk5Ljg0OTRWMTAzLjQ0MkgxMTguNDMyVjEwMy40NDJaIiBmaWxsPSIjQjg5OTRGIi8+CjxwYXRoIGQ9Ik0xMjYuMzkyIDEwMy40NDJWOTcuNzI0OUgxMjUuMzkzVjk2Ljg1MDFIMTI2LjM5MlY5Ni4xNjI4QzEyNi4zOTIgOTUuNzI1NCAxMjYuNDIzIDk1LjM4MTggMTI2LjUxNyA5NS4xNjMxQzEyNi42MSA5NC44ODE5IDEyNi43OTggOTQuNjMyIDEyNy4wNzkgOTQuNDQ0NUMxMjcuMzYgOTQuMjU3MSAxMjcuNzM0IDk0LjE2MzMgMTI4LjIwMiA5NC4xNjMzQzEyOC41MTUgOTQuMTYzMyAxMjguODU4IDk0LjE5NDYgMTI5LjIzMiA5NC4yODgzTDEyOS4wNzYgOTUuMjU2OEMxMjguODU4IDk1LjIyNTYgMTI4LjYzOSA5NS4xOTQzIDEyOC40MjEgOTUuMTk0M0MxMjguMDc4IDk1LjE5NDMgMTI3LjgyOCA5NS4yNTY4IDEyNy43MDMgOTUuNDEzQzEyNy41NzggOTUuNTY5MiAxMjcuNDg0IDk1LjgxOTIgMTI3LjQ4NCA5Ni4yMjUzVjk2LjgxODlIMTI4Ljc2NFY5Ny42OTM3SDEyNy40ODRWMTAzLjQxMUgxMjYuMzkyVjEwMy40NDJaIiBmaWxsPSIjQjg5OTRGIi8+CjxwYXRoIGQ9Ik0xMzQuNTcgMTAyLjYzQzEzNC4xNjUgMTAyLjk3NCAxMzMuNzU5IDEwMy4yMjQgMTMzLjM4NCAxMDMuMzhDMTMzLjAxIDEwMy41MzYgMTMyLjYwNCAxMDMuNTk4IDEzMi4xNjcgMTAzLjU5OEMxMzEuNDQ5IDEwMy41OTggMTMwLjg4NyAxMDMuNDExIDEzMC41MTIgMTAzLjA2N0MxMzAuMTM4IDEwMi43MjQgMTI5LjkxOSAxMDIuMjU1IDEyOS45MTkgMTAxLjcyNEMxMjkuOTE5IDEwMS40MTIgMTI5Ljk4MiAxMDEuMDk5IDEzMC4xMzggMTAwLjg0OUMxMzAuMjk0IDEwMC41NjggMTMwLjQ4MSAxMDAuMzgxIDEzMC43IDEwMC4xOTNDMTMwLjk0OSAxMDAuMDM3IDEzMS4xOTkgOTkuOTExOSAxMzEuNTExIDk5LjgxODJDMTMxLjczIDk5Ljc1NTcgMTMyLjA3MyA5OS42OTMyIDEzMi41MSA5OS42NjJDMTMzLjQxNSA5OS41NjgyIDEzNC4wNzEgOTkuNDEyIDEzNC41MDggOTkuMjg3MUMxMzQuNTA4IDk5LjEzMDkgMTM0LjUwOCA5OS4wMzcxIDEzNC41MDggOTkuMDA1OUMxMzQuNTA4IDk4LjUzNzMgMTM0LjQxNCA5OC4yMjQ4IDEzNC4xOTYgOTguMDM3NEMxMzMuOTE1IDk3Ljc4NzQgMTMzLjQ3OCA5Ny42NjI1IDEzMi45MTYgOTcuNjYyNUMxMzIuMzg1IDk3LjY2MjUgMTMyLjAxMSA5Ny43NTYyIDEzMS43NjEgOTcuOTQzN0MxMzEuNTExIDk4LjEzMTEgMTMxLjMyNCA5OC40NDM1IDEzMS4xOTkgOTguOTEyMkwxMzAuMTA3IDk4Ljc1NkMxMzAuMiA5OC4yODczIDEzMC4zNTYgOTcuOTEyNCAxMzAuNjA2IDk3LjYzMTJDMTMwLjgyNCA5Ny4zNTAxIDEzMS4xNjggOTcuMTMxNCAxMzEuNjA1IDk2Ljk3NTJDMTMyLjA0MiA5Ni44MTg5IDEzMi41NDEgOTYuNzU2NSAxMzMuMTAzIDk2Ljc1NjVDMTMzLjY2NSA5Ni43NTY1IDEzNC4xMzMgOTYuODE4OSAxMzQuNDc3IDk2Ljk0MzlDMTM0LjgyIDk3LjA2ODkgMTM1LjEwMSA5Ny4yNTYzIDEzNS4yNTcgOTcuNDQzOEMxMzUuNDEzIDk3LjYzMTIgMTM1LjUzOCA5Ny45MTI0IDEzNS42IDk4LjE5MzZDMTM1LjYzMiA5OC4zODEgMTM1LjY2MyA5OC43MjQ3IDEzNS42NjMgOTkuMjI0NlYxMDAuNzI0QzEzNS42NjMgMTAxLjc1NSAxMzUuNjk0IDEwMi40MTEgMTM1LjcyNSAxMDIuNjkyQzEzNS43ODggMTAyLjk3NCAxMzUuODgxIDEwMy4yMjQgMTM2LjAwNiAxMDMuNDczSDEzNC44NTFDMTM0LjY5NSAxMDMuMjI0IDEzNC42MDIgMTAyLjk0MiAxMzQuNTcgMTAyLjYzWk0xMzQuNDc3IDEwMC4xMzFDMTM0LjA3MSAxMDAuMjg3IDEzMy40NzggMTAwLjQ0MyAxMzIuNjY2IDEwMC41MzdDMTMyLjE5OCAxMDAuNTk5IDEzMS44ODYgMTAwLjY5MyAxMzEuNjk5IDEwMC43NTVDMTMxLjUxMSAxMDAuODQ5IDEzMS4zNTUgMTAwLjk3NCAxMzEuMjYxIDEwMS4xM0MxMzEuMTY4IDEwMS4yODcgMTMxLjEwNSAxMDEuNDc0IDEzMS4xMDUgMTAxLjY2MUMxMzEuMTA1IDEwMS45NzQgMTMxLjIzIDEwMi4xOTMgMTMxLjQ0OSAxMDIuNDExQzEzMS42NjcgMTAyLjU5OSAxMzIuMDExIDEwMi43MjQgMTMyLjQ0OCAxMDIuNzI0QzEzMi44ODUgMTAyLjcyNCAxMzMuMjU5IDEwMi42MyAxMzMuNjAzIDEwMi40NDNDMTMzLjk0NiAxMDIuMjU1IDEzNC4xOTYgMTAyLjAwNSAxMzQuMzUyIDEwMS42NjFDMTM0LjQ3NyAxMDEuNDEyIDEzNC41MzkgMTAxLjAzNyAxMzQuNTM5IDEwMC41MzdWMTAwLjEzMUgxMzQuNDc3WiIgZmlsbD0iI0I4OTk0RiIvPgo8cGF0aCBkPSJNMTM3Ljk3MyAxMDMuNDQyVjk2Ljg1MDJIMTM4Ljk3MlY5Ny43ODc0QzEzOS40NCA5Ny4wNjg5IDE0MC4xNTggOTYuNjk0IDE0MS4wNjMgOTYuNjk0QzE0MS40NjkgOTYuNjk0IDE0MS44MTIgOTYuNzU2NSAxNDIuMTU2IDk2LjkxMjdDMTQyLjQ5OSA5Ny4wNjg5IDE0Mi43NDkgOTcuMjU2MyAxNDIuOTA1IDk3LjQ3NUMxNDMuMDYxIDk3LjY5MzcgMTQzLjE4NiA5Ny45NzQ5IDE0My4yNDggOTguMjg3M0MxNDMuMjc5IDk4LjUwNiAxNDMuMzExIDk4Ljg0OTcgMTQzLjMxMSA5OS4zODA4VjEwMy40NDJIMTQyLjE4N1Y5OS40NDMzQzE0Mi4xODcgOTguOTc0NiAxNDIuMTU2IDk4LjY2MjIgMTQyLjA2MiA5OC40MTIzQzE0MS45NjggOTguMTkzNiAxNDEuODEyIDk4LjAwNjEgMTQxLjU5NCA5Ny44ODEyQzE0MS4zNzUgOTcuNzU2MiAxNDEuMTI2IDk3LjY5MzcgMTQwLjgxMyA5Ny42OTM3QzE0MC4zNDUgOTcuNjkzNyAxMzkuOTM5IDk3Ljg0OTkgMTM5LjU5NiA5OC4xMzExQzEzOS4yNTMgOTguNDQzNSAxMzkuMDY1IDk5LjAwNTkgMTM5LjA2NSA5OS44NDk0VjEwMy40NDJIMTM3Ljk3M1YxMDMuNDQyWiIgZmlsbD0iI0I4OTk0RiIvPgo8cGF0aCBkPSJNMTQ4LjExOCAxMDIuNDQyTDE0OC4yNzQgMTAzLjQ0MkMxNDcuOTYyIDEwMy41MDUgMTQ3LjY4MSAxMDMuNTM2IDE0Ny40MzEgMTAzLjUzNkMxNDcuMDI1IDEwMy41MzYgMTQ2LjcxMyAxMDMuNDczIDE0Ni40OTUgMTAzLjM0OEMxNDYuMjc2IDEwMy4yMjMgMTQ2LjEyIDEwMy4wNjcgMTQ2LjAyNiAxMDIuODQ5QzE0NS45MzMgMTAyLjYzIDE0NS45MDIgMTAyLjE5MyAxNDUuOTAyIDEwMS41MzZWOTcuNzU2MUgxNDUuMDlWOTYuODUwMUgxNDUuOTAyVjk1LjIyNTVMMTQ3LjAyNSA5NC41Njk1Vjk2Ljg4MTRIMTQ4LjE0OVY5Ny43NTYxSDE0Ny4wMjVWMTAxLjU5OUMxNDcuMDI1IDEwMS45MTEgMTQ3LjA1NyAxMDIuMTMgMTQ3LjA4OCAxMDIuMjI0QzE0Ny4xMTkgMTAyLjMxNyAxNDcuMTgxIDEwMi4zOCAxNDcuMjc1IDEwMi40NDJDMTQ3LjM2OSAxMDIuNTA1IDE0Ny40OTQgMTAyLjUzNiAxNDcuNjUgMTAyLjUzNkMxNDcuNzQzIDEwMi41MDUgMTQ3Ljg5OSAxMDIuNDc0IDE0OC4xMTggMTAyLjQ0MloiIGZpbGw9IiNCODk5NEYiLz4KPHBhdGggZD0iTTE0OS4zNjcgMTAxLjQ3NEwxNTAuNDU5IDEwMS4yODdDMTUwLjUyMSAxMDEuNzI0IDE1MC43MDkgMTAyLjA2OCAxNTAuOTkgMTAyLjMxOEMxNTEuMjcxIDEwMi41NjcgMTUxLjY3NiAxMDIuNjYxIDE1Mi4xNzYgMTAyLjY2MUMxNTIuNjc1IDEwMi42NjEgMTUzLjA4MSAxMDIuNTY3IDE1My4zMzEgMTAyLjM0OUMxNTMuNTgxIDEwMi4xMyAxNTMuNzA1IDEwMS44OCAxNTMuNzA1IDEwMS41OTlDMTUzLjcwNSAxMDEuMzQ5IDE1My41ODEgMTAxLjE2MiAxNTMuMzYyIDEwMS4wMDVDMTUzLjIwNiAxMDAuOTEyIDE1Mi44MzEgMTAwLjc4NyAxNTIuMjA3IDEwMC42M0MxNTEuMzk2IDEwMC40MTIgMTUwLjgzNCAxMDAuMjU2IDE1MC41MjEgMTAwLjA5OUMxNTAuMjA5IDk5Ljk0MzEgMTQ5Ljk2IDk5LjcyNDQgMTQ5LjgwNCA5OS40NzQ1QzE0OS42NDcgOTkuMTkzMyAxNDkuNTU0IDk4LjkxMjIgMTQ5LjU1NCA5OC41OTk3QzE0OS41NTQgOTguMzE4NiAxNDkuNjE2IDk4LjAzNzQgMTQ5Ljc0MSA5Ny43ODc0QzE0OS44NjYgOTcuNTM3NSAxNTAuMDUzIDk3LjMxODggMTUwLjMwMyA5Ny4xNjI2QzE1MC40OSA5Ny4wMzc2IDE1MC43MDkgOTYuOTEyNyAxNTEuMDIxIDk2Ljg1MDJDMTUxLjMzMyA5Ni43NTY1IDE1MS42NDUgOTYuNzI1MiAxNTEuOTg5IDk2LjcyNTJDMTUyLjUxOSA5Ni43MjUyIDE1Mi45NTYgOTYuNzg3NyAxNTMuMzYyIDk2Ljk0MzlDMTUzLjc2OCA5Ny4xMDAxIDE1NC4wNDkgOTcuMjg3NiAxNTQuMjM2IDk3LjUzNzVDMTU0LjQyMyA5Ny43ODc0IDE1NC41NDggOTguMTMxMSAxNTQuNjExIDk4LjU2ODVMMTUzLjUxOCA5OC43MjQ3QzE1My40NTYgOTguMzgxIDE1My4zMzEgOTguMTMxMSAxNTMuMDgxIDk3Ljk0MzdDMTUyLjgzMSA5Ny43NTYyIDE1Mi41MTkgOTcuNjYyNSAxNTIuMDgyIDk3LjY2MjVDMTUxLjU4MyA5Ny42NjI1IDE1MS4yMDggOTcuNzU2MiAxNTAuOTkgOTcuOTEyNEMxNTAuNzcxIDk4LjA2ODYgMTUwLjY0NiA5OC4yODczIDE1MC42NDYgOTguNTA2QzE1MC42NDYgOTguNjYyMiAxNTAuNjc4IDk4Ljc4NzIgMTUwLjc3MSA5OC45MTIyQzE1MC44NjUgOTkuMDM3MSAxNTAuOTkgOTkuMTMwOSAxNTEuMjA4IDk5LjIyNDZDMTUxLjMzMyA5OS4yNTU4IDE1MS42NDUgOTkuMzQ5NSAxNTIuMjA3IDk5LjUwNThDMTUyLjk4OCA5OS43MjQ0IDE1My41NDkgOTkuODgwNyAxNTMuODYyIDEwMC4wMzdDMTU0LjE3NCAxMDAuMTYyIDE1NC40MjMgMTAwLjM4MSAxNTQuNjExIDEwMC42M0MxNTQuNzk4IDEwMC44OCAxNTQuODkyIDEwMS4xOTMgMTU0Ljg5MiAxMDEuNTk5QzE1NC44OTIgMTAxLjk3NCAxNTQuNzk4IDEwMi4zMTggMTU0LjU4IDEwMi42NjFDMTU0LjM2MSAxMDMuMDA1IDE1NC4wNDkgMTAzLjI1NSAxNTMuNjQzIDEwMy40MTFDMTUzLjIzNyAxMDMuNTk4IDE1Mi43NjkgMTAzLjY5MiAxNTIuMjcgMTAzLjY5MkMxNTEuNDI3IDEwMy42OTIgMTUwLjc3MSAxMDMuNTA1IDE1MC4zMDMgMTAzLjE2MUMxNDkuODA0IDEwMi43MjQgMTQ5LjQ5MSAxMDIuMTkzIDE0OS4zNjcgMTAxLjQ3NFoiIGZpbGw9IiNCODk5NEYiLz4KPC9zdmc+Cg==" style="
							display: block; 
							max-width: 100%; 
							margin: 0 auto;
						">
                        <br>
					</td>
				</tr>
				<tr style="
					font-family: Montserrat, Helvetica, Arial, sans-serif; 
					font-size: 100%; 
					line-height: 1.6; 
					margin: 0; 
					padding: 0;
				">
					<td class="container" style="
						font-family: Montserrat, Helvetica, Arial, sans-serif; 
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
							font-family: Montserrat, Helvetica, Arial, sans-serif; 
							font-size: 100%; 
							line-height: 1.6; 
							max-width: 600px; 
							display: block; 
							margin: 0 auto; 
							padding: 20px;
						">
							<table style="
								font-family: Montserrat, Helvetica, Arial, sans-serif; 
								font-size: 100%; 
								line-height: 1.6; 
								width: 100%; 
								margin: 0; 
								padding: 0;
							">
								<tr style="
									font-family: Montserrat, Helvetica, Arial, sans-serif;
									font-size: 100%; 
									line-height: 1.6; 
									margin: 0; 
									padding: 0;
								">
									<td style="
										font-family: Montserrat, Helvetica, Arial, sans-serif; 
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
						font-family: Montserrat, Helvetica, Arial, sans-serif; 
						font-size: 100%; 
						line-height: 1.6; 
						margin: 0; 
						padding: 0;
					"></td>
				</tr>
			</table>
			
			<table class="footer-wrap" style="
				font-family: Montserrat, Helvetica, Arial, sans-serif; 
				font-size: 100%; 
				line-height: 1.6; 
				width: 100%; 
				clear: both !important; 
				margin: 0; 
				padding: 0;
			">
				<tr style="
					font-family: Montserrat, Helvetica, Arial, sans-serif; 
					font-size: 100%; 
					line-height: 1.6; 
					margin: 0; 
					padding: 0;
				">
					<td style="
						font-family: Montserrat, Helvetica, Arial, sans-serif; 
						font-size: 100%; 
						line-height: 1.6; 
						margin: 0; 
						padding: 0;
					"></td>
					<td class="container" style="
						font-family: Montserrat, Helvetica, Arial, sans-serif; 
						font-size: 100%; 
						line-height: 1.6; 
						display: block !important; 
						max-width: 600px !important; 
						clear: both !important; 
						margin: 0 auto; 
						padding: 0;
					">
						<div class="content" style="
							font-family: Montserrat, Helvetica, Arial, sans-serif; 
							font-size: 100%; 
							line-height: 1.6; 
							max-width: 600px; 
							display: block; 
							margin: 0 auto; 
							padding: 20px;
						">
							<table style="
								font-family: Montserrat, Helvetica, Arial, sans-serif; 
								font-size: 100%; 
								line-height: 1.6; 
								width: 100%; 
								margin: 0; 
								padding: 0;
							">
								<tr style="
									font-family: Montserrat, Helvetica, Arial, sans-serif; 
									font-size: 100%; 
									line-height: 1.6; 
									margin: 0; 
									padding: 0;
								">
									<td align="center" style="
										font-family: Montserrat, Helvetica, Arial, sans-serif; 
										font-size: 100%; 
										line-height: 1.6; 
										margin: 0; 
										padding: 0;
									">
										<p style="
											font-family: Montserrat, Helvetica, Arial, sans-serif; 
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
