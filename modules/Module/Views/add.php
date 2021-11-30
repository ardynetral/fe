<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>Module</h2>
		<em>add Module</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> Add Module</h3>
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

				<form id="#formModule" class="form-horizontal" role="form">
					<?= csrf_field() ?>
					<fieldset>
						<div class="form-group">
							<label for="module_name" class="col-sm-2 control-label text-right">Module Name</label>
							<div class="col-sm-3">
								<input type="text" name="module_name" class="form-control" id="module_name">
							</div>
						</div>	
						<div class="form-group">
							<label for="module_description" class="col-sm-2 control-label text-right">Module Description</label>
							<div class="col-sm-3">
								<input type="text" name="module_description" class="form-control" id="module_description">
							</div>
						</div>							
						<div class="form-group">
							<label for="module_parent" class="col-sm-2 control-label text-right">Parent</label>
							<div class="col-sm-3">
								<?=module_dropdown();?>
							</div>
						</div>	
						<div class="form-group">
							<label for="module_config" class="col-sm-2 control-label text-right">Config</label>
							<div class="col-sm-3">
								<select name="module_config[]" id="module_config" class="module_config select2 select2-multiple" multiple>
									<optgroup label="Select module config">
										<option value="VIEW">VIEW</option>
										<option value="INSERT">INSERT</option>
										<option value="UPDATE">UPDATE</option>
										<option value="DELETE">DELETE</option>
									</optgroup>
								</select>
							</div>
						</div>	
						<div class="form-group">
							<label for="module_var" class="col-sm-2 control-label text-right">Module Var</label>
							<div class="col-sm-3">
								<input type="text" name="module_var" class="form-control" id="module_var">
							</div>
						</div>	
						<div class="form-group">
							<label for="module_url" class="col-sm-2 control-label text-right">Module URL</label>
							<div class="col-sm-3">
								<input type="text" name="module_url" class="form-control" id="module_url">
							</div>
						</div>													
						<div class="form-group">
							<label for="module_type" class="col-sm-2 control-label text-right">Type</label>
							<div class="col-sm-3">
								<select name="module_type" id="module_type" class="form-control">
									<option value="">-select-</option>
									<option value="module">MODULE</option>
									<option value="any">ANY</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="module_content" class="col-sm-2 control-label text-right">Module Content</label>
							<div class="col-sm-3">
								<input class="form-control" name="module_content" id="module_content" type="text">		
							</div>
						</div>						
						<div class="form-group">
							<label for="module_icon" class="col-sm-2 control-label text-right">Icon</label>
							<div class="col-sm-3">
								<input class="form-control icp icp-auto iconpicker-element iconpicker-input module_icon" name="module_icon" id="module_icon" type="text">		
							</div>
						</div>
						<div class="form-group">
							<label for="sort_index" class="col-sm-2 control-label text-right">Sort index</label>
							<div class="col-sm-3">
								<input type="text" name="sort_index" class="form-control" id="sort_index">
							</div>
						</div>
						<div class="form-group">
							<label for="module_status" class="col-sm-2 control-label text-right">Status</label>
							<div class="col-sm-3">
								<select name="module_status" id="module_status" class="form-control">
									<option value="">-select-</option>
									<option value="1">ACTIVE</option>
									<option value="0">INACTIVE</option>
								</select>
							</div>
						</div>	
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
								<a href="<?=site_url('module')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
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

	<?= $this->include('\Modules\Module\Views\js'); ?>

<?= $this->endSection();?>