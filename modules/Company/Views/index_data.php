<?php if($data ==''):?>
	<p class="alert alert-warning"> Data not found.</p>
<?php else : 
$cp = $data[0];
?>

<div class="row">
	<div class="col-md-12">

		<form id="#mainForm" class="form-horizontal" role="form">
			<?= csrf_field() ?>
			<fieldset>
				<div class="form-group">
					<label for="name" class="col-sm-2 control-label text-right">Company Name</label>
					<div class="col-sm-3">
						<input type="text" name="name" class="form-control" id="name" value="<?=$cp['pagroup']?>">
					</div>
				</div>	
				<div class="form-group">
					<label for="address" class="col-sm-2 control-label text-right">Address</label>
					<div class="col-sm-3">
						<textarea name="" class="form-control text-left" rows="4">
							<?=$cp['paaddr']?>
						</textarea>
					</div>
				</div>		
				<div class="form-group">
					<label for="phone" class="col-sm-2 control-label text-right">Phone</label>
					<div class="col-sm-3">
						<input type="text" name="phone" class="form-control" id="phone" value="<?=$cp['paphone']?>">
					</div>
				</div>		
				<div class="form-group">
					<label for="fax" class="col-sm-2 control-label text-right">Fax</label>
					<div class="col-sm-3">
						<input type="text" name="fax" class="form-control" id="fax" value="<?=$cp['pafax']?>">
					</div>
				</div>				
				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<!-- <button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp; -->
						<button type="button" id="cancel" class="btn btn-default"><i class="fa fa-times-circle"></i> Back</button>
					</div>
				</div>						
			</fieldset>
		</form>

	</div>
</div>

<?php endif; ?>	