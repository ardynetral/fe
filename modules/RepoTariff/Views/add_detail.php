<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>Repo Tariff Detail</h2>
		<em>Add Repo Tariff Detail</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> Add Repo Tariff Detail</h3>
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
						<legend></legend>
						<div class="form-group">
							<label for="code" class="col-sm-2 control-label text-right">Repo Type</label>
							<div class="col-sm-3">
								<input type="text" name="type" class="form-control" id="type">
							</div>
						</div>	
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">From</label>
							<div class="col-sm-3">
								<input type="text" name="from" class="form-control" id="from">
							</div>
						</div>
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">To</label>
							<div class="col-sm-3">
								<input type="text" name="from" class="form-control" id="from">
							</div>
						</div>	

						<legend>Package Rate</legend>
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">Currency</label>
							<div class="col-sm-3">
								<?=currency_dropdown('curr','');?>
							</div>
						</div>
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">20"</label>
							<div class="col-sm-3">
								<input type="text" name="from" class="form-control" id="from">
							</div>
						</div>
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">40"</label>
							<div class="col-sm-3">
								<input type="text" name="from" class="form-control" id="from">
							</div>
						</div>	
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">45"</label>
							<div class="col-sm-3">
								<input type="text" name="from" class="form-control" id="from">
							</div>
						</div>

						<legend>Repo Breakdown</legend>
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">LOLO Currency</label>
							<div class="col-sm-3">
								<?=currency_dropdown('lolocurr','');?>
							</div>
						</div>
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">Lift On 20"</label>
							<div class="col-sm-3">
								<input type="text" name="from" class="form-control" id="from">
							</div>
						</div>
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">Lift On 40"</label>
							<div class="col-sm-3">
								<input type="text" name="from" class="form-control" id="from">
							</div>
						</div>	
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">Lift On 45"</label>
							<div class="col-sm-3">
								<input type="text" name="from" class="form-control" id="from">
							</div>
						</div>
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">Lift Off 20"</label>
							<div class="col-sm-3">
								<input type="text" name="from" class="form-control" id="from">
							</div>
						</div>
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">Lift Off 40"</label>
							<div class="col-sm-3">
								<input type="text" name="from" class="form-control" id="from">
							</div>
						</div>	
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">Lift Off 45"</label>
							<div class="col-sm-3">
								<input type="text" name="from" class="form-control" id="from">
							</div>
						</div>
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">Dic Currency</label>
							<div class="col-sm-3">
								<?=currency_dropdown('doccurr','');?>
							</div>
						</div>
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">Doc Method</label>
							<div class="col-sm-3">
								<input type="text" name="from" class="form-control" id="from">
							</div>
						</div>
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">Doc Free"</label>
							<div class="col-sm-3">
								<input type="text" name="from" class="form-control" id="from">
							</div>
						</div>
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">Haulage Currency</label>
							<div class="col-sm-3">
								<?=currency_dropdown('hcurr','');?>
							</div>
						</div>
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">Haulage 20"</label>
							<div class="col-sm-3">
								<input type="text" name="from" class="form-control" id="from">
							</div>
						</div>
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">Haulage 40"</label>
							<div class="col-sm-3">
								<input type="text" name="from" class="form-control" id="from">
							</div>
						</div>	
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">Haulage 45"</label>
							<div class="col-sm-3">
								<input type="text" name="from" class="form-control" id="from">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="button" id="saveDetail" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
								<button type="button" id="cancel" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</button>
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

	<?= $this->include('\Modules\RepoTariff\Views\js'); ?>

<?= $this->endSection();?>