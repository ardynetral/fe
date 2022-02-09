<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>Depo Info Daily</h2>
		<em>Depo Info Daily page</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header"></div>
			<div class="rows">
				<a href="" id="printPdf" class="btn btn-primary"><i class="fa fa-check-circle"></i> Print To PDF</a>
				<button type="button" id="printExl" class="btn btn-primary"><i class="fa fa-check-circle"></i> Print To CSV</button>
			</div>
		</div>

	</div>
</div>


<?= $this->endSection(); ?>

<!-- Load JS -->

<?= $this->Section('script_js'); ?>

<?= $this->include('\Modules\Did\Views\js'); ?>

<?= $this->endSection(); ?>