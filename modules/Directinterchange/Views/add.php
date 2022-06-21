<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>Direct Interchange</h2>
		<em>add Direct Interchange</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> Add Direct Interchange</h3>
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
							<label for="crno" class="col-sm-2 control-label text-right">Container No</label>
							<div class="col-sm-3">
								<input type="text" name="crno" class="form-control" id="crno">
							</div>
						</div>		
						<!-- Principal lama -->
						<div class="form-group">
							<label for="chfrom" class="col-sm-2 control-label text-right">Principal</label>
							<div class="col-sm-3">
								<input type="text" name="cpopr0" id="cpopr0" class="form-control" readonly>
							</div>
						</div>	
						<div class="form-group">
							<label for="chto" class="col-sm-2 control-label text-right">Customer</label>
							<div class="col-sm-3">
								<input type="text" name="cpcust0" class="form-control" id="cpcust0" readonly>
							</div>
						</div>	
						<!-- Principal baru -->
						<div class="form-group">
							<label for="chfrom" class="col-sm-2 control-label text-right">Principal</label>
							<div class="col-sm-3">
								<?=principal_dropdown("");?>
							</div>
						</div>	
						<div class="form-group">
							<label for="chto" class="col-sm-2 control-label text-right">Customer</label>
							<div class="col-sm-3">
								<input type="text" name="cpcust" class="form-control" id="cpcust" readonly>
							</div>
						</div>	
						<div class="form-group">
							<label for="chto" class="col-sm-2 control-label text-right">On hire Date</label>
							<div class="col-sm-3">
								<div class="input-group">
									<input type="text" name="onhiredate" id="onhiredate" class="form-control tanggal">
									<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>
							</div>	
						</div>									
						<div class="form-group">
							<label for="chgnote" class="col-sm-2 control-label text-right">Note</label>
							<div class="col-sm-3">
								<input type="text" name="chgnote" class="form-control" id="chgnote">
							</div>
						</div>							
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
								<a href="<?=site_url('directinterchange')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
							</div>
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

	<?= $this->include('\Modules\Directinterchange\Views\js'); ?>

<?= $this->endSection();?>