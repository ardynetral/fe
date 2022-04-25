<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>Summary Container Type</h2>
		<em>Summary Container Type page</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header"></div>

			<div class="widget-content">

				<form id="#formCType" class="form-horizontal" role="form">
					<?= csrf_field(); ?>
					<fieldset>
						<div class="form-group">
							<label for="cncode" class="col-sm-2 control-label text-right">Principal</label>
							<div class="col-sm-2">
								<?=principal_dropdown($selected = ""); ?>
							</div>

							<div class="col-sm-1">
							<label for="cpipratgl" class="col-sm-2 control-label text-right">Date</label>
							</div>
							<div class="col-sm-2">
								<div class="input-group">
									<input type="text" name="startDate" id="startDate" class="form-control tanggal">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="cpipratgl" class="col-sm-2 control-label text-right">Hour</label>
							<div class="col-sm-2">
								<div class="input-group bootstrap-timepicker timepicker">
									<input id="timepicker1" name="startHour" type="text" class="form-control input-small">
									<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
								</div>
							</div>
							<div class="col-sm-1">
								<center>To</center>
							</div>
							<div class="col-sm-2">
								<div class="input-group bootstrap-timepicker timepicker">
									<input id="timepicker2" name="endHour" type="text" class="form-control input-small">
									<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
							<button type="button" id="printPdf" class="btn btn-primary"><i class="fa fa-check-circle"></i> Print to PDF </button>
							<button type="button" id="printExl" class="btn btn-primary"><i class="fa fa-check-circle"></i> Print to Excel</button>
						</div>
					</fieldset>
				</form>
			</div>


		</div>

	</div>
</div>


<?= $this->endSection(); ?>

<!-- Load JS -->

<?= $this->Section('script_js'); ?>

<?= $this->include('\Modules\Summaryconttype\Views\js'); ?>

<?= $this->endSection(); ?>