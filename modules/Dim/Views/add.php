<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>



<div class="main-content">

	<div class="widget">
		<div class="widget-header"></div>

		<div class="widget-content">


			<?php if (isset($validasi)) : ?>
				<div class="alert alert-danger">
					<?= $validasi; ?>
				</div>
			<?php endif; ?>

			<?php if (isset($message)) : ?>
				<p class="alert alert-danger">
					<?php echo $message; ?>
				</p>
			<?php endif; ?>
			<div id="alert">

			</div>

			<form id="#formCType" class="form-horizontal" role="form">
				<?= csrf_field(); ?>
				<fieldset>
					<div class="rows">
						<button type="button" id="printPdf" class="btn btn-primary"><i class="fa fa-check-circle"></i> Print to PDF </button>
						<button type="button" id="printExl" class="btn btn-primary"><i class="fa fa-check-circle"></i> Print to Excel</button>
					</div>
				</fieldset>
			</form>
		</div>


	</div>

</div>


<?= $this->endSection(); ?>

<!-- Load JS -->

<?= $this->Section('script_js'); ?>

<?= $this->include('\Modules\Dim\Views\js'); ?>

<?= $this->endSection(); ?>