<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie ie9" lang="en" class="no-js"> <![endif]-->
<!--[if !(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->

<?= $this->include('smartdepo/dashboard/header'); ?>

<body class="sidebar-fixed topnav-fixed dashboard">
	<!-- WRAPPER -->
	<div id="wrapper" class="wrapper">
		<!-- TOP BAR -->
		<?= $this->include('smartdepo/dashboard/topnav'); ?>

		<!-- LEFT SIDEBAR -->
		<?= $this->include('smartdepo/dashboard/sidebar'); ?>

		<!-- MAIN CONTENT WRAPPER -->
		<div id="main-content-wrapper" class="content-wrapper ">
			<!-- top general alert -->
<!-- 			<div class="alert alert-danger top-general-alert">
				<span>If you <strong>can't see the logo</strong> on the top left, please reset the style on right style switcher (for upgraded theme only).</span>
				<button type="button" class="close">&times;</button>
			</div> -->
			<!-- end top general alert -->
			<div class="row">
				<div class="col-md-12 ">
					<ul class="breadcrumb">
						<li><i class="fa fa-home"></i><a href="#">Home</a></li>
						<li class="active">Dashboard</li>
					</ul>
				</div>
			</div>
			<!-- main -->
			<div class="content">
				<?= $this->renderSection('content') ?>
			</div>
			<!-- /main -->
			<!-- FOOTER -->
			<footer class="footer">
				&copy; 2021 - <?=SITENAME;?>
			</footer>
			<!-- END FOOTER -->
		</div>
		<!-- END CONTENT WRAPPER -->
	</div>
	<!-- END WRAPPER -->
	
	<!-- Javascript -->
	<?=$this->include('smartdepo/dashboard/footer'); ?>

</body>

</html>
