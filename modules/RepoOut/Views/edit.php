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
											<?=cpopr_dropdown($data['cpopr']);?>
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
												<option value="11" <?=(isset($data['retype'])&&($data['retype']=='11')?'selected':'');?>>DEPOT to DEPOT</option>
												<option value="12" <?=(isset($data['retype'])&&($data['retype']=='12')?'selected':'');?>>DEPOT to PORT</option>
												<option value="13" <?=(isset($data['retype'])&&($data['retype']=='13')?'selected':'');?>>DEPOT to INTERCITY</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label  class="col-sm-5 control-label text-right">From</label>
										<div id="fromDepoBlok" class="col-sm-7 <?=(isset($data['retype'])&&($data['retype']=='11')?'':'hideBlock')?>">
											<?= $from_depo_dropdown ?>
										</div>
										<div id="fromPortBlok" class="col-sm-7 <?=(isset($data['retype'])&&($data['retype']=='12')?'':'hideBlock')?>">
											<?= $from_port_dropdown ?>
										</div>						
										<div id="fromCityBlok" class="col-sm-7 <?=(isset($data['retype'])&&($data['retype']=='13')?'':'hideBlock')?>">
											<?= $from_city_dropdown ?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-5 control-label text-right">To</label>
										<div id="toDepoBlok" class="col-sm-7">
											<?= $to_depo_dropdown ?>
										</div>
										<div id="toPortBlok" class="col-sm-7 hideBlock">
											<?= $to_port_dropdown ?>
										</div>						
										<div id="toCityBlok" class="col-sm-7 hideBlock">
											<?= $to_city_dropdown ?>
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
											<input type="text" class="form-control" id="voyid" name="recpivoyid" value="<?=$data['recpivoyid']?>">
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
											<input type="text" name="repovendor" class="form-control" id="repovendor" value="<?=$data['repovendor']?>">
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-4 control-label text-right">F/E</label>
										<div class="col-sm-6">
											<label class="control-inline fancy-radio custom-bgcolor-green">
												<input type="radio" name="repofe" id="repofe" value="1" <?=((isset($data['repofe'])&&$data['repofe']==1)?'checked':'')?>>
												<span><i></i>Full</span>
											</label>
											<label class="control-inline fancy-radio custom-bgcolor-green">
												<input type="radio" name="repofe" id="repofe" value="0" <?=((isset($data['repofe'])&&$data['repofe']==0)?'checked':'')?>>
												<span><i></i>Empty</span>
											</label>								
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

									<div id="breakDown" <?=((isset($data['rebill'])&&($data['rebill']=='Breakdown')?'style="display:block;"':'style="display:none"'))?>>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Lift On Adm</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>		
											<div class="col-sm-4">
												<input type="text" name="relift" class="form-control" id="relift" value="<?=$data['relift']?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Doc Fee</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="redoc" class="form-control" id="redoc" value="<?=$data['redoc']?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Port Charge 20"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="rtportcharger20" class="form-control" id="rtportcharger20" value="<?=$data['reportcharger20']?>" readonly>
											</div>
										</div>										
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Total Port Charge 20"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="rtportchargertot20" class="form-control" id="rtportchargertot20" value="<?=$data['reportchargertot20']?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Port Charge 40"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="rtportcharger40" class="form-control" id="rtportcharger40" value="<?=$data['reportcharger40']?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Total Port Charge 40"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="rtportchargertot40" class="form-control" id="rtportchargertot40" value="<?=$data['reportchargertot40']?>" readonly>
											</div>
										</div>						
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Trucking 20"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="rttruck20" class="form-control" id="rttruck20" value="<?=$data['retruck20']?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Total Trucking 20"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="rttrucktot20" class="form-control" id="rttrucktot20" value="<?=$data['retrucktot20']?>" readonly>
											</div>
										</div>						
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Trucking 40"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="rttruck40" class="form-control" id="rttruck40" value="<?=$data['retruck40']?>" readonly>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-4 control-label text-right">Total Trucking 40"</label>
											<div class="col-sm-2">
												<input type="text" name="" class="form-control" id="" value="IDR" readonly>
											</div>	
											<div class="col-sm-4">
												<input type="text" name="rttrucktot40" class="form-control" id="rttrucktot40" value="<?=$data['retrucktot40']?>" readonly>
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
												<input type="text" name="subtotpack" class="form-control" id="subtotpack" value="<?=$data['subtotpack']?>" readonly >
											</div>
										</div>						
									</div>

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
											
											<div class="form-group" <?=((isset($data['rebill'])&&($data['rebill']=='Breakdown')?'style="display:block;"':'style="display:none"'))?>>
												<label class="col-sm-4 control-label text-right" style="color:blue;">Sub Total 2</label>
												<div class="col-sm-2">
													<input type="text" name="" class="form-control" id="" value="IDR" readonly>
												</div>								
												<div class="col-sm-4">
													<input type="text" name="reother1" class="form-control" id="reother1" value="<?=$data['reother1']?>" required>
												</div>
											</div>
																					
											<div class="form-group" <?=((isset($data['rebill'])&&($data['rebill']=='Package')?'style="display:block;"':'style="display:none"'))?>>
												<label class="col-sm-4 control-label text-right" style="color:blue;">Sub Total 2</label>
												<div class="col-sm-2">
													<input type="text" name="" class="form-control" id="" value="IDR" readonly>
												</div>								
												<div class="col-sm-4">
													<input type="text" name="reother2" class="form-control" id="reother2"  value="<?=$data['reother2']?>" required>
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
													<input type="text" name="totbreak" class="form-control" id="totbreak" value="<?=$data['totbreak']?>" required readonly <?=((isset($data['rebill'])&&($data['rebill']=='Breakdown')?'style="display:block;"':'style="display:none"'))?>>
													<input type="text" name="totpack" class="form-control" id="totpack" value="<?=$data['totpack']?>" required readonly <?=((isset($data['rebill'])&&($data['rebill']=='Package')?'style="display:block;"':'style="display:none"'))?>>
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
						<p><button class="btn btn-success" data-toggle="modal" data-target="#myModal" id="insertContainer"><i class="fa fa-plus"></i>&nbsp;Add Container</button>
						</p>
						<div class="table-responsive vscroll">
						<table id="rcTable" class="table table-hover table-bordered" style="width:100%;">
							<thead>
								<tr>
									<th></th>
									<th>No.</th>
									<th>Container #</th>
									<th>ID Code</th>
									<th>Type</th>
									<th>Length</th>
									<th>Height</th>
									<th>Hold/Release</th>
									<th>Remark</th>
									<th>Seal No</th>
								</tr>
							</thead>
							<?php if($containers!=""):?>
							<tbody id="listOrderPra">
								<?php $no=1; foreach($containers as $c):?>
								<tr>
									<td>
										<a href='#' class='btn btn-xs btn-danger delete' data-kode="<?=$c['repocrnoid']?>">delete</a>
									</td>									
									<td><?=$no;?></td>
									<td><?=$c['crno'];?></td>
									<td><?=$c['cccode'];?></td>
									<td><?=$c['ctcode'];?></td>
									<td><?=$c['cclength'];?></td>
									<td><?=$c['ccheight'];?></td>
									<td><?=((isset($c['reposhold'])&&$c['reposhold']==1)?'Hold':'Release');?></td>
									<td><?=$c['reporemark'];?></td>
									<td><?=$c['sealno'];?></td>
								</tr>
								<?php $no++; endforeach; ?>
							</tbody>
							<?php else:?>
								<tr><td colspan="9">Data Container kosong.</td></tr>
							<?php endif?>
						</table>
						</div>						
					</div>
					<div class="widget-footer text-center">
						<a href="<?=site_url('repoout');?>" class="btn btn-default" id="">Kembali</a>
						<a href="#" class="btn btn-danger" id="updateNewData"><i class="fa fa-save"></i> Save All</a>
					</div>					
				</div>
			</div>
		</div>		

	</div>
</div>

<?= $this->include('\Modules\RepoOut\Views\form_detail_header');?>

<?= $this->endSection();?>

<!-- Load JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\RepoOut\Views\js'); ?>

<?= $this->endSection();?>