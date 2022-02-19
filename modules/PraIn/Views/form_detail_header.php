<?php 
$token = get_token_item();
$group_id = $token['groupId'];
?>	
<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Add Container</h4>
			</div>
			<div class="modal-body">

<form id="formDetail" class="form-horizontal" role="form">
	<?= csrf_field() ?>
	<input type="hidden" name="pracrnoid" id="pracrnoid">
	<fieldset>		
		<div class="form-group">
			<label class="col-sm-4 control-label text-right">Container No. </label>
			<div class="col-sm-8">
				<input type="hidden" name="pra_id" class="form-control" id="pra_id">
				<input type="text" name="crno" class="form-control" id="crno">
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
<!-- 			<div class="form-group">
			<label class="col-sm-4 control-label text-right">Hold</label>
			<div class="col-sm-8">
				<label class="control-inline fancy-checkbox custom-color-green"> -->
					<input type="hidden" name="cpishold" id="cpishold" value="0">
<!-- 						<span></span>
				</label>
			</div>	
		</div>	 -->
		<div class="form-group">
			<label class="col-sm-4 control-label text-right">Remark</label>
			<div class="col-sm-8">
				<input type="text" name="cpiremark" id="cpiremark" class="form-control" >
			</div>	
		</div>								
		<div class="form-group">
			<div class="col-sm-offset-4 col-sm-8">
				<button type="button" id="saveDetail" class="btn btn-primary" data-act="add" disabled><i class="fa fa-check-circle"></i> Save</button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
			</div>
		</div>						
	</fieldset>
</form>		
			</div>
		</div>
	</div>
</div>	