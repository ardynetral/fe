<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>Movement Out By Ves/Voy</h2>
		<em>Movement Out By Ves/Voy page</em>
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

				<form id="#formCType" class="form-horizontal" role="form">
					<?= csrf_field(); ?>
					<fieldset>
						<div class="form-group">
							<label for="cucode" class="col-sm-2 control-label text-right">EMKL</label>
							<div class="col-sm-2">
								<?php if ($cucode == "0") :
									echo debitur_dropdown($selected = "");
								else :
								?>
									<input type="text" readonly name="cucode" class="form-control" id="cucode" value="<?= $cucode; ?>">
								<?php endif; ?>
							</div>
						</div>

						<div class="form-group">
							<label for="cpives" class="col-sm-2 control-label text-right">Vessel</label>
							<div class="col-sm-2">
								<?php
								echo vessel_dropdown($selected = "");
								?>
							</div>
						</div>


						<div class="form-group">
							<label for="cpipratgl" class="col-sm-2 control-label text-right">Date</label>
							<div class="col-sm-2">
								<div class="input-group">
									<input type="text" name="startDate" id="startDate" class="form-control tanggal">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>
							</div>
							<div class="col-sm-1">
								<center>To</center>
							</div>
							<div class="col-sm-2">
								<div class="input-group">
									<input type="text" name="endDate" id="endDate" class="form-control tanggal">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>
							</div>
						</div>

						<div class="rows">
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

<?= $this->include('\Modules\Rptmovementoutbyvey\Views\js'); ?>

<?= $this->endSection(); ?>