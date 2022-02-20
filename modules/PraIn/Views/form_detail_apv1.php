<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Edit Container</h4>
			</div>
			<div class="modal-body">

<form id="formDetail" class="form-horizontal" role="form">
	<?= csrf_field() ?>
	<input type="hidden" name="pracrnoid" id="pracrnoid">
	<fieldset>
		
		<?php if($group_id!=1): ?>
		
		<div class="form-group">
			<label for="prcode" class="col-sm-4 control-label text-right">Principal</label>
			<div class="col-sm-8">
				<?= principal_dropdown(""); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="cpcust" class="col-sm-4 control-label text-right">Customer</label>
			<div class="col-sm-8">
				<input type="text" name="cucode" class="form-control" id="cucode" readonly="">
			</div>
		</div> 
		
		<?php endif; ?>	

		<div class="form-group">
			<label class="col-sm-4 control-label text-right">Container No. </label>
			<div class="col-sm-8">
				<input type="hidden" name="praid" class="form-control" id="praid" value="<?=@$praid?>">
				<input type="text" name="crno" class="form-control" id="crno" readonly="">
				<i class="err-crno text-danger"></i>
			</div>
		</div>	
		<div class="form-group">
			<label class="col-sm-4 control-label text-right">ID Code </label>
			<div class="col-sm-8">
				<?=ccode_dropdown();?>
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
					<input type="radio" name="cpife" id="cpife" value="1" readonly="">
					<span><i></i>Full</span>
				</label>
				<label class="control-inline fancy-radio custom-bgcolor-green">
					<input type="radio" name="cpife" id="cpife" value="0" readonly="">
					<span><i></i>Empty</span>
				</label>				
			</div>	
		</div>					
		<div class="form-group" style="display: none;">
			<label class="col-sm-4 control-label text-right">Hold</label>
			<div class="col-sm-8">
				<label class="control-inline fancy-checkbox custom-color-green">
					<input type="checkbox" name="cpishold" id="cpishold" value="0" readonly="">
					<span></span>
				</label>
			</div>	
		</div>
		<div class="form-group">
			<label class="col-sm-4 control-label text-right">Deposit</label>
			<div class="col-sm-2">
				<label class="fancy-checkbox custom-color-green">
					<p></p>
					<input type="checkbox" name="deposit" id="deposit" value="0" readonly>
					<span></span>
				</label>
			</div>
			<div class="col-sm-6">
				<input type="text" name="biaya_clean" id="biaya_clean" class="form-control" readonly>
			</div>
		</div>	
		<div class="form-group">
			<label class="col-sm-4 control-label text-right">Lift Off</label>
			<div class="col-sm-8">
				<input type="text" name="biaya_lolo" id="biaya_lolo" value="0" class="form-control" readonly>
			</div>	
		</div>	
		<div class="form-group">
			<label class="col-sm-4 control-label text-right">Cleaning Type</label>
			<div class="col-sm-4">
				<label class="control-inline fancy-checkbox custom-color-green">
					<?= cleaning_method("cleaning_type", "WW"); ?>
				</label>
			</div>
			<div class="col-sm-4">
				<input type="text" name="biaya_lain" id="biaya_lain" class="form-control" value="0">
			</div>
		</div>																							
		<div class="form-group">
			<label class="col-sm-4 control-label text-right">Remark</label>
			<div class="col-sm-8">
				<input type="text" name="cpiremark" id="cpiremark" class="form-control">
			</div>	
		</div>								
		<div class="form-group">
			<div class="col-sm-offset-4 col-sm-8">
				<button type="button" id="apvUpdateContainer" class="btn btn-info"><i class="fa fa-pencil"></i> Update Container</button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
			</div>
		</div>						
	</fieldset>
</form>

			</div>
		</div>
	</div>
</div>