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
		<h2>Repo Pra In</h2>
		<em>Order Repo Pra in</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table" id="OrderPra">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Data Order Repo</h3>
			</div>
			<div class="widget-content">
				<div class="row">
					<form id="fUpdateOrderRepo" class="form-horizontal" role="form" method="post">
						<?= csrf_field() ?>
						<input type="hidden" name="repoid" id="repoid" value="<?=$data['repoid'];?>">
						<fieldset>
							<div class="row">
								<div class="col-sm-6">
									<div class="form-group">
										<label for="cpopr " class="col-sm-5 control-label text-right">Principal</label>
										<div class="col-sm-4">
											<?=principal_dropdown($data['cpopr']);?>
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
											<select name="retype" id="retype" class="selects">
												<option value="">- select -</option>
				<!-- 								<option value="11">DEPOT to DEPOT (OUT)</option>
												<option value="12">DEPOT to PORT</option>
												<option value="13">DEPOT to INTERCITY</option> -->
												<option value="21" <?=(isset($data['retype'])&&($data['retype']=='21')?'selected':'');?>>DEPOT to DEPOT</option>
												<option value="22" <?=(isset($data['retype'])&&($data['retype']=='22')?'selected':'');?>>PORT to DEPOT</option>
												<option value="23" <?=(isset($data['retype'])&&($data['retype']=='23')?'selected':'');?>>INTERCITY to DEPOT</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label  class="col-sm-5 control-label text-right">From</label>
										<div id="fromDepoBlok" class="col-sm-7 <?=(isset($data['retype'])&&($data['retype']=='21')?'':'hideBlock')?>">
											<?=depo_dropdown2("retfrom",$data['retfrom'])?>
										</div>
										<div id="fromPortBlok" class="col-sm-7 <?=(isset($data['retype'])&&($data['retype']=='22')?'':'hideBlock')?>">
											<?=port_dropdown("retfrom",$data['retfrom'])?>
										</div>						
										<div id="fromCityBlok" class="col-sm-7 <?=(isset($data['retype'])&&($data['retype']=='23')?'':'hideBlock')?>">
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
												<input type="text" name="repodisdat" id="repodisdat" class="form-control tanggal" value="<?=date('d-m-Y',strtotime($data['redate']))?>">
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
											<!-- <input type="text" name="name" class="form-control" id="name"> -->
											<!-- <?=voyage_dropdown(); ?> -->
											<input type="text" class="form-control" id="repovoyid" name="repovoyid" value="<?=$data['recpivoyid']?>">
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
										<label for="reorderno" class="col-sm-4 control-label text-right">Repo Pra In No</label>
										<div class="col-sm-6">
											<input type="text" name="reorderno" class="form-control" id="reorderno" value="<?=$data['reorderno'];?>" readonly>
										</div>
									</div>								
									<div class="form-group">
										<label for="repopratgl" class="col-sm-4 control-label text-right">Repo Pra In Date</label>
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
											<select name="rebill" id="rebill" class="">
												<option value="0">- select -</option>
												<option value="Breakdown" <?=(isset($data['rebill'])&&($data['rebill']=='Breakdown')?'selected':'');?>>Breakdown</option>
												<option value="Package" <?=(isset($data['rebill'])&&($data['rebill']=='Package')?'selected':'');?>>Package</option>
											</select>
										</div>
									</div>
									<?php if($data['rebill']=='Breakdown'):?>
									<div id="breakDown">
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Lift On Adm</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>		
											<div class="col-sm-4">
												<input type="text" name="relift" class="form-control" id="relift" value="0" required>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Doc Fee</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="redoc" class="form-control" id="redoc" value="0" required>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Haulage 20"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="re20" class="form-control" id="re20" value="0" value="<?=$data['re20']?>" required>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Total Haulage 20"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="retot20" class="form-control" id="retot20" value="<?=$data['retot20']?>"required>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Haulage 40"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>
											<div class="col-sm-4">
												<input type="text" name="re40" class="form-control" id="re40" value="<?=$data['re40']?>" required>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Total Haulage 40"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="retot40" class="form-control" id="retot40" value="<?=$data['retot40']?>" required>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Haulage 45"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="re45" class="form-control" id="re45" value="<?=$data['re45']?>" required>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Total Haulage 45"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="retot45" class="form-control" id="retot45" value="<?=$data['retot45']?>" required>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right" style="color:blue;">Sub Total 1</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="SUBTOT" readonly>
											</div>
											<div class="col-sm-4">
												<input type="text" name="subtotbreak" class="form-control" id="subtotbreak" value="<?=$data['subtotbreak']?>" requirereadonly>
											</div>
										</div>				
									</div>
									<?php elseif($data['rebill']=='Package'):?>
									<div id="Package">
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">20"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="recpack20" class="form-control" id="recpack20" value="<?=$data['recpack20']?>" required>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Total 20"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>
											<div class="col-sm-4">
												<input type="text" name="recpacktot20" class="form-control" id="recpacktot20" value="<?=$data['recpacktot20']?>" required>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">40"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="recpack40" class="form-control" id="recpack40" value="<?=$data['recpack40']?>">
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Total 40"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>			
											<div class="col-sm-4">
												<input type="text" name="recpacktot40" class="form-control" id="recpacktot40" value="<?=$data['recpacktot40']?>" >
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">45"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>			
											<div class="col-sm-4">
												<input type="text" name="recpack45" class="form-control" id="recpack45" value="<?=$data['recpack45']?>">
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Total45"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="recpacktot45" class="form-control" id="recpacktot45" value="<?=$data['recpacktot45']?>" >
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right" style="color:blue;">Sub Total 1</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="SUBTOT" readonly>
											</div>'		
											<div class="col-sm-4">
												<input type="text" name="subtotpack" class="form-control" id="subtotpack" value="<?=$data['subtotpack']?>" readonly >
											</div>
										</div>						
									</div>
								<?php endif; ?>

								</div>	
							</div>
							<div class="row" style="margin-top: 25px;">
								<div class="col-sm-12">
									<div class="col-sm-6">
										
										<legend><center>Quantity</center></legend>
										<div class="row">
											<div class="col-sm-6">
												<label class="col-sm-offset-4"><b>STD</b></label>
												<div class="form-group">
													<label for="code" class="col-sm-2 control-label text-right">20"</label>
													<div class="col-sm-8">
														<input type="text" name="std20" class="form-control" id="std20" value="<?=@$QTY['std20']?>">
													</div>
												</div>								
												<div class="form-group">
													<label for="code" class="col-sm-2 control-label text-right">40"</label>
													<div class="col-sm-8">
														<input type="text" name="std40" class="form-control" id="std40" value="<?=@$QTY['std40']?>">
													</div>
												</div>												
											</div>
											<div class="col-sm-6">
												<label  class="col-sm-offset-4"><b>HC</b></label>
												<div class="form-group">
													<label for="code" class="col-sm-2 control-label text-right">20"</label>
													<div class="col-sm-8">
														<input type="text" name="hc20" class="form-control" id="hc20" value="<?=@$QTY['hc20']?>">
													</div>
												</div>								
												<div class="form-group">
													<label for="code" class="col-sm-2 control-label text-right">40"</label>
													<div class="col-sm-8">
														<input type="text" name="hc40" class="form-control" id="hc40" value="<?=@$QTY['hc40']?>">
													</div>
												</div>
												<div class="form-group">
													<label for="code" class="col-sm-2 control-label text-right">45"</label>
													<div class="col-sm-8">
														<input type="text" name="hc45" class="form-control" id="hc45" value="<?=@$QTY['hc45']?>">
													</div>
												</div>											
											</div>										
										</div>									
									</div>
									<div class="col-sm-6">
										
										<legend><center>Additional Charges </center></legend>
										<div class="row">

											<?php if($data['rebill']=='Breakdown'):?>

											<div class="form-group">
												<label class="col-sm-4 control-label text-right" style="color:blue;">Sub Total 2</label>
												<div class="col-sm-2">
													<input type="text" name="" class="form-control" id="" value="IDR" readonly>
												</div>								
												<div class="col-sm-4">
													<input type="text" name="reother1" class="form-control" id="reother1" value="<?=$data['reother1']?>" required>
												</div>
											</div>

											<?php elseif($data['rebill']=='Package'):?>

											<div class="form-group">
												<label class="col-sm-4 control-label text-right" style="color:blue;">Sub Total 2</label>
												<div class="col-sm-2">
													<input type="text" name="" class="form-control" id="" value="IDR" readonly>
												</div>								
												<div class="col-sm-4">
													<input type="text" name="reother2" class="form-control" id="reother2"  value="<?=$data['reother2']?>" required>
												</div>
											</div>	

											<?php endif; ?>					

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
												<div class="col-sm-4">
													<?php if($data['rebill']=='Breakdown'):?>
													<input type="text" name="totbreak" class="form-control" id="totbreak" value="<?=$data['totbreak']?>" required>
													<?php elseif($data['rebill']=='Package'):?>
													<input type="text" name="totpack" class="form-control" id="totpack" value="<?=$data['totpack']?>" required>
													<?php endif; ?>
												</div>
											</div>																						
										</div>									
									</div>
								</div>
							</div>

						<div class="row">
						</div>
						</fieldset>
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
									<th></th>
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
									<td>
										<a href='#' class='btn btn-xs btn-info cetak_kitir'	data-crno="<?=$c['crno']?>">cetak kitir</a>
									</td>									
								</tr>
								<?php $no++; endforeach; ?>
							</tbody>
							<?php else:?>
								<tr><td colspan="9">Data Container kosong.</td></tr>
							<?php endif?>
						</table>						
					</div>
					<div class="widget-footer text-center">
						<a href="<?=site_url('repoin');?>" class="btn btn-default">Kembali</a>
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