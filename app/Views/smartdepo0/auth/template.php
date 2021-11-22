<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie ie9" lang="en" class="no-js"> <![endif]-->
<!--[if !(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->

<head>
	<title>Login | SmartDepo</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="description" content="KingAdmin Dashboard">
	<meta name="author" content="The Develovers">
	<!-- CSS -->
	<link href="<?php echo base_url();?>/public/themes/smartdepo/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>/public/themes/smartdepo/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>/public/themes/smartdepo/css/main.css" rel="stylesheet" type="text/css">
	<!--[if lte IE 9]>
		<link href="<?php echo base_url();?>/public/themes/smartdepo/css/main-ie.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url();?>/public/themes/smartdepo/css/main-ie-part2.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	<!-- Fav and touch icons -->
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo base_url();?>/public/themes/smartdepo/ico/kingadmin-favicon144x144.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo base_url();?>/public/themes/smartdepo/ico/kingadmin-favicon114x114.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo base_url();?>/public/themes/smartdepo/ico/kingadmin-favicon72x72.png">
	<link rel="apple-touch-icon-precomposed" sizes="57x57" href="<?php echo base_url();?>/public/themes/smartdepo/ico/kingadmin-favicon57x57.png">
	<link rel="shortcut icon" href="<?php echo base_url();?>/public/themes/smartdepo/ico/favicon.png">
</head>

<body>
	<div class="wrapper full-page-wrapper page-auth page-login text-center">
		<div class="inner-page">
			<div class="logo">

				<a href="<?=site_url();?>">
					<h1>SMART<b>DEPO</b></h1>
				</a>
			</div>

			<!-- CONTENT -->
			<?= $this->renderSection('content') ?>

		</div>
	</div>
	<footer class="footer">&copy; 2021 - <?=SITENAME;?></footer>
	<!-- Javascript -->
	<script src="<?php echo base_url();?>/public/themes/smartdepo/js/jquery/jquery-2.1.0.min.js"></script>
	<script src="<?php echo base_url();?>/public/themes/smartdepo/js/bootstrap/bootstrap.js"></script>
	<script src="<?php echo base_url();?>/public/themes/smartdepo/js/plugins/modernizr/modernizr.js"></script>
</body>

</html>
