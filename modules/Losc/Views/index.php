<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>Long of Stay Container</h2>
		<em>Long of Stay Container page</em>
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
							<label for="cncode" class="col-sm-2 control-label text-right">Principal</label>
							<div class="col-sm-2">
								<?php if ($prcode == "0") :
									echo principal_dropdown($selected = "");
								else :
								?>
									<input type="text" readonly name="prcode" class="form-control" id="prcode" value="<?= $prcode; ?>">
								<?php endif; ?>
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
						</div>
						<div class="form-group">
							<label for="days" class="col-sm-2 control-label text-right">Long Of Stay</label>
							<div class="col-sm-2">
								<input type="text" name="days" class="form-control" id="city" value="<?= @$city ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label text-right">Container Length</label>
							<div class="col-sm-3">


								<select name="length" id="length" class="select-length">
									<option value="">- select -</option>
									<option value="A">20"</option>
									<option value="B">40"</option>
									<option value="C">HC</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="days" class="col-sm-2 control-label text-right">Container Type</label>
							<div class="col-sm-2">
								<input type="text" name="days" class="form-control" id="city" value="<?= @$city ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label text-right">Condition</label>
							<div class="col-sm-3">
								<select name="billType" id="billType" class="select-billtype">
									<option value="">- select -</option>
									<option value="B">Available</option>
									<option value="P">Damage</option>
								</select>
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

<?= $this->include('\Modules\Losc\Views\js'); ?>

<?= $this->endSection(); ?>
