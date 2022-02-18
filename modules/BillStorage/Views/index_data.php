
<form id="#formCType" class="form-horizontal" role="form">
	<?= csrf_field(); ?>
	<fieldset>
		<div class="form-group">
			<label for="cncode" class="col-sm-2 control-label text-right">Depot</label>
			<div class="col-sm-3">
				<select name="depot" id="depot" class="select-length">
					<option value="">- select -</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="cncode" class="col-sm-2 control-label text-right">Principal</label>
			<div class="col-sm-3">
				<select name="prcode" id="prcode" class="select-length">
					<option value="">- select -</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="cpipratgl" class="col-sm-2 control-label text-right">Date</label>
			<div class="col-sm-2">
				<div class="input-group">
					<input type="text" name="startDate" id="startDate" class="form-control tanggal">
					<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="vessel" class="col-sm-2 control-label text-right">Vessel</label>
			<div class="col-sm-3">
				<select name="vessel" id="vessel" class="select-length">
					<option value="">- select -</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="voyage" class="col-sm-2 control-label text-right">Voyage</label>
			<div class="col-sm-2">
				<input type="text" name="voyage" class="form-control" id="voyage" value="<?= @$voyage ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label text-right">Billing Reference No</label>
			<div class="col-sm-2">
				<input type="text" name="billNo" class="form-control" id="billNo" value="<?= @$billNo ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="authNo" class="col-sm-2 control-label text-right">Authentication No</label>
			<div class="col-sm-2">
				<input type="text" name="authNo" class="form-control" id="authNo" value="<?= @$authNo ?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label text-right">Out Depot</label>
			<div class="col-sm-3">
				<input type="checkbox" name="outDepot">
			</div>
		</div>

		<div class="rows">
			<button type="button" id="printPdf" class="btn btn-primary"><i class="fa fa-check-circle"></i> Print to PDF </button>
			<button type="button" id="printExl" class="btn btn-primary"><i class="fa fa-check-circle"></i> Print to Excel</button>
		</div>
	</fieldset>
</form>