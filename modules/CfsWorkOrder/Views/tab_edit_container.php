<div class="row">
	<div class="col-sm-12">
		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> WO Container</h3>
			</div>
			<div class="widget-content">
				<p><button class="btn btn-success" id="insertContainer"><i class="fa fa-plus"></i>&nbsp;Add Container</button>
				</p>
				<div class="table-responsive vscroll">
				<table id="tblList_edit" class="table table-hover table-bordered" style="width:100%;">
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
					<tbody id="listOrderPra">
					<?php if(isset($dataContainer[0]) && $dataContainer[0]!=""):?>
						<?php $no=1; foreach($dataContainer[0] as $c):?>
						<tr>
							<td>
								<a href='#' class='btn btn-xs btn-primary edit' data-kode="<?=$c['wocid']?>">edit</a>
								<a href='#' class='btn btn-xs btn-danger delete' data-kode="<?=$c['wocid']?>">delete</a>
							</td>									
							<td><?=$no;?></td>
							<td class="crno"><?=$c['crno'];?></td>
							<td class="cccode"><?=$c['cccode'];?></td>
							<td class="ctcode"><?=$c['ctcode'];?></td>
							<td class="cclength"><?=$c['cclength'];?></td>
							<td class="ccheight"><?=$c['ccheight'];?></td>
							<td class="fe"><?=((isset($c['fe'])&&$c['fe']=="1")?'Full':'Empty')?></td>
							<td class="sealno"><?=$c['sealno'];?></td>
							<td class="remark"><?=$c['remark'];?></td>
						</tr>
						<?php $no++; endforeach; ?>
					<?php else:?>
						<tr>
							<td colspan="11">Data not found</td>
						</tr>						
					<?php endif;?>
					</tbody>

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
<div class="modal fade in" id="containerEditModal" tabindex="-1" role="dialog" aria-labelledby="containerEditModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalEditLabel">Container</h4>
			</div>
			<form id="formContainerEdit" class="form-horizontal" role="form">
			<div class="modal-body">

				<?= csrf_field() ?>
				<fieldset>
					<div class="form-group">
						<label class="col-sm-3 control-label text-right">Container No. </label>
						<div class="col-sm-7">
							<input type="hidden" name="act" class="form-control" id="act" value="">
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
				<button type="button" id="saveDetail" class="btn btn-custom-primary"><i class="fa fa-check-circle"></i> Save Container</button>
				<button type="button" id="updateDetail" class="btn btn-custom-primary" style="display:none;"><i class="fa fa-check-circle"></i> Update Container</button>
			</div>
			</form>			
		</div>
	</div>
</div>