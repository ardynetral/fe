<?php 
$token = get_token_item();
$group_id = $token['groupId'];
?>	
	<form id="formDetail" class="form-horizontal" role="form">
		<?= csrf_field() ?>
		<input type="hidden" name="pracrnoid" id="pracrnoid">
		<fieldset>
			<div class="form-group">
				<label class="col-sm-4 control-label text-right">Container No. </label>
				<div class="col-sm-8">
					<input type="hidden" name="pra_id" class="form-control" id="pra_id" value="<?=@$praid?>">
					<input type="text" name="crno" class="form-control" id="crno" disabled>
					<i class="err-crno text-danger"></i>
				</div>
			</div>	
			<div class="form-group">
				<label class="col-sm-4 control-label text-right">ID Code </label>
				<div class="col-sm-8">
					<?=ccode_dropdown("");?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-4 control-label text-right">Container Type</label>
				<div class="col-sm-8">
					<input type="text" id="ctcode" class="form-control" readonly="">
				</div>
			</div>	

			<div class="form-group">
				<label class="col-sm-4 control-label text-right">Lenght</label>
				<div class="col-sm-8">
					<input type="text" name="cclength" id="cclength" class="form-control" readonly="">
				</div>	
			</div>	
			<div class="form-group">
				<label class="col-sm-4 control-label text-right">Height</label>
				<div class="col-sm-8">
					<input type="text" name="ccheight" id="ccheight" class="form-control" readonly="">
				</div>	
			</div>			
			<div class="form-group">
				<label class="col-sm-4 control-label text-right">F/E</label>
				<div class="col-sm-8">
					<label class="control-inline fancy-radio custom-bgcolor-green">
						<input type="radio" name="cpife" id="cpife" value="1">
						<span><i></i>Full</span>
					</label>
					<label class="control-inline fancy-radio custom-bgcolor-green">
						<input type="radio" name="cpife" id="cpife" value="0" checked>
						<span><i></i>Empty</span>
					</label>				
				</div>	
			</div>					
				
				<input type="hidden" name="cpishold" id="cpishold" value="0">

			<div class="form-group">
				<label class="col-sm-4 control-label text-right">Remark</label>
				<div class="col-sm-8">
					<input type="text" name="cpiremark" id="cpiremark" class="form-control" >
				</div>	
			</div>	
			<div class="form-group">
				<label class="col-sm-4 control-label text-right">Seal Number</label>
				<div class="col-sm-8">
					<input type="text" name="sealno" id="sealno" class="form-control" >
				</div>	
			</div>								
			<div class="form-group">
				<div class="col-sm-offset-4 col-sm-8">
					<button type="button" id="saveDetail" class="btn btn-primary" data-act="add" disabled><i class="fa fa-check-circle"></i> Save</button>
				</div>
			</div>						
		</fieldset>
	</form>			