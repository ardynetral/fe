<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>
<?php $data = $data; ?>
<div class="content">
	<div class="main-header">
		<h2>Cetak Kitir</h2>
		<em>Pra Out</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table" id="OrderPra">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Cetak Kitir Order Pra Out</h3>
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
									<label for="cpidisdat" class="col-sm-5 control-label text-right">Loading Date</label>
									<div class="col-sm-7">
										<div class="input-group">
											<input type="text" name="cpidisdat" id="cpidisdat" class="form-control" value="<?=$data['cpidisdat']?>" readonly="">
											<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
										</div>									
									</div>
								</div>
								<div class="form-group" style="display:none;">
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
										<input type="text" id="" value="<?=$depo['dpname']?>" class="form-control" readonly="">
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
									<label for="cpideliver" class="col-sm-4 control-label text-right">Redeliverer</label>
									<div class="col-sm-6">
										<input type="text" name="cpideliver" class="form-control" id="cpideliver" value="<?=$data['cpideliver']?>" readonly="">
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
						</fieldset>					
					</form>
				</div>
				<div class="row">
					<div class="col-sm-6">
						<div class="widget widget-table">
						
							<input type="hidden" name="praid" class="form-control" id="praid" value="<?=$data['praid']?>">
							
							<div class="widget-header">
								<h3><i class="fa fa-info green-font"></i>Quantity</h3>
							</div>
							<div class="widget-content">						
								<div class="form-horizontal">
								<fieldset>
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
								</fieldset>
								</div>								
							</div>
						</div>
					</div>

					<div class="col-sm-6">	
						<!-- BUKTI BAYAR -->
										
						<div class="widget widget-table">
						
							<input type="hidden" name="praid" class="form-control" id="praid" value="<?=$data['praid']?>">
							
							<div class="widget-header">
								<h3><i class="fa fa-warning yellow-font"></i>Bukti Pembayaran</h3>
							</div>
							<div class="widget-content">

								<form class="form-horizontal">
								<fieldset>
								<div class="form-group">
									<label for="cpideliver" class="col-sm-4 control-label text-right">Ref Transfer No</label>
									<div class="col-sm-6">
										<input type="text" class="form-control" value="<?=$bukti_bayar['cpireceptno']?>"readonly>
									</div>
								</div>
								<div class="form-group">
									<label for="cpicurr" class="col-sm-2 col-sm-offset-2 control-label text-right">Currency</label>
									<div class="col-sm-2">
										<input type="text" class="form-control" value="IDR" readonly="">
									</div>

									<label for="cpirate" class="col-sm-2 control-label text-right">Rate</label>
									<div class="col-sm-2">
										<input type="text" class="form-control" value="1" readonly="">
									</div>
								</div>
								</fieldset>
								</form>	
								
								<p class="text-right"><b>Bukti Transfer</b><br>
									<?php foreach($bukti_bayar['files'] as $file):?>
									<a href="<?=$file['url']; ?>" target="_blank">
										<img src="<?=$file['url']; ?>" style="width:200px;">
									</a>
									<?php endforeach; ?>
								</p>

							</div>						

						</div>									
					</div>					
				</div>
			</div>

			<!-- CONTAINERS -->
			<div class="widget-content">
				<legend>List Container</legend>
				<div class="table-responsive vscroll">
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
							<th>Principal</th>
							<th>Lift Off</th>
							<th>Seal No</th>
							<th>Remark</th>
							<th>GateIn Date</th>
							<th></th>
						</tr>
					</thead>
					
					<tbody id="listOrderPra">
						<?php if($data['orderPraContainers']==""): ?>
							<tr><td colspan="11"><p class="alert alert-warning">Data not found.</p></td></tr>
						<?php else: ?>

							<?php 
							$i=1;
					 		$subtotal = 0;
							$total_lolo = 0;
							$total = 0;
							foreach($data['orderPraContainers'] as $row): 
								$subtotal = @$row['biaya_lolo'];
							?>
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
									<td><?=$row['cpopr']?></td>
									<td><?=number_format($row['biaya_lolo'],2);?></td>
									<td><?=$row['sealno'];?></td>									
									<td><?=$row['cpiremark']?></td>
									<td></td>
									<td>
										<a class="btn btn-xs btn-primary cetak_kitir" href="#" id=""
										data-praid="<?=$row['praid']; ?>"  
										data-crno="<?=$row['crno']; ?>" 
										data-cpiorderno="<?=$data['cpiorderno']?>">Cetak Kitir</a>
									</td>
								</tr>

							<?php 
							$i++;
							$total_lolo = $total_lolo+@$row['biaya_lolo'];
							$total = $total+$subtotal;							 
							endforeach; 
							?>

							<tr><th colspan="12" class="text-right">TOTAL LOLO</th><th class="text-right"><?=number_format($total,2)?></th></tr>							

						<?php endif; ?>
					</tbody>
				</table>
				</div>
				<table class="tbl-form" width="100%">
					<tbody>
						<?php
						if($total_lolo > 5000000) {
							$biaya_materai = $materai;
						} else {
							$biaya_materai = 0;
						}
						$total_pajak = $pajak*$total;
						$grand_total = $total+$total_pajak+$biaya_materai+$adm_tarif;
						?>	
						<tr>
							<td class="text-right" colspan="9">Pajak</td>
							<td width="200"><input type="text" name="" value="<?=number_format($total_pajak,2);?>" class="form-control" readonly></td>
						</tr>		
						<tr>
							<td class="text-right" colspan="9">Adm Tarif</td>
							<td width="200"><input type="text" name="" value="<?=number_format($adm_tarif,2);?>" class="form-control" readonly></td>
						</tr>	
						<tr>
							<td class="text-right" colspan="9">Materai</td>
							<td width="200"><input type="text" name="" value="<?=number_format($biaya_materai,2);?>" class="form-control" readonly></td>
						</tr>			
						<tr>
							<th class="text-right" colspan="9">Total Charge</th>
							<td width="200"><input type="text" name="" value="<?=number_format($grand_total,2);?>" class="form-control" readonly></td>
						</tr>
					</tbody>
				</table>										
			</div>	
			<div class="widget-footer text-center">
				<a href="#" class="btn btn-primary" id="proformaPrintInvoice1" data-praid="<?=$data['praid']?>"><i class="fa fa-print"></i> Cetak Kwitansi</a>
				<button type="button" id="cancel" class="btn btn-default cancel"><i class="fa fa-times-circle"></i> BACK</button>				
			</div>
		</div>	
		
	</div>
</div>

<?= $this->endSection();?>

<!-- JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\PraOut\Views\js'); ?>	
	
<?= $this->endSection();?>