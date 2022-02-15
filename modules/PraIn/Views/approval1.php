<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>
<?php $data = $data; ?>
<div class="content">
	<div class="main-header">
		<h2>Approval1</h2>
		<em>Pra In</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table" id="OrderPra">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Approval1 Order Pra In</h3>
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
									<label for="cpidish " class="col-sm-5 control-label text-right">Origin Port</label>
									<div class="col-sm-4">
										<input type="text" name="cpidish" id="cpidish" class="form-control" value="<?=$data['cpidish']?>" readonly="">
									</div>
									<label class="col-sm-3 control-label">* Port Country</label>
								</div>	
								<div class="form-group">
									<label for="cpidisdat" class="col-sm-5 control-label text-right">Discharge Date</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input type="text" name="cpidisdat" id="cpidisdat" class="form-control" value="<?=$data['cpidisdat']?>" readonly="">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										</div>									
									</div>
								</div>
								
								<div class="form-group">
									<label for="liftoffcharge" class="col-sm-5 control-label text-right">Lift Off Charged in Depot</label>
									<div class="col-sm-7">
										<label class="control-inline fancy-checkbox custom-color-green">
											<input type="checkbox" name="liftoffcharge" id="liftoffcharge" value="<?=$data['liftoffcharge']?>" <?=(isset($data['liftoffcharge'])&&($data['liftoffcharge']==1)?'':'checked');?> readonly="">
											<span></span>
										</label>
									</div>
								</div>

								<div class="form-group">
									<label for="Type" class="col-sm-5 control-label text-right">Type</label>
									<div class="col-sm-7">
										<label class="control-inline fancy-checkbox custom-color-green">
											<input type="radio" name="typedo" id="" value="1" <?=(isset($data['type_do'])&&($data['type_do']==1)?'checked':'');?> required>
											<span>Free Use</span>
										</label>										
										<label class="control-inline fancy-checkbox custom-color-green">
											<input type="radio" name="typedo" id="" value="2" <?=(isset($data['type_do'])&&($data['type_do']==2)?'checked':'');?> required>
											<span>COC</span>
										</label>
										<label class="control-inline fancy-checkbox custom-color-green">
											<input type="radio" name="typedo" id="" value="3" <?=(isset($data['type_do'])&&($data['type_do']==3)?'checked':'');?> required>
											<span>SOC</span>
										</label>											
										<label class="control-inline fancy-checkbox custom-color-green">
											<input type="radio" name="typedo" id="" value="4" <?=(isset($data['type_do'])&&($data['type_do']==4)?'checked':'');?> required>
											<span>ex Import</span>
										</label>

									</div>
								</div>									

								<div class="form-group">
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
									<label for="cpirefin" class="col-sm-4 control-label text-right">DO Number #</label>
									<div class="col-sm-6">
										<input type="text" name="cpirefin" class="form-control" id="cpirefin" value="<?=$data['cpirefin']?>" readonly="">
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
									<label for="vesopr" class="col-sm-4 control-label text-right">Vessel Operator</label>
									<div class="col-sm-6">
										<input type="text" name="vesopr" class="form-control" id="vesopr" value="<?=@$data['vessels']['vesopr'];?>" readonly>
										<input type="hidden" name="vesprcode" class="form-control" id="vesprcode" value="<?=@$data['vessels']['prcode'];?>" readonly>
									</div>
								</div>
								
								<div class="form-group">
									<label for="cpicargo" class="col-sm-4 control-label text-right">Ex Cargo</label>
									<div class="col-sm-6">
										<input type="text" name="cpicargo" class="form-control" id="cpicargo" value="<?=$data['cpicargo']?>" readonly="">
									</div>
								</div>
								<div class="form-group">
									<label for="cpideliver" class="col-sm-4 control-label text-right">Redeliverer</label>
									<div class="col-sm-6">
										<input type="text" name="cpideliver" class="form-control" id="cpideliver" value="<?=$data['cpideliver']?>" readonly="">
									</div>
								</div>
								<div class="form-group" style="display:none;">
									<label for="cpideliver" class="col-sm-4 control-label text-right">File Upload</label>
									<div class="col-sm-6">
										<input type="file" name="files[]" class="form-control" id="files" multiple="true">
									</div>
								</div>								
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
<?php if($data['orderPraContainers']==null): ?>

	<div class="row">
		<div class="col-sm-12">
			<div class="widget widget-table">
				<div class="widget-content">
				<p class="alert alert-warning text-center">
					Data Container masih kosong!
				</p>
				</div>
			</div>
		</div>
	</div>

<?php else: ?>	

		<div class="row">
			<div class="col-sm-4">
				<div class="widget widget-table">
					<div class="widget-header">
						<h3><i class="fa fa-table"></i> Form Input Container</h3>
					</div>
					<div class="widget-content">

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
								</div>
							</div>						
						</fieldset>
					</form>	

					</div>
				</div>						
			</div>
			<div class="col-sm-8">
				<div class="widget widget-table">
					<div class="widget-header">
						<h3><i class="fa fa-table"></i> List Order Pra Container</h3>
					</div>
					<div class="widget-content">
						<div class="table-responsive">
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
									<th>Lift Off</th>
									<th>Deposit</th>
									<th>Cleaning</th>
									<th>Remark</th>
								</tr>
							</thead>
							
							<tbody id="listOrderPra">
								<?php if($data['orderPraContainers']==null): ?>
									<tr><td colspan="11"><p class="alert alert-warning">Data not found.</p></td></tr>
								<?php else: ?>

									<?php 
									$i=1; 
									$subtotal = 0;
									$total_lolo = 0;
									$total_cleaning = 0;
									$total_biaya_lain = 0;
									$total_pph23 = 0;
									$total = 0;
									// echo var_dump($orderPraContainers);die();
									foreach($orderPraContainers as $row): 
									?>
										<tr>
											<td><a href="#" id="editContainer" class="btn btn-xs btn-info edit" data-crid="<?=$row['pracrnoid']?>">view</a></td>
											<td><?=$i;?></td>
											<td><?=$row['crno'];?></td>
											<td><?=$row['cccode']?></td>
											<td><?=$row['ctcode']?></td>
											<td><?=$row['cclength']?></td>
											<td><?=$row['ccheight']?></td>
											<td><?=$row['cpopr'];?></td>
											<td><?=$row['biaya_lolo'];?></td>
											<td><?=$row['biaya_clean'];?></td>
											<td><?=$row['biaya_lain'];?></td>
											<td><?=$row['cpiremark']?></td>
										</tr>
									<?php 
									$i++; 
									$total_lolo = $total_lolo+$row['biaya_lolo'];
									$total_cleaning = $total_cleaning+$row['biaya_clean'];
									$total_biaya_lain = $total_biaya_lain+$row['biaya_lain'];
									$total_pph23 = $total_pph23+$row['pph23'];
									endforeach; 
									$subtotal = $total_lolo+$total_biaya_lain+$total_cleaning;
									?>
								<?php endif; ?>

								<input type="hidden" name="total_lolo" id="total_lolo" value="<?=$total_lolo?>">
								<input type="hidden" name="total_cleaning" id="total_cleaning" value="<?=$total_cleaning?>">
								<input type="hidden" name="total_biaya_lain" id="total_biaya_lain" value="<?=$total_biaya_lain?>">
								<input type="hidden" name="total_pph23" id="total_pph23" value="<?=$total_pph23?>">
								<input type="hidden" name="subtotal_bill" id="subtotal_bill" value="<?=$subtotal?>">
								
							</tbody>
						</table>
						</div>

					</div>

					<div class="widget-content text-center">
						<?php if((isset($act)&&$act=="approval1")&&($data['orderPraContainers']!=null)):?>

							<button type="button" id="ApprovalOrder" class="btn btn-danger"><i class="fa fa-check-circle"></i> Approve Order</button>

						<?php endif; ?>
						<a href="<?=site_url('prain');?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Back</a>	
					</div>

				</div>
			</div>
		</div>	
		<div class="row">
			<div class="col-md-12">
			
			</div>
		</div>	

<?php endif; ?>		
		
		<!-- END CONTAINER -->

	</div>
</div>

<?= $this->endSection();?>

<!-- JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\PraIn\Views\js'); ?>	
	
<?= $this->endSection();?>