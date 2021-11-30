<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>
<?php $data = $data; ?>
<div class="content">
	<div class="main-header">
		<h2>Approval-2</h2>
		<em>Pra In</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table" id="OrderPra">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Approval-2 Order Pra In</h3>
			</div>
			<div class="widget-content">
<!-- FORM HEADER -->
				<div class="row">
					<form id="form1" class="form-horizontal" role="form">
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
									<label for="cpdepo" class="col-sm-5 control-label text-right">Depot</label>
									<div class="col-sm-7">
										<input type="text" id="cpdepo" value="<?=$data['cpdepo']?>" class="form-control" readonly="">
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
										<input type="text" id="cpives" name="cpives" class="form-control" value="<?=$data['cpives']?>" readonly="">
									</div>
								</div>															
								<div class="form-group">
									<label for="cpivoyid" class="col-sm-4 control-label text-right">Voyage</label>
									<div class="col-sm-6">
										<input type="text" id="cpivoyid" name="cpivoyid" class="form-control" value="<?=$data['cpivoyid']?>" readonly="">
										<input type="hidden" id="cpivoyno" name="cpivoyno" value="<?=$data['voyages']['voyno']?>" readonly="">
									</div>
								</div>								
								<div class="form-group">
									<label for="vesopr" class="col-sm-4 control-label text-right">Vessel Operator</label>
									<div class="col-sm-6">
										<input type="text" name="vesopr" class="form-control" id="vesopr" value="<?=$data['vessels']['vesopr']?>" readonly="">
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
							</div>	
						</fieldset>					
					</form>
				</div>

			</div>

			<!-- CONTAINERS -->
			<div class="widget-content">
				<legend>List Container</legend>
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
						<?php if($data['orderPraContainers']==""): ?>
							<tr><td colspan="11"><p class="alert alert-warning">Data not found.</p></td></tr>
						<?php else: ?>

							<?php $i=1; foreach($data['orderPraContainers'] as $row): ?>
								<tr>
									<td><?=$i;?>
										<input type="hidden" name="cpopr" id="cpopr" value="<?=@$row['cpopr']?>">
										<input type="hidden" name="cpcust" id="cpcust" value="<?=@$row['cpcust']?>">			
									</td>
									<td><?=$row['crno'];?></td>
									<td><?=$row['cccode']?></td>
									<td><?=$row['ctcode']?></td>
									<td><?=$row['cclength']?></td>
									<td><?=$row['ccheight']?></td>
									<td><?=((isset($row['cpife'])&&$row['cpife']==1)?"Full":"Empty");?>
										<input type="hidden" name="cpife" id="cpife" value="<?=$row['cpife'];?>">
									</td>
									<td><?=((isset($row['cpishold'])&&$row['cpishold']==1)?"Hold":"Release")?>
										<input type="hidden" name="cpishold" id="cpishold" value="<?=$row['cpishold'];?>">
									</td>
									<td><?=$row['cpiremark']?></td>
									<td><a class="btn btn-xs btn-primary" href="#" id="cetak_kitir" data-crno="<?=$row['crno']; ?>">Cetak Kitir</a></td>
								</tr>
							<?php $i++; endforeach; ?>
						<?php endif; ?>
					</tbody>
				</table>						
			</div>	
			<div class="widget-footer text-center">
				<button type="button" id="cancel" class="btn btn-default cancel"><i class="fa fa-times-circle"></i> Cancel</button>				
			</div>
		</div>	
		
	</div>
</div>

<?= $this->endSection();?>

<!-- JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\PraIn\Views\js'); ?>	
	
<?= $this->endSection();?>