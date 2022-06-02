<div class="row">
	<div class="col-sm-12">
		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> WO Container</h3>
			</div>
			<div class="widget-content">
				<p><button class="btn btn-success" data-toggle="modal" data-target="#containerModal" id="insertContainer"><i class="fa fa-plus"></i>&nbsp;Add Container</button>
				</p>
				<div class="table-responsive vscroll">
				<table id="tblList_add" class="table table-hover table-bordered" style="width:100%;">
					<thead>
						<tr>
							<th></th>
							<th>No.</th>
							<th>Container #</th>
							<th>ID Code</th>
							<th>Type</th>
							<th>Length</th>
							<th>Height</th>
							<th>Full/Empty</th>
							<th>Seal No.</th>
							<th>Remark</th>
						</tr>
					</thead>

					<tbody> </tbody>

				</table>
				</div>						
			</div>
			<div class="widget-footer text-center">
				<a href="<?=site_url('cfswo');?>" class="btn btn-default" id="">Kembali</a>
				<!-- <a href="#" class="btn btn-danger" id="updateNewData"><i class="fa fa-save"></i> Save All</a> -->
			</div>					
		</div>
	</div>
</div>


<!-- FORM CONTAINER -->
<div class="modal fade in" id="containerModal" tabindex="-1" role="dialog" aria-labelledby="containerModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="containerModalLabel">Add Container</h4>
			</div>
			<form id="formContainer" class="form-horizontal" role="form">
			<div class="modal-body">

				<?= csrf_field() ?>
				<fieldset>
					<div class="form-group">
						<label class="col-sm-3 control-label text-right">Container No. </label>
						<div class="col-sm-7">
							<input type="hidden" name="statusContainer" class="form-control" id="statusContainer" value="">
							<input type="hidden" name="wono_id" class="form-control" id="wono_id" value="">
							<input type="hidden" name="wo_no" class="form-control" id="wo_no" value="">
							<input type="hidden" name="wo_type" class="form-control" id="wo_type" value="">
							<input type="hidden" name="wo_stok" class="form-control" id="wo_stok" value="">
							<input type="text" name="crno" class="form-control" id="crno">
							<i class="err-crno text-danger"></i>
						</div>
					</div>	
					<div class="form-group">
						<label class="col-sm-3 control-label text-right">ID Code </label>
						<div class="col-sm-7">
							<!-- <input type="text" id="cccode" class="form-control" readonly="">							 -->
							<?=$select_ccode;?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label text-right">Container Type</label>
						<div class="col-sm-7">
							<input type="text" name="ctcode" id="ctcode" class="form-control" readonly="">
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
					<div class="form-group">
						<label class="col-sm-3 control-label text-right">F/E</label>
						<div class="col-sm-7">
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
					<div class="form-group" style="display:none;">
						<label class="col-sm-3 control-label text-right">Hold</label>
						<div class="col-sm-7">
							<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="cpishold" id="cpishold" value="0">
								<span></span>
							</label>
						</div>	
					</div>	
					<div class="form-group">
						<label class="col-sm-3 control-label text-right">Seal Number</label>
						<div class="col-sm-7">
							<input type="text" name="sealno" id="sealno" class="form-control">
						</div>	
					</div>						
					<div class="form-group">
						<label class="col-sm-3 control-label text-right">Remark</label>
						<div class="col-sm-7">
							<textarea name="remark" id="remark" class="form-control" ></textarea>
						</div>	
					</div>		
				</fieldset>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
				<button type="submit" id="saveContainer" class="btn btn-custom-primary"><i class="fa fa-check-circle"></i> Save Container</button>
				<button type="button" id="updateContainer" class="btn btn-custom-primary" style="display:none;"><i class="fa fa-check-circle"></i> Update Container</button>
			</div>
			</form>			
		</div>
	</div>
</div>