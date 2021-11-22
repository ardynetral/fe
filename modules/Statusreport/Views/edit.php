<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>Principal</h2>
		<em>Principal page</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> Edit Principal</h3>
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
							<label for="prcode" class="col-sm-2 control-label text-right">Principal Code</label>
							<div class="col-sm-3">
								<input type="text" name="prcode" class="form-control" id="prcode" value="<?=@$data['prcode'];?>">
							</div>
						</div>	
						<div class="form-group">
							<label for="cucode" class="col-sm-2 control-label text-right">Customers Code</label>
							<div class="col-sm-3">
								<input type="text" name="cucode" class="form-control" id="cucode" value="<?=@$data['cucode'];?>">
							</div>
						</div>							
						<div class="form-group">
							<label for="prname" class="col-sm-2 control-label text-right">Name</label>
							<div class="col-sm-3">
								<input type="text" name="prname" class="form-control" id="prname" value="<?=@$data['prname'];?>">
							</div>
						</div>
						<div class="form-group">
							<label for="praddr" class="col-sm-2 control-label text-right">Address</label>
							<div class="col-sm-8">
								<input type="text" name="praddr" class="form-control" id="praddr" value="<?=@$data['praddr'];?>">
							</div>
						</div>					
						<div class="form-group">
							<label for="cncode" class="col-sm-2 control-label text-right">Country</label>
							<div class="col-sm-3">
								<?=$country_dropdown;?>
							</div>
						</div>
						<div class="form-group">
							<label for="prremark" class="col-sm-2 control-label text-right">Remark</label>
							<div class="col-sm-3">
								<input type="text" name="prremark" class="form-control" id="prremark" value="<?=@$data['prremark'];?>">
							</div>
						</div>							
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="button" id="updateData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
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

	<?= $this->include('\Modules\Principal\Views\js'); ?>

<?= $this->endSection();?>