<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>City</h2>
		<em>city page</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> Edit City</h3>
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

				<form id="#formcountry" class="form-horizontal" role="form">
					<?= csrf_field() ?>
					<fieldset>
						<div class="form-group">
							<label for="code" class="col-sm-2 control-label text-right">Name</label>
							<div class="col-sm-3">
								<input type="hidden" name="cityId" class="form-control" id="cityId" value="<?=@$data['city_id'];?>">
								<input type="text" name="name" class="form-control" id="name" value="<?=@$data['city_name'];?>">
							</div>
						</div>	
						<div class="form-group">
							<label for="cncode" class="col-sm-2 control-label text-right">Country</label>
							<div class="col-sm-3">
								<?=$country_dropdown;?>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="button" id="updateData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>
								<a href="<?=site_url('city')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
							</div>
						</div>						
					</fieldset>
				</form>
			</div>
		</div>		

	</div>
</div>


<?= $this->endSection();?>


<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\City\Views\js'); ?>

<?= $this->endSection();?>