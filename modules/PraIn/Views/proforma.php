<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Pra In</h2>
		<em>Proforma</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table" id="OrderPra">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i>Proforma</h3>
			</div>
			<div class="widget-content">
<!-- FORM HEADER -->
				<div class="row">
					<form id="form1" class="form-horizontal" role="form">
						<fieldset>
							<div class="col-sm-6">				
								<div class="form-group">
									<label for="cpidish " class="col-sm-5 control-label text-right">Origin Port</label>
									<div class="col-sm-4">
										<input type="text" name="cpidish" class="form-control" id="cpidish" value="<?=$data['cpidish']?>" readOnly>
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
											<input type="checkbox" name="liftoffcharge" id="liftoffcharge" value="<?=$data['liftoffcharge']?>" <?=(isset($data['liftoffcharge'])&&($data['liftoffcharge']==1)?'':'checked');?> readOnly>
											<span></span>
										</label>
									</div>
								</div>
								<div class="form-group">
									<label for="cpdepo" class="col-sm-5 control-label text-right">Depot</label>
									<div class="col-sm-7">
										<input type="text" name="cpdepo" class="form-control" id="cpdepo" value="<?=$data['cpdepo']?>" readOnly>
									</div>
								</div>	
							
								<h2>&nbsp;</h2>

							
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
										<input type="text" name="cpirefin" class="form-control" id="cpirefin" value="<?=$data['cpirefin']?>" readOnly>
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
										<input type="text" name="cpives" class="form-control" id="cpives" readonly value="<?=$data['cpives'];?>">
									</div>
								</div>															
								<div class="form-group">
									<label for="cpivoyid" class="col-sm-4 control-label text-right">Voyage</label>
									<div class="col-sm-6">
										<!-- <input type="text" name="name" class="form-control" id="name"> -->
										<!-- <?=voyage_dropdown(); ?> -->
										<input type="text" id="cpivoyid" name="cpivoyid" class="form-control" value="<?=$data['cpivoyid']?>" readonly>
									</div>
								</div>								
								<div class="form-group">
									<label for="cpopr" class="col-sm-4 control-label text-right">Vessel Operator</label>
									<div class="col-sm-6">
										<input type="text" name="cpopr" class="form-control" id="cpopr" readonly value="<?=$data['cpopr']?>" readonly>
									</div>
								</div>
								
								<div class="form-group">
									<label for="cpicargo" class="col-sm-4 control-label text-right">Ex Cargo</label>
									<div class="col-sm-6">
										<input type="text" name="cpicargo" class="form-control" id="cpicargo" value="<?=$data['cpicargo']?>" readonly>
									</div>
								</div>
								<div class="form-group">
									<label for="cpideliver" class="col-sm-4 control-label text-right">Redeliverer</label>
									<div class="col-sm-6">
										<input type="text" name="cpideliver" class="form-control" id="cpideliver" value="<?=$data['cpideliver']?>" readonly>
									</div>
								</div>
							</div>	

						</fieldset>
					</form>
				</div>

<?php if($coadmm=="By Container"):?>								

				<div class="row">
					<div class="col-sm-12">
						<legend></legend>
					</div>
					
					<?php 
						if(isset($data['files'])&&(array_search('1', array_column($data['files'], 'flag'))!==false)) {
							echo array_search('1', array_column($data['files'], 'flag'));
						
					} else { ?>

					<div class="col-sm-6">
						<div class="alert alert-danger text-center">
							<p class="lead">Segera lakukan Pembayaran sejumlah<br>
							<b>Rp. <?=number_format($totalcharge,2)?>.</b><br>
							ke Rekening : 1230985 (BANK MANDIRI)</p>
						</div>		
					</div>
					<div class="col-sm-6">

						<!-- BUKTI BAYAR -->
						<div class="widget widget-table">
							<div class="widget-header">
								<h3><i class="fa fa-warning yellow-font"></i>Upload Bukti Pembayaran</h3>
							</div>
							<div class="widget-content">							
								<form class="form-horizontal" id="fBuktiBayar" enctype="multypart/form-data">
								<fieldset>
								<div class="form-group">
									<label for="cpideliver" class="col-sm-4 control-label text-right">Ref Transfer No</label>
									<div class="col-sm-6">
										<input type="hidden" name="praid" class="form-control" id="praid" value="<?=$data['praid']?>">
										<input type="text" name="cpireceptno" class="form-control" id="cpireceptno" required="">
									</div>
								</div>
								<div class="form-group">
									<label for="cpicurr" class="col-sm-2 col-sm-offset-2 control-label text-right">Currency</label>
									<div class="col-sm-2">
										<input type="text" name="cpicurr" class="form-control" id="cpicurr" value="IDR" readonly="">
									</div>

									<label for="cpirate" class="col-sm-2 control-label text-right">Rate</label>
									<div class="col-sm-2">
										<input type="text" name="cpirate" class="form-control" id="cpirate" value="1" readonly="">
									</div>
								</div>
								<div class="form-group">
									<label for="cpideliver" class="col-sm-4 control-label text-right">Bukti Transfer</label>
									<div class="col-sm-6">
										<input type="file" name="files" class="form-control" id="files" accept="image/*">
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-4 col-sm-6">
										<button type="submit" id="saveBuktiBayar" class="btn btn-primary"><i class="fa fa-save"></i> Submit</button>&nbsp;
									</div>
								</div>
								</fieldset>
								</form>	
							</div>
						</div>									
					</div>

					<?php } ?>

				</div>

<?php endif; ?>						

			</div>
		</div>	

<!-- CONTAINERS -->
		<div class="row">
			<div class="col-sm-12">
				<div class="widget widget-table">
					<div class="widget-header">
						<h3><i class="fa fa-table"></i> Order Pra Container</h3>
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
									<th>Principal</th>
									<th>Lift Off</th>
									<th>Deposit</th>
									<th>Subtotal</th>
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
										$subtotal = @$row['biaya_lolo']+@$row['biaya_clean'];
									?>
										<tr>
											<td><?=$i;?></td>
											<td><?=$row['crno'];?></td>
											<td><?=$row['cccode']?></td>
											<td><?=$row['ctcode']?></td>
											<td><?=$row['cclength']?></td>
											<td><?=$row['ccheight']?></td>
											<td><?=$row['cpopr'];?></td>
											<td><?=@$row['biaya_lolo'];?></td>
											<td><?=@$row['biaya_clean'];?></td>											
											<td class="text-right"><?=$subtotal;?></td>
										</tr>
									
									<?php 
									
									$i++; 
									$total_lolo = $total_lolo+@$row['biaya_lolo'];
									$total = $total+$subtotal;
									
									endforeach; 


									?>

									<tr><th colspan="9" class="text-right">TOTAL</th><th><?=$total?></th></tr>

								<?php endif; ?>
							</tbody>
						</table>
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
									<td width="200"><input type="text" name="" value="<?=$total_pajak;?>" class="form-control" readonly></td>
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
					<a href="#" class="btn btn-primary" id="proformaPrintOrder" data-praid="<?=$data['praid']?>"><i class="fa fa-print"></i> Cetak</a>
					<a href="<?=site_url('prain')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Kembali</a>
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