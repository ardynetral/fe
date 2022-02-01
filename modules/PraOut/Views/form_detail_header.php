<?php 
$token = get_token_item();
$group_id = $token['groupId'];
?>	
	<form id="formDetail" class="form-horizontal" role="form">
		<?= csrf_field() ?>
		<input type="hidden" name="pracrnoid" id="pracrnoid">
		<fieldset>
			
<!-- 			<?php if(($group_id==4 || $group_id==3)): ?>
			
			<div class="form-group">
				<label for="prcode" class="col-sm-4 control-label text-right">Principal</label>
				<div class="col-sm-8">
					<?= principal_dropdown(""); ?>
				</div>
			</div>
			<div class="form-group">
				<label for="cucode" class="col-sm-4 control-label text-right">Customer</label>
				<div class="col-sm-8">
					<input type="text" name="cucode" class="form-control" id="cucode" value="" readOnly>
				</div>
			</div> 
			
			<?php endif; ?>	 -->

			<div class="form-group">
				<label class="col-sm-4 control-label text-right">Container No. </label>
				<div class="col-sm-8">
					<input type="hidden" name="pra_id" class="form-control" id="pra_id" value="<?=@$praid?>">
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
				<label class="col-sm-4 control-label text-right">Seal Number</label>
				<div class="col-sm-8">
					<input type="text" name="sealno" id="sealno" class="form-control" >
				</div>	
			</div>								
			<div class="form-group">
				<div class="col-sm-offset-4 col-sm-8">
					<?php if(isset($act)&&$act=="add"):?>
					<button type="button" id="saveDetail" class="btn btn-primary" disabled><i class="fa fa-check-circle"></i> Save</button>
					<?php elseif((isset($act)&&$act=="edit")&&($data['orderPraContainers']==null)):?>
						<button type="button" id="saveDetail" class="btn btn-primary"  disabled><i class="fa fa-check-circle"></i> Save</button>
					<?php else:?>
						<button type="button" id="updateDetail" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>
					<?php endif; ?>
				</div>
			</div>						
		</fieldset>
	</form>			