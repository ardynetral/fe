<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>
<?php $data = $data; ?>
<div class="content">
	<div class="main-header">
		<h2>Pra In</h2>
		<em>Order Pra-In page</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table" id="OrderPra">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Edit Order Pra In</h3>
			</div>
			<div class="widget-content">
<!-- FORM HEADER -->
				<div class="row">
					<form id="fEditPraIn" class="form-horizontal" role="form" method="post">
						<?= csrf_field() ?>
						<input type="hidden" name="praid" id="praid" value="<?=$data['praid']?>">
						<fieldset>
							<div class="col-sm-6">
<?php /*								
								<?php if($group_id==4 || $group_id==3): ?>
								
								<div class="form-group">
									<label for="prcode" class="col-sm-5 control-label text-right">Principal</label>
									<div class="col-sm-7">
										<?php if($data['cpopr']=="0"):
											echo principal_dropdown($data['cpopr']); 
										else:?>
											<input type="text" name="prcode" class="form-control" id="prcode" value="<?=$data['cpopr'];?>" required>
										<?php endif; ?>
									</div>
								</div>
								<div class="form-group">
									<label for="cucode" class="col-sm-5 control-label text-right">Customer</label>
									<div class="col-sm-7">
										<input type="text" name="cucode" class="form-control" id="cucode" value="<?=$data['cpcust']?>" readOnly>
									</div>
								</div> 
								
								<?php endif; ?>	
*/ ?>
								<div class="form-group">
									<label for="cpidish " class="col-sm-5 control-label text-right">Origin Port</label>
									<div class="col-sm-4">
										<?=port_dropdown($data['cpidish']);?>
									</div>
									<label class="col-sm-3 control-label">* Port Country</label>
								</div>	
								<div class="form-group">
									<label for="cpidisdat" class="col-sm-5 control-label text-right">Discharge Date</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input type="text" name="cpidisdat" id="cpidisdat" class="form-control tanggal" value="<?=$data['cpidisdat']?>">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										</div>									
									</div>
								</div>
								<div class="form-group">
									<label for="liftoffcharge" class="col-sm-5 control-label text-right">Lift Off Charged in Depot</label>
									<div class="col-sm-7">
										<label class="control-inline fancy-checkbox custom-color-green">
											<input type="checkbox" name="liftoffcharge" id="liftoffcharge" value="<?=$data['liftoffcharge']?>" <?=(isset($data['liftoffcharge'])&&($data['liftoffcharge']==1)?'checked':'');?>>
											<span></span>
										</label>
									</div>
								</div>
								<div class="form-group">
									<label for="cpdepo" class="col-sm-5 control-label text-right">Depot</label>
									<div class="col-sm-7">
										<?=depo_dropdown($data['cpdepo']);?>
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
									<label for="cpiorderno" class="col-sm-4 control-label text-right">Pra In No</label>
									<div class="col-sm-6">
										<input type="text" name="cpiorderno" class="form-control" id="cpiorderno" value="<?=$data['cpiorderno'];?>" readonly>
									</div>
								</div>								
								<div class="form-group">
									<label for="cpipratgl" class="col-sm-4 control-label text-right">Pra In Date</label>
									<div class="col-sm-6">
										<div class="input-group">
											<input type="text" name="cpipratgl" id="cpipratgl" class="form-control" required readonly value="<?=$data['cpipratgl'];?>">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										</div>	
									</div>
								</div>								
								<div class="form-group">
									<label for="cpirefin" class="col-sm-4 control-label text-right">Reff In No #</label>
									<div class="col-sm-6">
										<input type="text" name="cpirefin" class="form-control" id="cpirefin" value="<?=$data['cpirefin']?>">
									</div>
								</div>								
								<div class="form-group">
									<label for="cpijam" class="col-sm-4 control-label text-right">Time In</label>
									<div class="col-sm-4">
										<input type="text" name="cpijam" class="form-control" id="cpijam" required readonly value="<?=$data['cpijam'];?>">
									</div>
									<!-- <label class="col-sm-2 control-label">hh:mm:ss</label> -->
								</div>								
								<div class="form-group">
									<label for="cpives" class="col-sm-4 control-label text-right">Vessel</label>
									<div class="col-sm-6">
										<?=vessel_dropdown($data['cpives']);?>
									</div>
								</div>															
								<div class="form-group">
									<label for="cpivoyid" class="col-sm-4 control-label text-right">Voyage</label>
									<div class="col-sm-6">
										<!-- <input type="text" name="name" class="form-control" id="name"> -->
										<!-- <?=voyage_dropdown(); ?> -->
										<input type="text" id="cpivoyid" name="cpivoyid" class="form-control" value="<?=$data['cpivoyid']?>">
									</div>
								</div>								
								<div class="form-group">
									<label for="cpopr" class="col-sm-4 control-label text-right">Vessel Operator</label>
									<div class="col-sm-6">
										<input type="text" name="cpopr" class="form-control" id="cpopr" readonly value="<?=$data['cpopr']?>">
									</div>
								</div>
								<div class="form-group">
									<label for="cpicargo" class="col-sm-4 control-label text-right">Ex Cargo</label>
									<div class="col-sm-6">
										<input type="text" name="cpicargo" class="form-control" id="cpicargo" value="<?=$data['cpicargo']?>">
									</div>
								</div>
								<div class="form-group">
									<label for="cpideliver" class="col-sm-4 control-label text-right">Redeliverer</label>
									<div class="col-sm-6">
										<input type="text" name="cpideliver" class="form-control" id="cpideliver" value="<?=$data['cpideliver']?>">
									</div>
								</div>
								<div class="form-group">
									<label for="cpideliver" class="col-sm-4 control-label text-right">File Upload</label>
									<div class="col-sm-6">
										<input type="file" name="files[]" class="form-control" id="files" multiple="true" accept="image/*">
									</div>
								</div>	
								<div class="form-group">
									<div class="col-sm-offset-4 col-sm-6">
										<div class="imgPreview alert alert-info">
										<b>File Uploaded : </b><br><br>
											<?php if($data['files']=="") :
												echo "No files found.";
											else: ?>
												
												<?php foreach($data['files'] as $key=>$val):?>
													<img src="<?=$data['files'][$key]['url'];?>" alt="" class="thumbnail" width="190">
												<?php endforeach; ?>
												
											<?php endif;?>

										</div>
									</div>			
								</div>								
							</div>	
						</fieldset>
						<div class="form-footer">
							<div class="form-group text-center">
								<button type="sumbit" id="update" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>&nbsp;
								<a href="<?=site_url('prain')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
							</div>	
						</div>
					</form>
				</div>

			</div>
		</div>	

<!-- CONTAINERS -->
		<div class="row">
			<div class="col-sm-4">
				<div class="widget widget-table">
					<div class="widget-header">
						<h3><i class="fa fa-table"></i> Form Input Container</h3>
					</div>
					<div class="widget-content">
						<?= $this->include('\Modules\PraIn\Views\form_detail_header');?>
					</div>
				</div>						
			</div>
			<div class="col-sm-8">
				<div class="widget widget-table">
					<div class="widget-header">
						<h3><i class="fa fa-table"></i> List Order Pra Container</h3>
					</div>
					<div class="widget-content">
						<table id="detTable" class="table table-hover table-bordered" style="width:100%;">
							<thead>
								<tr>
									<th>No.</th>
									<th>Container #</th>
									<th>ID Code</th>
									<th>Type</th>
									<th>Length</th>
									<th>Height</th>
									<th>F/E</th>
									<th>Hold/Release</th>
									<th>Remark</th>
									<th>GateIn Date</th>
									<th></th>
								</tr>
							</thead>
							
							<tbody id="listOrderPra">
								<?php if($data['orderPraContainers']==null): ?>
									<tr><td colspan="11"><p class="alert alert-warning">Data not found.</p></td></tr>
								<?php else: ?>

									<?php $i=1; foreach($data['orderPraContainers'] as $row): ?>
										<tr>
											<td><?=$i;?></td>
											<td><?=$row['crno'];?></td>
											<td><?=$row['cccode']?></td>
											<td><?=$row['ctcode']?></td>
											<td><?=$row['cclength']?></td>
											<td><?=$row['ccheight']?></td>
											<td><?=((isset($row['cpife'])&&$row['cpife']==1)?"Full":"Empty");?></td>
											<td><?=((isset($row['cpishold'])&&$row['cpishold']==1)?"Hold":"Release")?></td>
											<td><?=$row['cpiremark']?></td>
											<td></td>
											<td><a href="#" id="editContainer" class="btn btn-xs btn-primary edit" data-crid="<?=$row['pracrnoid']?>">edit</a></td>
										</tr>
									<?php $i++; endforeach; ?>
								<?php endif; ?>
							</tbody>
						</table>						
					</div>
				</div>
			</div>
		</div>			
		
	</div>
</div>

<?= $this->endSection();?>

<!-- JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\PraIn\Views\js'); ?>	
	
<?= $this->endSection();?>