<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>Container Code</h2>
		<em>add container code</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> Add Container Code (CC)</h3>
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
							<label for="cccode" class="col-sm-2 control-label text-right">Code</label>
							<div class="col-sm-3">
								<input type="text" name="cccode" class="form-control" id="cccode">
							</div>
						</div>	
						<div class="form-group">
							<label for="ctype" class="col-sm-2 control-label text-right">Container Type</label>
							<div class="col-sm-3">
								<?=$ctype;?>
							</div>
						</div>
						<div class="form-group">
							<label for="length" class="col-sm-2 control-label text-right">Length</label>
							<div class="col-sm-3">
								<input type="text" name="length" class="form-control" id="length">
							</div>
						</div>						
						<div class="form-group">
							<label for="height" class="col-sm-2 control-label text-right">Height</label>
							<div class="col-sm-3">
								<input type="text" name="height" class="form-control" id="height">
							</div>
						</div>	
						<div class="form-group">
							<label for="alias1" class="col-sm-2 control-label text-right">Alias 1</label>
							<div class="col-sm-3">
								<input type="text" name="alias1"class="form-control" id="alias1">
							</div>
						</div>	
						<div class="form-group">
							<label for="alias2" class="col-sm-2 control-label text-right">Alias 2</label>
							<div class="col-sm-3">
								<input type="text" name="alias2"class="form-control" id="alias2">
							</div>
						</div>																		
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="button" id="saveCCode" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
								<a href="<?=site_url('containercode')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
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

	<?= $this->include('\Modules\ContainerCode\Views\js'); ?>

<?= $this->endSection();?>