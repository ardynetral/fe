<div class="row">
	<div class="col-sm-12">
		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Repo Container</h3>
			</div>
			<div class="widget-content">
				<p><button class="btn btn-success" data-toggle="modal" data-target="#myModal" id="insertContainer"><i class="fa fa-plus"></i>&nbsp;Add Container</button>
				</p>
				<div class="table-responsive vscroll">
				<table id="rcTable" class="table table-hover table-bordered" style="width:100%;">
					<thead>
						<tr>
							<th></th>
							<th>No.</th>
							<th>Container #</th>
							<th>ID Code</th>
							<th>Type</th>
							<th>Length</th>
							<th>Height</th>
							<th>Hold/Release</th>
							<th>Remark</th>
						</tr>
					</thead>
					<?php if(isset($containers) && $containers!=""):?>
					<tbody id="listOrderPra">
						<?php $no=1; foreach($containers as $c):?>
						<tr>
							<td>
								<a href='#' class='btn btn-xs btn-danger delete' data-kode="<?=$c['repocrnoid']?>">delete</a>
							</td>									
							<td><?=$no;?></td>
							<td><?=$c['crno'];?></td>
							<td><?=$c['cccode'];?></td>
							<td><?=$c['ctcode'];?></td>
							<td><?=$c['cclength'];?></td>
							<td><?=$c['ccheight'];?></td>
							<td><?=((isset($c['reposhold'])&&$c['reposhold']==1)?'Hold':'Release');?></td>
							<td><?=$c['reporemark'];?></td>
						</tr>
						<?php $no++; endforeach; ?>
					</tbody>
					<?php else:?>
						<tr><td colspan="9">Data Container kosong.</td></tr>
					<?php endif?>
				</table>
				</div>						
			</div>
			<div class="widget-footer text-center">
				<a href="<?=site_url('repoin');?>" class="btn btn-default" id="">Kembali</a>
				<a href="#" class="btn btn-danger" id="updateNewData"><i class="fa fa-save"></i> Save All</a>
			</div>					
		</div>
	</div>
</div>


<!-- FORM CONTAINER -->
<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Add Repo Container</h4>
			</div>
			<form id="formDetail" class="form-horizontal" role="form">
			<div class="modal-body">

				<?= csrf_field() ?>
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