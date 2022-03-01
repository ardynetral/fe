<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>Daily Movement Out</h2>
		<em>Daily Movement Out page</em>
	</div>
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

				<form id="#formCType" class="form-horizontal" role="form" data-parsley-validate="">
					<?= csrf_field(); ?>
					<fieldset>
						<div class="form-group">
							<label for="cncode" class="col-sm-2 control-label text-right">Principal</label>
							<div class="col-sm-2">
								<?php if ($prcode == "0") :
									echo principal_dropdown($selected = "");
								else :
								?>
									<input type="text" readonly name="prcode" class="form-control" id="prcode" value="<?= $prcode; ?>" required>
								<?php endif; ?>
							</div>
						</div>
						<div class="form-group">
							<label for="cpipratgl" class="col-sm-2 control-label text-right">Date</label>
							<div class="col-sm-2">
								<div class="input-group">
									<input type="text" name="startDate" id="startDate" class="form-control tanggal" required>
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>
							</div>
							<div class="col-sm-1">
								<center>To</center>
							</div>
							<div class="col-sm-2">
								<div class="input-group">
									<input type="text" name="endDate" id="endDate" class="form-control tanggal" required>
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="cpipratgl" class="col-sm-2 control-label text-right">Hour</label>
							<div class="col-sm-2">
								<div class="input-group bootstrap-timepicker timepicker">
									<input id="timepicker1" name="startHour" type="text" class="form-control input-small" required>
									<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
								</div>
							</div>
							<div class="col-sm-1">
								<center>To</center>
							</div>
							<div class="col-sm-2">
								<div class="input-group bootstrap-timepicker timepicker">
									<input id="timepicker2" name="endHour" type="text" class="form-control input-small" required>
									<span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
								</div>
							</div>
						</div>

						<div class="rows">
							<div class="col-sm-offset-2">
							<button type="button" id="printPdf" class="btn btn-primary"><i class="fa fa-check-circle"></i> Print to PDF </button>
							<button type="button" id="printExl" class="btn btn-primary"><i class="fa fa-check-circle"></i> Print to Excel</button>
							</div>
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

<?= $this->include('\Modules\Dailymovementout\Views\js'); ?>

<?= $this->endSection(); ?>
