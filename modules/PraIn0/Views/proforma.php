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
									<label for="prcode" class="col-sm-5 control-label text-right">Principal</label>
									<div class="col-sm-7">
										<input type="text" name="prcode" class="form-control" id="prcode" value="<?=$data['cpopr'];?>" readOnly>
									</div>
								</div>
								<div class="form-group">
									<label for="cucode" class="col-sm-5 control-label text-right">Customer</label>
									<div class="col-sm-7">
										<input type="text" name="cucode" class="form-control" id="cucode" value="<?=$data['cpcust']?>" readOnly>
									</div>
								</div> 

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

<?php if($coadmm=="By Container"):?>								

								<div class="row">
									<div class="col-sm-12 col-sm-offset-1">
										<div class="row">
											<legend>Lift On : </legend>
										</div>
										<div class="row">
											<div class="">

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
			<th class="text-right" colspan="2">Total Charge</th>
			<td><input type="text" name="" value="<?=number_format($totalcharge,2)?>" class="form-control" readonly></td>
		</tr>
		<tr>
			<td colspan="6">

			</td>
		</tr>		
		<tr>
			<th colspan="6">
				<div class="alert alert-warning text-center">
					<p class="lead">Segera lakukan Pembayaran sejumlah<br>
					<b>Rp. <?=number_format($totalcharge,2)?>.</b><br>
					ke Rekening : 1230985 (BANK MANDIRI)</p>
				</div>
			</th>
		</tr>
	</tbody>
</table>
																			
											</div>	

										</div>									
									</div>
								</div>
<?php endif; ?>								
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
									<th>F/E</th>
									<th>Hold/Release</th>
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
											<td><?=((isset($row['cpife'])&&$row['cpife']==1)?"Full":"Empty");?></td>
											<td><?=((isset($row['cpishold'])&&$row['cpishold']==1)?"Hold":"Release")?></td>
											<td><?=$row['cpiremark']?></td>
											<td></td>
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