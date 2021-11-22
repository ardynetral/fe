<!DOCTYPE html>
<!--[if IE 9 ]><html class="ie ie9" lang="en" class="no-js"> <![endif]-->
<!--[if !(IE)]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->

<?= $this->include('smartdepo/landing/header'); ?>

<body data-spy="scroll">
	<!-- WRAPPER -->
	<div id="wrapper">
		<!-- TOP BAR -->
		<?= $this->include('smartdepo/landing/topnav'); ?>

		<!-- main -->
		<?= $this->renderSection('content') ?>
		<!-- /main -->

		<!-- FOOTER -->
		<footer class="footer-minimal">
			<div class="container">
				<nav>
					<ul class="list-unstyled list-inline margin-bottom-30px">
						<li><a href="#">About</a></li>
						<li><a href="#">Terms &amp; Condition</a></li>
						<li><a href="#">Help</a></li>
					</ul>
				</nav>
				<p class="copyright-text">&copy; 2016 <a href="#" target="_blank">The Develovers</a>. All Rights Reserved.</p>
			</div>
		</footer>
		<!-- END FOOTER -->


	</div>
	<!-- END WRAPPER -->
	
	<!-- Javascript -->
	<?=$this->include('smartdepo/landing/footer'); ?>

</body>

</html>
