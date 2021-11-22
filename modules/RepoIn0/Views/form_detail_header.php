<form id="#form" class="form-horizontal" role="form">
	<?= csrf_field() ?>
	<fieldset>
		<div class="form-group">
			<label class="col-sm-5 control-label text-right">Container No. </label>
			<div class="col-sm-7">
				<input type="hidden" name="praid" class="form-control" id="praid" value="<?=@$praid?>">
				<input type="text" name="crno" class="form-control" id="crno">
				<i class="err-crno text-danger"></i>
			</div>
		</div>	
		<div class="form-group">
			<label class="col-sm-5 control-label text-right">ID Code </label>
			<div class="col-sm-7">
				<?=ccode_dropdown();?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-5 control-label text-right">Container Type</label>
			<div class="col-sm-7">
				<input type="text" id="ctcode" class="form-control" readonly="">
			</div>
		</div>	

		<div class="form-group">
			<label class="col-sm-5 control-label text-right">Lenght</label>
			<div class="col-sm-7">
				<input type="text" name="cclength" id="cclength" class="form-control" readonly="">
			</div>	
		</div>	
		<div class="form-group">
			<label class="col-sm-5 control-label text-right">Height</label>
			<div class="col-sm-7">
				<input type="text" name="ccheight" id="ccheight" class="form-control" readonly="">
			</div>	
		</div>			
		<div class="form-group">
			<label class="col-sm-5 control-label text-right">F/E</label>
			<div class="col-sm-7">
				<label class="control-inline fancy-radio custom-bgcolor-green">
					<input type="radio" name="cpife" id="cpife" value="1">
					<span><i></i>Full</span>
				</label>
				<label class="control-inline fancy-radio custom-bgcolor-green">
					<input type="radio" name="cpife" id="cpife" value="0">
					<span><i></i>Empty</span>
				</label>				
			</div>	
		</div>					
		<div class="form-group">
			<label class="col-sm-5 control-label text-right">Hold</label>
			<div class="col-sm-7">
				<label class="control-inline fancy-checkbox custom-color-green">
					<input type="checkbox" name="cpishold" id="cpishold" value="0">
					<span></span>
				</label>
			</div>	
		</div>	
		<div class="form-group">
			<label class="col-sm-5 control-label text-right">Remark</label>
			<div class="col-sm-7">
				<input type="text" name="cpiremark" id="cpiremark" class="form-control" >
			</div>	
		</div>								
		<div class="form-group">
			<div class="col-sm-offset-5 col-sm-7">
				<button type="button" id="saveDetail" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>
			</div>
		</div>						
	</fieldset>
</form>			