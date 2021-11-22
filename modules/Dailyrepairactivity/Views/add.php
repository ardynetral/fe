<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>Daily Repair Activity</h2>
		<em>Daily Repair Activity page</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header"></div>
			<div class="rows">
				<button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Print To PDF</button>
				<button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Print To CSV</button>
				<button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Print To Excel</button>
			</div>
			<div class="widget-content">


				<?php if(isset($validasi)):?>
					<div class="alert alert-danger">
						<?=$validasi; ?>
					</div>
				<?php endif; ?>

				<?php if(isset($message)): ?>
					<p class="alert alert-danger">
						<?php echo $message;?>
					</p>
				<?php endif;?>
				<div id="alert">
					
				</div>

				<form id="#formCType" class="form-horizontal" role="form">
					<?= csrf_field() ?>
					<fieldset>
						<div class="form-group">
							<label for="cncode" class="col-sm-2 control-label text-right">Principal</label>
							<div class="col-sm-2">
								<?=principal_dropdown()?>
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
							<div class="col-sm-offset-2 col-sm-10">
							<!-- 	<button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
								<button type="button" id="cancel" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</button>
							</div> -->
						</div>						
					</fieldset>
				</form>
			</div>
		</div>		

	</div>
</div>


<?= $this->endSection();?>

<!-- Load JS -->

<?= $this->Section('script_js');?>
	
	<?= $this->include('\Modules\Dailymovementout\Views\js'); ?>

<?= $this->endSection();?>