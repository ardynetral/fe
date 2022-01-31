<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<style type="text/css">
	.hideBlock{
		display: none;
	}
	.showBlock{
		display: block;
	}
</style>

<div class="content">
	<div class="main-header">
		<h2>Repo Pra Out</h2>
		<em>Order Repo Pra Out</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table" id="OrderPra">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Form Order Repo</h3>
			</div>
			<div class="widget-content">
				<div class="row">
					<form id="fUpdateOrderRepo" class="form-horizontal" role="form" method="post">
						<?= csrf_field() ?>
						<fieldset>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label for="cpopr " class="col-sm-5 control-label text-right">Principal</label>
										<div class="col-sm-7">
											<input type="text" name="cpcust" id="cpcust" class="form-control" value="<?=$data['cpopr']?>" readonly>
										</div>
									</div>
									<div class="form-group">
										<label for="cpopr " class="col-sm-5 control-label text-right">Customer</label>
										<div class="col-sm-4">
											<input type="text" name="cpcust" id="cpcust" class="form-control" value="<?=$data['cpcust']?>" readonly>
										</div>
									</div>												
									<div class="form-group">
										<label for="cucode" class="col-sm-5 control-label text-right">Repo Type</label>
										<div class="col-sm-7">

											<?php if($data['retype']=='11') {
												$retype = "DEPOT to DEPOT";
											} else if($data['retype']=='12') {
												$retype = "DEPOT to PORT";
											} else if($data['retype']=='13') {
												$retype = "DEPOT to INTERCITY";
											} else {
												$retype = "";
											}
											?>
											<input type="text" name="cpcust" id="cpcust" class="form-control" value="<?=$retype?>" readonly>
										</div>
									</div>
									<div class="form-group">
										<label  class="col-sm-5 control-label text-right">From</label>
										<div id="fromDepoBlok" class="col-sm-7 <?=(isset($data['retype'])&&($data['retype']=='11')?'':'hideBlock')?>">
											<?=depo_dropdown2("retfrom",$data['retfrom'])?>
										</div>
										<div id="fromPortBlok" class="col-sm-7 <?=(isset($data['retype'])&&($data['retype']=='12')?'':'hideBlock')?>">
											<?=port_dropdown("retfrom",$data['retfrom'])?>
										</div>						
										<div id="fromCityBlok" class="col-sm-7 <?=(isset($data['retype'])&&($data['retype']=='13')?'':'hideBlock')?>">
											<?=city_dropdown("retfrom",$data['retfrom'])?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label text-right">To</label>
										<div id="toDepoBlok" class="col-sm-7">
											<?=depo_dropdown2("retto",$data['retto'])?>
										</div>
										<div id="toPortBlok" class="col-sm-7 hideBlock">
											<?=port_dropdown("retto",$data['retto'])?>
										</div>						
										<div id="toCityBlok" class="col-sm-7 hideBlock">
											<?=city_dropdown("retto",$data['retto'])?>
										</div>
									</div>

									<div class="form-group" style="display:none;">
										<label for="repodish " class="col-sm-5 control-label text-right">Origin Port</label>
										<div class="col-sm-4">
											<?=port_dropdown("repodish","");?>
										</div>
										<label class="col-sm-3 control-label">* Port Country</label>
									</div>	
									<div class="form-group">
										<label for="repodisdat" class="col-sm-5 control-label text-right">Discharge Date</label>
										<div class="col-sm-7">
											<div class="input-group">
												<input type="text" name="repodisdat" id="repodisdat" class="form-control tanggal">
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											</div>									
										</div>
									</div>
									<div class="form-group">
										<label for="readdr" class="col-sm-5 control-label text-right">Address</label>
										<div class="col-sm-7">
											<textarea class="form-control" style="resize: none;" rows="3" cols="32" name="readdr" id="readdr"><?=$data['readdr']?></textarea>
										</div>
									</div>
									<div class="form-group">
										<label for="recity" class="col-sm-5 control-label text-right">City</label>
										<div class="col-sm-7">
											<?=city_dropdown("recity",$data['recity'])?>
										</div>
									</div>
									<div class="form-group">
										<label for="redline" class="col-sm-5 control-label text-right">Deadline</label>
										<div class="col-sm-7">
											<div class="input-group">
												<input type="text" name="redline" id="redline" class="form-control tanggal" value="<?=date('d-m-Y',strtotime($data['redline']))?>" required>
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											</div>									
										</div>
									</div>
									<div class="form-group" style="display: none;">
										<label for="liftoffcharge" class="col-sm-5 control-label text-right">Lift Off Charged in Depot</label>
										<div class="col-sm-7">
											<label class="control-inline fancy-checkbox custom-color-green">
												<input type="checkbox" name="liftoffcharge" id="liftoffcharge" value="1" checked>
												<span></span>
											</label>
										</div>
									</div>					
									<div class="form-group">
										<label for="repoves" class="col-sm-5 control-label text-right">Ex Vessel</label>
										<div class="col-sm-7">
											<?=repoves_dropdown($data['recpives']);?>
										</div>
									</div>		
									<div class="form-group">
										<label for="repovoyid" class="col-sm-5 control-label text-right">Voyage</label>
										<div class="col-sm-7">
											<input type="text" class="form-control" id="voyid" name="voyid" value="<?=$data['recpivoyid']?>">
											<input type="hidden" class="form-control" id="voyno" name="voyno" value="0">
										</div>
									</div>		
									<div class="form-group" style="display:none;">
										<label for="cpdepo" class="col-sm-5 control-label text-right">Depot</label>
										<div class="col-sm-7">
											<?=depo_dropdown("000");?>
										</div>
									</div>	
									<div class="form-group" style="display:none">
										<label for="cpdepo" class="col-sm-5 control-label text-right">Sub Depot</label>
										<div class="col-sm-7">
											<?=depo_dropdown("000");?>
										</div>
									</div>	
								</div>

								<div class="col-sm-6">
									<div class="form-group">
										<label for="reorderno" class="col-sm-4 control-label text-right">Repo Pra Out No</label>
										<div class="col-sm-6">
											<input type="text" name="reorderno" class="form-control" id="reorderno" value="<?=$data['reorderno'];?>" readonly>
										</div>
									</div>								
									<div class="form-group">
										<label for="repopratgl" class="col-sm-4 control-label text-right">Repo Pra Out  Date</label>
										<div class="col-sm-6">
											<div class="input-group">
												<input type="text" name="redate" id="redate" class="form-control" value="<?=date('d-m-Y',strtotime($data['redate']));?>" readonly>
												<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
											</div>	
										</div>
									</div>														
									<div class="form-group">
										<label for="repojam" class="col-sm-4 control-label text-right">Time In</label>
										<div class="col-sm-4">
											<input type="text" name="repojam" class="form-control" id="repojam" value="<?=date('H:i:s');?>" readonly>
										</div>
										<label class="col-sm-2 control-label">hh:mm:ss</label>
									</div>
									<div class="form-group">
										<label for="reautno" class="col-sm-4 control-label text-right">Authorized No</label>
										<div class="col-sm-6">
											<input type="text" name="reautno" class="form-control" id="reautno" value="<?=$data['reautno']?>" required>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label text-right">Repo Vendor</label>
										<div class="col-sm-6">
											<input type="text" name="cpicargo" class="form-control" id="cpicargo">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label text-right">Billing Type</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" value="<?=$data['rebill']?>" readonly>
										</div>
									</div>

									<div id="breakDown" <?=((isset($data['rebill'])&&($data['rebill']=='Breakdown')?'style="display:block;"':'style="display:none"'))?>>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Lift On Adm</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>		
											<div class="col-sm-4">
												<input type="text" name="relift" class="form-control" id="relift" value="0" required readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Doc Fee</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="redoc" class="form-control" id="redoc" value="0" required readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Haulage 20"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="re20" class="form-control" id="re20" value="<?=$data['re20']?>" required readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Total Haulage 20"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="retot20" class="form-control" id="retot20" value="<?=$data['retot20']?>" required readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Haulage 40"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>
											<div class="col-sm-4">
												<input type="text" name="re40" class="form-control" id="re40" value="<?=$data['re40']?>" required readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Total Haulage 40"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="retot40" class="form-control" id="retot40" value="<?=$data['retot40']?>" required readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Haulage 45"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="re45" class="form-control" id="re45" value="<?=$data['re45']?>" required readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Total Haulage 45"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="retot45" class="form-control" id="retot45" value="<?=$data['retot45']?>" required readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right" style="color:blue;">Sub Total 1</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="SUBTOT" readonly>
											</div>
											<div class="col-sm-4">
												<input type="text" name="subtotbreak" class="form-control" id="subtotbreak" value="<?=$data['subtotbreak']?>" require readonly>
											</div>
										</div>				
									</div>
									<div id="Package" <?=((isset($data['rebill'])&&($data['rebill']=='Package')?'style="display:block;"':'style="display:none"'))?>>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">20"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="recpack20" class="form-control" id="recpack20" value="<?=$data['recpack20']?>" required>
												<input type="hidden" name="rechaul20" class="form-control" id="rechaul20" value="<?=$data['rechaul20']?>" required>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Total 20"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>
											<div class="col-sm-4">
												<input type="text" name="recpacktot20" class="form-control" id="recpacktot20" value="<?=$data['recpacktot20']?>" required>
												<input type="hidden" name="rechaultot20" class="form-control" id="rechaultot20" value="<?=$data['rechaultot20']?>" required>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">40"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="recpack40" class="form-control" id="recpack40" value="<?=$data['recpack40']?>">
												<input type="hidden" name="rechaul40" class="form-control" id="rechaul40" value="<?=$data['rechaul40']?>">
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Total 40"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>			
											<div class="col-sm-4">
												<input type="text" name="recpacktot40" class="form-control" id="recpacktot40" value="<?=$data['recpacktot40']?>" >
												<input type="hidden" name="rechaultot40" class="form-control" id="rechaultot40" value="<?=$data['rechaultot40']?>" >
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">45"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>			
											<div class="col-sm-4">
												<input type="text" name="recpack45" class="form-control" id="recpack45" value="<?=$data['recpack45']?>">
												<input type="hidden" name="rechaul45" class="form-control" id="rechaul45" value="<?=$data['rechaul45']?>">
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Total45"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="recpacktot45" class="form-control" id="recpacktot45" value="<?=$data['recpacktot45']?>" >
												<input type="hidden" name="rechaultot45" class="form-control" id="rechaultot45" value="<?=$data['rechaultot45']?>" >
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right" style="color:blue;">Sub Total 1</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="SUBTOT" readonly>
											</div>'		
											<div class="col-sm-4">
												<input type="text" name="subtotpack" class="form-control" id="subtotpack" value="<?=number_format($data['subtotpack'],2)?>" readonly >
											</div>
										</div>						
									</div>

								</div>	
							</div>
							<div class="row" style="margin-top: 25px;">
								<div class="col-sm-12">
									<div class="col-sm-6">
																	
									</div>
									<div class="col-sm-6">
										
										<legend><center>Additional Charges </center></legend>
										<div class="row">
											
											<div class="form-group" <?=((isset($data['rebill'])&&($data['rebill']=='Breakdown')?'style="display:block;"':'style="display:none"'))?>>
												<label class="col-sm-4 control-label text-right" style="color:blue;">Sub Total 2</label>
												<div class="col-sm-2">
													<input type="text" name="" class="form-control" id="" value="IDR" readonly>
												</div>								
												<div class="col-sm-4">
													<input type="text" name="reother1" class="form-control" id="reother1" value="<?=number_format($data['reother1'],2)?>" required>
												</div>
											</div>
																					
											<div class="form-group" <?=((isset($data['rebill'])&&($data['rebill']=='Package')?'style="display:block;"':'style="display:none"'))?>>
												<label class="col-sm-4 control-label text-right" style="color:blue;">Sub Total 2</label>
												<div class="col-sm-2">
													<input type="text" name="" class="form-control" id="" value="IDR" readonly>
												</div>								
												<div class="col-sm-4">
													<input type="text" name="reother2" class="form-control" id="reother2"  value="<?=number_format($data['reother2'],2)?>" required>
												</div>
											</div>	

											<div class="form-group">
												<label for="liftoffcharge" class="col-sm-5 control-label text-right">Will Be Charge</label>
												<div class="col-sm-7">
													<label class="control-inline fancy-checkbox custom-color-green">
														<input type="checkbox" name="liftoffcharge" id="liftoffcharge" value="0">
														<span></span>
													</label>
												</div>
											</div>
											<div class="form-group">
												<label for="liftoffcharge" class="col-sm-5 control-label text-right">By Depot</label>
												<div class="col-sm-7">
													<label class="control-inline fancy-checkbox custom-color-green">
														<input type="checkbox" name="liftoffcharge" id="liftoffcharge" value="0">
														<span></span>
													</label>
												</div>
											</div>	
											<div class="form-group">
												<label class="col-sm-4 control-label text-right" style="color:blue;">Total Charge</label>
												<div class="col-sm-2">
													<input type="text" name="" class="form-control" id="" value="IDR" readonly>
												</div>								
												<div class="col-sm-4" >
													<input type="text" name="totbreak" class="form-control" id="totbreak" value="<?=number_format($data['totbreak'],2)?>" required readonly <?=((isset($data['rebill'])&&($data['rebill']=='Breakdown')?'style="display:block;"':'style="display:none"'))?>>
													<input type="text" name="totpack" class="form-control" id="totpack" value="<?=number_format($data['totpack'],2)?>" required readonly <?=((isset($data['rebill'])&&($data['rebill']=='Package')?'style="display:block;"':'style="display:none"'))?>>
												</div>
											</div>																						
										</div>									
									</div>
								</div>
							</div>

						<div class="row">
						</div>
						</fieldset>


<!-- 						<div class="form-footer">
							<div class="form-group text-center">
								<button type="submit" id="updateData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>&nbsp;
								
							</div>	
						</div> -->
					</form>
				</div>

			</div>
		</div>	

		<div class="row">
			<div class="col-sm-12">
				<div class="widget widget-table">
					<div class="widget-header">
						<h3><i class="fa fa-table"></i> Repo Container</h3>
					</div>
					<div class="widget-content">
						<table id="rcTable" class="table table-hover table-bordered" style="width:100%;">
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
								</tr>
							</thead>
							<?php if($containers!=""):?>
							<tbody id="listOrderPra">
								<?php $no=1; foreach($containers as $c):?>
								<tr>
									<td><?=$no;?></td>
									<td><?=$c['crno'];?></td>
									<td><?=$c['cccode'];?></td>
									<td><?=$c['ctcode'];?></td>
									<td><?=$c['cclength'];?></td>
									<td><?=$c['ccheight'];?></td>
									<td><?=((isset($c['repofe'])&&$c['repofe']==1)?'Full':'Empty');?></td>
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
					<div class="widget-footer text-center">
						<p>
						<a href="<?=site_url('repoin');?>" class="btn btn-default" id="">Kembali</a>
						<a href="#" class="btn btn-success" id="cetakKwitansi"><i class="fa fa-print"></i> Cetak Kwitansi</a>
						</p>
					</div>					
				</div>
			</div>
		</div>		

	</div>
</div>

<?= $this->include('\Modules\RepoIn\Views\form_detail_header');?>

<?= $this->endSection();?>

<!-- Load JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\RepoIn\Views\js'); ?>

<?= $this->endSection();?>