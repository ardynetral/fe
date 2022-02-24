<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>
<?php $data = $data; ?>
<div class="content">
	<div class="main-header">
		<h2>Pra Out</h2>
		<em>Edit Pra Out<?=$data['cpidish']?></em>
	</div>
	<div class="main-content">

		<div class="widget widget-table" id="OrderPra">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Edit Order Pra Out</h3>
			</div>
			<div class="widget-content">
<!-- FORM HEADER -->
				<div class="row">
					<form id="fEditPraIn" class="form-horizontal" role="form" enctype="multipart/form-data">
						<?= csrf_field() ?>
						<input type="hidden" name="praid" id="praid" value="<?=$data['praid']?>">
						<fieldset>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="cpidish " class="col-sm-5 control-label text-right">Destination Port</label>
									<div class="col-sm-4">
										<?=port_dropdown("cpidish",$data['cpidish']);?>
									</div>
									<label class="col-sm-3 control-label">* Port Country</label>
								</div>	
								<div class="form-group">
									<label for="cpidisdat" class="col-sm-5 control-label text-right">Loading Date</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input type="text" name="cpidisdat" id="cpidisdat" class="form-control" value="<?=$data['cpidisdat']?>" readonly="">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										</div>									
									</div>
								</div>
								
								<div class="form-group" style="display:none;">
									<label for="liftoffcharge" class="col-sm-5 control-label text-right">Lift On Charged in Depot</label>
									<div class="col-sm-7">
										<label class="control-inline fancy-checkbox custom-color-green">
											<input type="checkbox" name="liftoffcharge" id="liftoffcharge" value="<?=$data['liftoffcharge']?>" <?=(isset($data['liftoffcharge'])&&($data['liftoffcharge']==1)?'':'checked');?> readonly="">
											<span></span>
										</label>
									</div>
								</div>							
								<div class="form-group" style="display:none;">
									<label for="cpdepo" class="col-sm-5 control-label text-right">Depot</label>
									<div class="col-sm-7">
										<input type="text" value="<?=$depo['dpname'];?>" class="form-control" readonly="">
										<div style="display:none;">
											<?=depo_dropdown($depo['dpcode']);?>
										</div>
									</div>
								</div>	

								<h2>&nbsp;</h2>
								<div class="row">
									<div class="col-sm-8 col-sm-offset-4">

										<div class="row">
											<legend>Quantity : </legend>
										</div>
										<div class="row">
											<div class="col-sm-6">
												<label class="col-sm-offset-4"><b>STD</b></label>
												<div class="form-group">
													<label for="std20" class="col-sm-2 control-label text-right">20"</label>
													<div class="col-sm-8">
														<input type="text" name="std20" class="form-control" id="std20" value="<?=$std20?>" readonly>
													</div>
												</div>								
												<div class="form-group">
													<label for="std40" class="col-sm-2 control-label text-right">40"</label>
													<div class="col-sm-8">
														<input type="text" name="std40" class="form-control" id="std40" value="<?=$std40?>" readonly>
													</div>
												</div>												
											</div>

											<div class="col-sm-6">
												<label  class="col-sm-offset-4"><b>HC</b></label>
												<div class="form-group">
													<label for="hc20" class="col-sm-2 control-label text-right">20"</label>
													<div class="col-sm-8">
														<input type="text" name="hc20" class="form-control" id="hc20" value="<?=$hc20?>" readonly>
													</div>
												</div>								
												<div class="form-group">
													<label for="hc40" class="col-sm-2 control-label text-right">40"</label>
													<div class="col-sm-8">
														<input type="text" name="hc40" class="form-control" id="hc40" value="<?=$hc40?>" readonly>
													</div>
												</div>
												<div class="form-group">
													<label for="hc45" class="col-sm-2 control-label text-right">45"</label>
													<div class="col-sm-8">
														<input type="text" name="hc45" class="form-control" id="hc45" value="<?=$hc45?>" readonly>
													</div>
												</div>											
											</div>	

										</div>									
										
									</div>
								</div>

							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="cpiorderno" class="col-sm-4 control-label text-right">Pra Out No</label>
									<div class="col-sm-6">
										<input type="text" name="cpiorderno" class="form-control" id="cpiorderno" value="<?=$data['cpiorderno'];?>" readonly>
									</div>
								</div>								
								<div class="form-group">
									<label for="cpipratgl" class="col-sm-4 control-label text-right">Pra Out Date</label>
									<div class="col-sm-6">
										<div class="input-group">
											<input type="text" name="cpipratgl" id="cpipratgl" class="form-control" required readonly value="<?=$data['cpipratgl'];?>">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										</div>	
									</div>
								</div>								
								<div class="form-group">
									<label for="cpirefin" class="col-sm-4 control-label text-right">RO Number #</label>
									<div class="col-sm-6">
										<input type="text" name="cpirefin" class="form-control" id="cpirefin" value="<?=$data['cpirefin']?>" readonly="">
									</div>
								</div>								
								<div class="form-group">
									<label for="cpijam" class="col-sm-4 control-label text-right">Time Out</label>
									<div class="col-sm-4">
										<input type="text" name="cpijam" class="form-control" id="cpijam" required readonly value="<?=$data['cpijam'];?>">
									</div>
									<!-- <label class="col-sm-2 control-label">hh:mm:ss</label> -->
								</div>					
											
								<div class="form-group" style="display:none;">
									<label for="cpives" class="col-sm-4 control-label text-right">Vessel</label>
									<div class="col-sm-6">
										<?=vessel_dropdown($data['cpives']);?>
									</div>
								</div>															
								<div class="form-group" style="display:none;">
									<label for="cpivoyid" class="col-sm-4 control-label text-right">Voyage</label>
									<div class="col-sm-6">
										<!-- <input type="text" name="name" class="form-control" id="name"> -->
										<!-- <?=voyage_dropdown(); ?> -->
										<input type="text" id="cpivoyid" name="cpivoyid" class="form-control" value="<?=$data['cpivoyid']?>">
									</div>
								</div>								
								<div class="form-group" style="display:none;">
									<label for="vesopr" class="col-sm-4 control-label text-right">Vessel Operator</label>
									<div class="col-sm-6">
										<input type="text" name="vesopr" class="form-control" id="vesopr" value="<?=@$data['vessels']['vesopr'];?>" readonly>
									</div>
								</div>
								
								<div class="form-group">
									<label for="cpideliver" class="col-sm-4 control-label text-right">Receiver</label>
									<div class="col-sm-6">
										<input type="text" name="cpideliver" class="form-control" id="cpideliver" value="<?=$data['cpideliver']?>" readonly>
									</div>
								</div>
								<div class="form-group">
									<label for="cpideliver" class="col-sm-4 control-label text-right">File Upload</label>
									<div class="col-sm-6">
										<input type="file" name="files[]" class="form-control" id="files" multiple="true">
									</div>
								</div>
								<?php if($data['files']!=""):?>
								<div class="form-group">
									<label for="cpideliver" class="col-sm-4 control-label text-right">&nbsp;</label>
									<div class="col-sm-6 alert alert-info">
										<b>Files uploaded:</b><br>
										<?php $i=1; foreach($data['files'] as $f):?>
											<a href="<?=$f['url']; ?>" class="btn btn-default" target="_blank"><i class="fa fa-file"></i>&nbsp;File-<?=$i?></a>
										<?php $i++;
										endforeach;?>								
									</div>
								</div>	
								<?php endif;?>			
							</div>	
							<input type="hidden" name="appv1_update" class="form-control" id="appv1_update" value="<?=(isset($act)&&($act=='approval1')?'1':'0')?>" readonly="">
						</fieldset>
						<div class="form-footer">
							<div class="form-group text-center">
								<button type="sumbit" id="update" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update </button>&nbsp;
							</div>	
						</div>
					</form>
				</div>

			</div>
		</div>	

<!-- CONTAINERS -->

		<div class="row">
			<div class="col-sm-12">
				<div class="widget widget-table">
					<div class="widget-header">
						<h3><i class="fa fa-table"></i> List Order Pra Container</h3>
					</div>
					<div class="widget-content">
						<p><button class="btn btn-success" data-toggle="modal" data-target="#myModal" id="addContainer"><i class="fa fa-plus"></i>&nbsp;Add Container</button>
						</p>
						<br>
						<div class="table-responsive vscroll">
						<table id="detTable" class="table table-hover table-bordered" style="width:100%;">
							<thead>
								<tr>
									<th></th>
									<th>No.</th>
									<th>Container #</th>
									<th>ID Code</th>
									<th>Type</th>
									<th>Length</th>
									<th>Height</th>
									<th>Principal</th>
									<th>F/E</th>
									<th>Remark</th>
									<th>Seal No</th>
								</tr>
							</thead>
							
							<tbody id="listOrderPra">
								<?php if($data['orderPraContainers']==null): ?>
									<tr><td colspan="11"><p class="alert alert-warning">Data not found.</p></td></tr>
								<?php else: ?>

									<?php 
									$i=1; 
									foreach($orderPraContainers as $row): 
									?>
										<tr>
											<td>
												<a href="#" id="editContainer" class="btn btn-xs btn-info edit" data-crid="<?=$row['pracrnoid']?>" data-toggle="modal" data-target="#myModal">edit</a>
												<a href="#" id="deleteContainer" class="btn btn-xs btn-danger delete" data-crid="<?=$row['pracrnoid']?>" data-act="edit">delete</a>
											</td>
											<td><?=$i;?></td>
											<td><?=$row['crno'];?></td>
											<td><?=$row['cccode']?></td>
											<td><?=$row['ctcode']?></td>
											<td><?=$row['cclength']?></td>
											<td><?=$row['ccheight']?></td>
											<td><?=$row['cpopr']?></td>
											<td><?=((isset($row['cpife'])&&$row['cpife']==1)?'Full':'Empty')?></td>
											<td><?=$row['cpiremark']?></td>
											<td><?=$row['sealno']?></td>
										</tr>
									<?php 
									$i++; 					
									endforeach; 
									?>
								<?php endif; ?>
							</tbody>
						</table>
						</div>
					</div>

					<div class="widget-content text-center">
						<a href="<?=site_url('praout');?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Back</a>	
					</div>

				</div>
			</div>
		</div>	
		<div class="row">
			<div class="col-md-12">
			
			</div>
		</div>	
		
		<!-- END CONTAINER -->

	</div>
</div>

<?= $this->include('\Modules\PraOut\Views\form_edit_detail');?>

<?= $this->endSection();?>

<!-- JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\PraOut\Views\js'); ?>	
	
<?= $this->endSection();?>