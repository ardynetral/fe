<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Add Repo Container</h4>
			</div>
			<form id="formDetail" class="form-horizontal" role="form">
			<div class="modal-body">

				<fieldset>
					<div class="form-group">
						<label class="col-sm-3 control-label text-right">Container No. </label>
						<div class="col-sm-7">
							<input type="hidden" name="repoid" class="form-control" id="repoid" value="<?=@$repoid?>">
							<input type="hidden" name="repo_orderno" class="form-control" id="repo_orderno" value="<?=@$reorderno?>">
							<input type="text" name="crno" class="form-control" id="crno">
							<i class="err-crno text-danger"></i>
						</div>
					</div>	
					<div class="form-group">
						<label class="col-sm-3 control-label text-right">ID Code </label>
						<div class="col-sm-7">
							<!-- <input type="text" id="cccode" class="form-control" readonly="">							 -->
							<?=select_ccode("");?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label text-right">Container Type</label>
						<div class="col-sm-7">
							<input type="text" id="ctcode" class="form-control" readonly="">
						</div>
					</div>	

					<div class="form-group">
						<label class="col-sm-3 control-label text-right">Lenght</label>
						<div class="col-sm-7">
							<input type="text" name="cclength" id="cclength" class="form-control" readonly="">
						</div>	
					</div>	
					<div class="form-group">
						<label class="col-sm-3 control-label text-right">Height</label>
						<div class="col-sm-7">
							<input type="text" name="ccheight" id="ccheight" class="form-control" readonly="">
						</div>	
					</div>			
<!-- 					<div class="form-group" style="display:none;">
						<label class="col-sm-3 control-label text-right">F/E</label>
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
					</div>	 -->				
					<div class="form-group" style="display:none;">
						<label class="col-sm-3 control-label text-right">Hold</label>
						<div class="col-sm-7">
							<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="cpishold" id="cpishold" value="0">
								<span></span>
							</label>
						</div>	
					</div>	
					<div class="form-group" style="display:none;">
						<label class="col-sm-3 control-label text-right">Seal Number</label>
						<div class="col-sm-7">
							<input type="text" name="sealno" id="sealno" class="form-control" value="">
						</div>	
					</div>						
					<div class="form-group">
						<label class="col-sm-3 control-label text-right">Remark</label>
						<div class="col-sm-7">
							<textarea name="reporemark" id="reporemark" class="form-control" ></textarea>
						</div>	
					</div>		
				</fieldset>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
				<button type="button" id="saveDetail" class="btn btn-custom-primary"><i class="fa fa-check-circle"></i> Save Container</button>
				<button type="button" id="updateDetail" class="btn btn-custom-primary" style="display:none;"><i class="fa fa-check-circle"></i> Update Container</button>
			</div>
			</form>			
		</div>
	</div>
</div>