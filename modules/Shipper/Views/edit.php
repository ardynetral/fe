<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2><?=$page_title?></h2>
		<em>Edit <?=$page_title?> page</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> Edit <?=$page_title?></h3>
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
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="cucode" class="col-sm-3 control-label text-right">Customer Code</label>
									<div class="col-sm-9">
										<input type="text" name="cucode" class="form-control" id="cucode" value="<?=@$data['cucode']?>">
									</div>
								</div>	
								<div class="form-group">
									<label for="cuname" class="col-sm-3 control-label text-right">Name</label>
									<div class="col-sm-9">
										<input type="text" name="cuname" class="form-control" id="cuname" value="<?=@$data['cuname']?>">
									</div>
								</div>	
								<div class="form-group">
									<label for="cuaddr" class="col-sm-3 control-label text-right">Address</label>
									<div class="col-sm-9">
										<input type="text" name="cuaddr" class="form-control" id="cuaddr" value="<?=@$data['cuaddr']?>">
									</div>
								</div>	
								<div class="form-group">
									<label for="cuzip" class="col-sm-3 control-label text-right">Zip Code</label>
									<div class="col-sm-9">
										<input type="text" name="cuzip" class="form-control" id="cuzip" value="<?=@$data['cuzip']?>">
									</div>
								</div>
								<div class="form-group">
									<label for="cncode" class="col-sm-3 control-label text-right">Country</label>
									<div class="col-sm-9">
										<?=$country_dropdown;?>
									</div>
								</div>				
								<div class="form-group">
									<label for="cuphone" class="col-sm-3 control-label text-right">Phone</label>
									<div class="col-sm-9">
										<input type="text" name="cuphone" class="form-control" id="cuphone" value="<?=@$data['cuphone']?>">
									</div>
								</div>
								<div class="form-group">
									<label for="cufax" class="col-sm-3 control-label text-right">Fax</label>
									<div class="col-sm-9">
										<input type="text" name="cufax" class="form-control" id="cufax" value="<?=@$data['cufax']?>">
									</div>
								</div>								

								<div class="form-group">
									<label for="cucontact" class="col-sm-3 control-label text-right">Contact</label>
									<div class="col-sm-9">
										<input type="text" name="cucontact" class="form-control" id="cucontact" value="<?=@$data['cucontact']?>">
									</div>
								</div>		
								<div class="form-group">
									<label for="cuemail" class="col-sm-3 control-label text-right">Email</label>
									<div class="col-sm-9">
										<input type="text" name="cuemail" class="form-control" id="cuemail" value="<?=@$data['cuemail']?>">
									</div>
								</div>		
								<div class="form-group">
									<label for="cunpwp" class="col-sm-3 control-label text-right">NPWP</label>
									<div class="col-sm-9">
										<input type="text" name="cunpwp" class="form-control" id="cunpwp" value="<?=@$data['cunpwp']?>">
									</div>
								</div>
								<div class="form-group">
									<label for="cuskada" class="col-sm-3 control-label text-right">SKADA</label>
									<div class="col-sm-9">
										<input type="text" name="cuskada" class="form-control" id="cuskada" value="<?=@$data['cuskada']?>">
									</div>
								</div>
								<div class="form-group">
									<label for="cudebtur" class="col-sm-3 control-label text-right">Debitur Code</label>
									<div class="col-sm-9">
										<input type="text" name="cudebtur" class="form-control" id="cudebtur" value="<?=@$data['cudebtur']?>">
									</div>
								</div>	

								<div class="form-group">
									<label for="cunppkp" class="col-sm-3 control-label text-right">NPPKP</label>
									<div class="col-sm-9">
										<input type="text" name="cunppkp" class="form-control" id="cunppkp" value="<?=@$data['cunppkp']?>">
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-3 col-sm-12"><br>
									<button type="button" id="updateData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>&nbsp;
									<a href="<?=site_url('shipper')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
									</div>
								</div>
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

	<?= $this->include('\Modules\Shipper\Views\js'); ?>

<?= $this->endSection();?>