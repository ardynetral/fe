<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>Vessel</h2>
		<em>add Vessel</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> Add Vessel</h3>
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
							<label for="code" class="col-sm-2 control-label text-right">Vessel ID</label>
							<div class="col-sm-6">
								<input type="text" name="code" class="form-control" id="code" value="<?=@$data['vesid'];?>" readonly>
							</div>
						</div>	
						<div class="form-group">
							<label for="description" class="col-sm-2 control-label text-right">Vessel Title</label>
							<div class="col-sm-6">
								<input type="text" name="title" class="form-control" id="title" value="<?=@$data['vestitle'];?>">
							</div>
						</div>	
						<div class="form-group">
							<label for="operator" class="col-sm-2 control-label text-right">Vessel Operator</label>
							<div class="col-sm-6">
								<input type="text" name="operator" class="form-control" id="operator" value="<?=@$data['vesopr'];?>">
							</div>
						</div>							
						<div class="form-group">
							<label for="cncode" class="col-sm-2 control-label text-right">Vessel Flag</label>
							<div class="col-sm-6">
								<?=$country_dropdown;?>
							</div>
						</div>	
						<div class="form-group">
							<label for="operator" class="col-sm-2 control-label text-right">Principal</label>
							<div class="col-sm-6">
								<input type="text" name="prcode" class="form-control" id="prcode" value="<?=@$data['prcode'];?>">
							</div>
						</div>			
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="button" id="updateData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
								<a href="<?=site_url('vessel')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
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

	<?= $this->include('\Modules\Vessel\Views\js'); ?>

<?= $this->endSection();?>