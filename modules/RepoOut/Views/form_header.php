 <style type="text/css">
	.hideBlock{
		display: none;
	}
	.showBlock{
		display: block;
	}
</style>
<div class="row">
	<form id="formOrderRepo" class="form-horizontal" role="form" method="post">
		<?= csrf_field() ?>
		<fieldset>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="cpopr " class="col-sm-5 control-label text-right">Principal</label>
						<div class="col-sm-7">
							<?=cpopr_dropdown();?>
						</div>
					</div>
					<div class="form-group">
						<label for="cpopr " class="col-sm-5 control-label text-right">Customer</label>
						<div class="col-sm-4">
							<input type="text" name="cpcust" id="cpcust" class="form-control" readonly>
						</div>
					</div>												
					<div class="form-group">
						<label for="cucode" class="col-sm-5 control-label text-right">Repo Type</label>
						<div class="col-sm-7">
							<select name="retype" id="retype" class="selects">
								<option value="">- select -</option>
								<option value="11">DEPOT to DEPOT (OUT)</option>
								<option value="12">DEPOT to PORT</option>
								<option value="13">DEPOT to INTERCITY</option> 
							</select>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-sm-5 control-label text-right">From</label>
						<div id="fromDepoBlok" class="col-sm-7 hideBlock">
							<?= $from_depo_dropdown ?>
						</div>
						<div id="fromPortBlok" class="col-sm-7 hideBlock">
							<?= $from_port_dropdown ?>
						</div>						
						<div id="fromCityBlok" class="col-sm-7 hideBlock">
							<?= $from_city_dropdown ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-5 control-label text-right">To</label>
						<div id="toDepoBlok" class="col-sm-7 hideBlock">
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
								<input type="text" name="repodisdat" id="repodisdat" class="form-control tanggal" required>
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>									
						</div>
					</div>
					<div class="form-group">
						<label for="readdr" class="col-sm-5 control-label text-right">Address</label>
						<div class="col-sm-7">
							<textarea class="form-control" style="resize: none;" rows="3" cols="32" name="readdr" id="readdr"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="recity" class="col-sm-5 control-label text-right">City</label>
						<div class="col-sm-7">
							<?=city_dropdown("recity","1")?>
						</div>
					</div>
					<div class="form-group">
						<label for="redline" class="col-sm-5 control-label text-right">Deadline</label>
						<div class="col-sm-7">
							<div class="input-group">
								<input type="text" name="redline" id="redline" class="form-control tanggal" required>
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
							<?=repoves_dropdown();?>
						</div>
					</div>		
					<div class="form-group">
						<label for="repovoyid" class="col-sm-5 control-label text-right">Voyage</label>
						<div class="col-sm-7">
							<input type="text" class="form-control" id="recpivoyid" name="recpivoyid" required>
							<input type="hidden" class="form-control" id="voyno" name="voyno" value="0">
						</div>
					</div>		
					<div class="form-group" style="display:none">
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
							<input type="text" name="reorderno" class="form-control" id="reorderno" value="<?=$RepoOut_no;?>" readonly>
						</div>
					</div>								
					<div class="form-group">
						<label for="repopratgl" class="col-sm-4 control-label text-right">Repo Pra Out Date</label>
						<div class="col-sm-6">
							<div class="input-group">
								<input type="text" name="redate" id="redate" class="form-control" value="<?=date('d-m-Y');?>" readonly>
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>	
						</div>
					</div>														
					<div class="form-group">
						<label for="repojam" class="col-sm-4 control-label text-right">Time Out</label>
						<div class="col-sm-4">
							<input type="text" name="repojam" class="form-control" id="repojam" value="<?=date('H:i:s');?>" readonly>
						</div>
						<label class="col-sm-2 control-label">hh:mm:ss</label>
					</div>
					<div class="form-group">
						<label for="reautno" class="col-sm-4 control-label text-right">Authorized No</label>
						<div class="col-sm-6">
							<input type="text" name="reautno" class="form-control" id="reautno" required>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-right">Repo Vendor</label>
						<div class="col-sm-6">
							<input type="text" name="repovendor" class="form-control" id="repovendor" required>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-right">Billing Type</label>
						<div class="col-sm-6">
							<select name="rebill" id="rebill" class="">
								<option value="0">- select -</option>
								<option value="Breakdown">Breakdown</option>
								<option value="Package">Package</option>
							</select>
						</div>
					</div>

					<div id="breakDown" style="display:none;">
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
								<input type="text" name="re20" class="form-control" id="re20" value="0" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label text-right">Total Haulage 20"</label>
							<div class="col-sm-2">
								<input type="text" name="" class="form-control" id="" value="IDR" readonly>
							</div>	
							<div class="col-sm-4">
								<input type="text" name="retot20" class="form-control" id="retot20" value="0" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label text-right">Haulage 40"</label>
							<div class="col-sm-2">
								<input type="text" name="" class="form-control" id="" value="IDR" readonly>
							</div>
							<div class="col-sm-4">
								<input type="text" name="re40" class="form-control" id="re40" value="0" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label text-right">Total Haulage 40"</label>
							<div class="col-sm-2">
								<input type="text" name="" class="form-control" id="" value="IDR" readonly>
							</div>	
							<div class="col-sm-4">
								<input type="text" name="retot40" class="form-control" id="retot40" value="0" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label text-right">Haulage 45"</label>
							<div class="col-sm-2">
								<input type="text" name="" class="form-control" id="" value="IDR" readonly>
							</div>	
							<div class="col-sm-4">
								<input type="text" name="re45" class="form-control" id="re45" value="0" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label text-right">Total Haulage 45"</label>
							<div class="col-sm-2">
								<input type="text" name="" class="form-control" id="" value="IDR" readonly>
							</div>	
							<div class="col-sm-4">
								<input type="text" name="retot45" class="form-control" id="retot45" value="0" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label text-right" style="color:blue;">Sub Total 1</label>
							<div class="col-sm-2">
								<input type="text" name="" class="form-control" id="" value="SUBTOT" readonly>
							</div>
							<div class="col-sm-4">
								<input type="text" name="subtotbreak" class="form-control" id="subtotbreak" value="0" requirereadonly>
							</div>
						</div>				
					</div>

					<div id="Package" style="display:none;">
						<div class="form-group">
							<label class="col-sm-4 control-label text-right">20"</label>
							<div class="col-sm-2">
								<input type="text" name="" class="form-control" id="" value="IDR" readonly>
							</div>	
							<div class="col-sm-4">
								<input type="text" name="recpack20" class="form-control" id="recpack20" value="0" required>
								<input type="hidden" name="rechaul20" class="form-control" id="rechaul20" value="0" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label text-right">Total 20"</label>
							<div class="col-sm-2">
								<input type="text" name="" class="form-control" id="" value="IDR" readonly>
							</div>
							<div class="col-sm-4">
								<input type="text" name="recpacktot20" class="form-control" id="recpacktot20" value="0" required>
								<input type="hidden" name="rechaultot20" class="form-control" id="rechaultot20" value="0" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label text-right">40"</label>
							<div class="col-sm-2">
								<input type="text" name="" class="form-control" id="" value="IDR" readonly>
							</div>	
							<div class="col-sm-4">
								<input type="text" name="recpack40" class="form-control" id="recpack40" value="0">
								<input type="hidden" name="rechaul40" class="form-control" id="rechaul40" value="0">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label text-right">Total 40"</label>
							<div class="col-sm-2">
								<input type="text" name="" class="form-control" id="" value="IDR" readonly>
							</div>			
							<div class="col-sm-4">
								<input type="text" name="recpacktot40" class="form-control" id="recpacktot40" value="0" >
								<input type="hidden" name="rechaultot40" class="form-control" id="rechaultot40" value="0" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label text-right">45"</label>
							<div class="col-sm-2">
								<input type="text" name="" class="form-control" id="" value="IDR" readonly>
							</div>			
							<div class="col-sm-4">
								<input type="text" name="recpack45" class="form-control" id="recpack45" value="0">
								<input type="hidden" name="rechaul45" class="form-control" id="rechaul45" value="0">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label text-right">Total45"</label>
							<div class="col-sm-2">
								<input type="text" name="" class="form-control" id="" value="IDR" readonly>
							</div>	
							<div class="col-sm-4">
								<input type="text" name="recpacktot45" class="form-control" id="recpacktot45" value="0" >
								<input type="hidden" name="rechaultot45" class="form-control" id="rechaultot45" value="0" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label text-right" style="color:blue;">Sub Total 1</label>
							<div class="col-sm-2">
								<input type="text" name="" class="form-control" id="" value="SUBTOT" readonly>
							</div>'		
							<div class="col-sm-4">
								<input type="text" name="subtotpack" class="form-control" id="subtotpack" value="0" readonly required>
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
										<input type="text" name="std20" class="form-control" id="std20" value="<?=(isset($QTY['std20'])?$QTY['std20']:'0')?>">
									</div>
								</div>								
								<div class="form-group">
									<label for="code" class="col-sm-2 control-label text-right">40"</label>
									<div class="col-sm-8">
										<input type="text" name="std40" class="form-control" id="std40" value="<?=(isset($QTY['std40'])?$QTY['std40']:'0')?>">
									</div>
								</div>												
							</div>
							<div class="col-sm-6">
								<label  class="col-sm-offset-4"><b>HC</b></label>
								<div class="form-group">
									<label for="code" class="col-sm-2 control-label text-right">20"</label>
									<div class="col-sm-8">
										<input type="text" name="hc20" class="form-control" id="hc20" value="<?=(isset($QTY['hc20'])?$QTY['hc20']:'0')?>">
									</div>
								</div>								
								<div class="form-group">
									<label for="code" class="col-sm-2 control-label text-right">40"</label>
									<div class="col-sm-8">
										<input type="text" name="hc40" class="form-control" id="hc40" value="<?=(isset($QTY['hc40'])?$QTY['hc40']:'0')?>">
									</div>
								</div>
								<div class="form-group">
									<label for="code" class="col-sm-2 control-label text-right">45"</label>
									<div class="col-sm-8">
										<input type="text" name="hc45" class="form-control" id="hc45" value="<?=(isset($QTY['hc45'])?$QTY['hc45']:'0')?>">
									</div>
								</div>											
							</div>										
						</div>									
					</div>
					<div class="col-sm-6">
						
						<legend><center>Additional Charges </center></legend>
						<div class="row">
							<div class="form-group" id="blockReother1">
								<label class="col-sm-4 control-label text-right" style="color:blue;">Sub Total 2</label>
								<div class="col-sm-2">
									<input type="text" name="" class="form-control" id="" value="IDR" readonly>
								</div>								
								<div class="col-sm-4">
									<input type="text" name="reother1" class="form-control" id="reother1" value="0" required>
								</div>
							</div>
							<div class="form-group" id="blockReother2" style="display:none;">
								<label class="col-sm-4 control-label text-right" style="color:blue;">Sub Total 2</label>
								<div class="col-sm-2">
									<input type="text" name="" class="form-control" id="" value="IDR" readonly>
								</div>								
								<div class="col-sm-4">
									<input type="text" name="reother2" class="form-control" id="reother2"  value="0" required >
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
								<div class="col-sm-4">
									<input type="text" name="totpack" class="form-control" id="totpack" value="0"  style="display:none;" required>
									<input type="text" name="totbreak" class="form-control" id="totbreak" value="0" required>
								</div>
							</div>																						
						</div>									
					</div>
				</div>
			</div>

		<div class="row">
		</div>
		</fieldset>


		<div class="form-footer">
			<div class="form-group text-center">
				<button type="submit" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
				<span class="block-loading"></span>
				<!-- <button type="button" id="cancel" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</button> -->
			</div>	
		</div>
	</form>
</div>
