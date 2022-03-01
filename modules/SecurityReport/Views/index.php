<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>Security Daily Report</h2>
		<em>Security Daily Report page</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header"></div>

			<form id="#formCType" class="form-inline" role="form">
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

				<?= csrf_field(); ?>
				<fieldset>
					<div class="form-group">
						<label for="startDate" class="control-label">Date</label>
					</div>						
					<div class="form-group">
					<div class="input-group">
						<input type="text" name="startDate" id="startDate" class="form-control tanggal">
						<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
					</div>
					</div>
					<div class="form-group">
						<label for="endDate" class="control-label">To</label>
					</div>
					<div class="form-group">
						<div class="input-group">
							<input type="text" name="endDate" id="endDate" class="form-control tanggal">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>
					</div>
				</fieldset>
			</div>
			<div class="widget-footer">
				<button type="button" id="printPdf" class="btn btn-primary"><i class="fa fa-check-circle"></i> Print to PDF </button>
				<button type="button" id="printExl" class="btn btn-primary"><i class="fa fa-check-circle"></i> Print to Excel</button>				
			</div>
			</form>
		</div>

	</div>
</div>


<?= $this->endSection(); ?>

<!-- Load JS -->

<?= $this->Section('script_js'); ?>

<?= $this->include('\Modules\SecurityReport\Views\js'); ?>

<?= $this->endSection(); ?>