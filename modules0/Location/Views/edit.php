<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>Location</h2>
		<em>Location page</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> Edit Location</h3>
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
							<label for="code" class="col-sm-2 control-label text-right">Location Code</label>
							<div class="col-sm-3">
								<input type="text" name="code" class="form-control" id="code" value="<?=@$data['lccode'];?>" readonly>
							</div>
						</div>	
						<div class="form-group">
							<label for="desc" class="col-sm-2 control-label text-right">Description</label>
							<div class="col-sm-8">
								<input type="text" name="desc"class="form-control" id="desc" value="<?=@$data['lcdesc'];?>">
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="button" id="updateData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>
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

	<?= $this->include('\Modules\Location\Views\js'); ?>

<?= $this->endSection();?>