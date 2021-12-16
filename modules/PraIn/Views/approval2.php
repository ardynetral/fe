<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Pra In</h2>
		<em>Approval 2</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table" id="OrderPra">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i>Approval</h3>
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
<?php /*								
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
*/?>								
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
<?php /*														
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
*/?>								
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
						<legend>Lift On : </legend>
					</div>
					<div class="col-sm-6">
<!-- 
						<table class="tbl-form" width="100%">
							<tbody>
								<tr>
									<td></td><td colspan="5"><b>STD</b></td>
								</tr>
								<tr>
									<td width="90" class="text-right">20"</td>
									<td><input type="text" name="std20" class="form-control" id="std20" value="<?=$std20?>" readonly></td>	
									<td>x</td>	
									<td><input type="text" name="lonstd20" class="form-control" value="<?=number_format($lon20,2);?>"></td>	
									<td>=</td>	
									<td><input type="text" name="lon20" class="form-control" value="<?=number_format($lon_std20,2);?>"></td>	
								</tr>
								<tr>
									<td class="text-right">40"</td>
									<td><input type="text" name="std40" class="form-control" id="std40" value="<?=$std40?>" readonly></td>
									<td>x</td>
									<td><input type="text" name="lonstd40" class="form-control" value="<?=number_format($lon40,2);?>"></td>
									<td>=</td>
									<td><input type="text" name="lon40" class="form-control" value="<?=number_format($lon_std40,2);?>"></td>
								</tr>	
								<tr>
									<td></td><td colspan="5"><b>HC</b></td>
								</tr>			
								<tr>
									<td class="text-right">20"</td>
									<td><input type="text" name="hc20" class="form-control" id="hc20" value="<?=$hc20?>" readonly></td>
									<td>x</td>
									<td><input type="text" name="lonhc20" class="form-control" value="<?=number_format($lon20,2);?>"></td>
									<td>=</td>
									<td><input type="text" name="totlonhc20" class="form-control" value="<?=number_format($lon_hc20,2);?>"></td>
								</tr>		
								<tr>
									<td class="text-right">40"</td>
									<td><input type="text" name="hc40" class="form-control" id="hc40" value="<?=$hc40?>" readonly></td>
									<td>x</td>
									<td><input type="text" name="lonhc40" class="form-control" value="<?=number_format($lon40,2);?>"></td>
									<td>=</td>
									<td><input type="text" name="lonhc40" class="form-control" value="<?=number_format($lon_hc40,2);?>"></td>
								</tr>		
								<tr>
									<td class="text-right">45"</td>
									<td><input type="text" name="hc45" class="form-control" id="hc45" value="<?=$hc45?>" readonly></td>
									<td>x</td>
									<td><input type="text" name="lonhc45" class="form-control" value="<?=number_format($lon45,2);?>"></td>
									<td>=</td>
									<td><input type="text" name="lon45" class="form-control" value="<?=number_format($lon_hc45,2);?>"></td>
								</tr>	
								<tr><td></td></tr>	
								<tr>
									<td></td>
									<td class="text-right" colspan="2">Cleaning</td>
									<td><label class="control-inline fancy-radio custom-color-green">
										<input type="radio" name="coadmm" id="coadmm" value="1" checked>
										<span><i></i><?=$coadmm;?></span></label></td>
									<td></td>
									<td></td>
								</tr>		
								<tr>
									<td></td>
									<td class="text-right" colspan="2">Pajak</td>
									<td colspan="2"><input type="text" name="" value="IDR" class="form-control" readonly></td>
									<td><input type="text" name="" value="<?=number_format($pajak,2);?>" class="form-control" readonly></td>
								</tr>		
								<tr>
									<td></td>
									<td class="text-right" colspan="2">Adm Tarif</td>
									<td colspan="2"><input type="text" name="" value="IDR" class="form-control" readonly></td>
									<td><input type="text" name="" value="<?=number_format($adm_tarif,2);?>" class="form-control" readonly></td>
								</tr>	
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td class="text-right" colspan="2">Materai</td>
									<td><input type="text" name="" value="" class="form-control" readonly></td>
								</tr>			
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<th class="text-right" colspan="2">Total Charge</th>
									<td><input type="text" name="" value="<?=number_format($totalcharge,2)?>" class="form-control" readonly></td>
								</tr>
							</tbody>
						</table>
		 -->
					</div>
					<div class="col-sm-6">	
						<!-- BUKTI BAYAR -->
						<div class="widget widget-table">
						
						<input type="hidden" name="praid" class="form-control" id="praid" value="<?=$data['praid']?>">

						<?php if(isset($data['files'])&&(array_search('1', array_column($data['files'], 'flag'))!==false)):?>
							
							<div class="widget-header">
								<h3><i class="fa fa-warning yellow-font"></i>Bukti Pembayaran</h3>
							</div>
							<div class="widget-content">
								<p>
									<?php foreach($data['files'] as $file):?>
									<img src="<?=$file['url']; ?>" style="width:200px;">
									<?php endforeach; ?>
								</p>
							</div>

						<?php else: ?>

							<div class="widget-header">
								<h3><i class="fa fa-warning yellow-font"></i>Upload Bukti Pembayaran</h3>
							</div>
							<div class="widget-content">	

								<div class="alert alert-danger text-center">
								<p class="lead">Segera lakukan Pembayaran sejumlah<br>
								<b>Rp. <?=number_format($totalcharge,2)?>.</b><br>
								ke Rekening : 1230985 (BANK MANDIRI)</p>
								</div>		

								<form class="form-horizontal" id="fBuktiBayar" enctype="multypart/form-data">
								<fieldset>
								<div class="form-group">
									<label for="cpideliver" class="col-sm-4 control-label text-right">Ref Transfer No</label>
									<div class="col-sm-6">
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

						<?php endif; ?>

						</div>									
					</div>
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
									<th>Remark</th>
									<th>GateIn Date</th>
								</tr>
							</thead>
							
							<tbody id="listOrderPra">
								<?php if($data['orderPraContainers']==""): ?>
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
											<td><?=$row['cpopr'];?></td>
											<td></td>
											<td></td>
											<td><?=$row['cpiremark']?></td>
											<td></td>
										</tr>
									<?php $i++; endforeach; ?>
								<?php endif; ?>
							</tbody>
						</table>						
					</div>
					<div class="widget-footer text-center">
					<button type="button" id="approval2" class="btn btn-success"><i class="fa fa-check"></i> Approve</button>
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