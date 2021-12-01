<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>Material</h2>
		<em>add material</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> Add Material</h3>
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
							<label for="code" class="col-sm-2 control-label text-right">Material Code</label>
							<div class="col-sm-3">
								<input type="text" name="code" class="form-control" id="code" value="<?=@$data['mtcode']?>">
							</div>
						</div>	
						<div class="form-group">
							<label for="desc" class="col-sm-2 control-label text-right">Description</label>
							<div class="col-sm-3">
								<input type="text" name="desc" class="form-control" id="description" value="<?=@$data['mtdesc']?>">
							</div>
						</div>				
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="button" id="updateData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
								<a href="<?=site_url('material')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
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

	<?= $this->include('\Modules\Material\Views\js'); ?>

<?= $this->endSection();?>