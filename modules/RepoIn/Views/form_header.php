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
						<div class="col-sm-4">
							<?=principal_dropdown("");?>
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
<!-- 								<option value="11">DEPOT to DEPOT (OUT)</option>
								<option value="12">DEPOT to PORT</option>
								<option value="13">DEPOT to INTERCITY</option> -->
								<option value="21">DEPOT to DEPOT</option>
								<option value="22">PORT to DEPOT</option>
								<option value="23">INTERCITY to DEPOT</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-sm-5 control-label text-right">From</label>
						<div id="fromDepoBlok" class="col-sm-7 hideBlock">
							<?=depo_dropdown2("retfrom","000")?>
						</div>
						<div id="fromPortBlok" class="col-sm-7 hideBlock">
							<?=port_dropdown("retfrom","")?>
						</div>						
						<div id="fromCityBlok" class="col-sm-7 hideBlock">
							<?=city_dropdown("retfrom","1")?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-5 control-label text-right">To</label>
						<div id="toDepoBlok" class="col-sm-7 hideBlock">
							<?=depo_dropdown2("retto","000")?>
						</div>
						<div id="toPortBlok" class="col-sm-7 hideBlock">
							<?=port_dropdown("retto","")?>
						</div>						
						<div id="toCityBlok" class="col-sm-7 hideBlock">
							<?=city_dropdown("retto","1")?>
						</div>
					</div>

					<div class="form-group">
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
					<div class="form-group" style="">
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
							<?=vessel_dropdown("repoves","");?>
						</div>
					</div>		
					<div class="form-group">
						<label for="repovoyid" class="col-sm-5 control-label text-right">Voyage</label>
						<div class="col-sm-7">
							<!-- <input type="text" name="name" class="form-control" id="name"> -->
							<!-- <?=voyage_dropdown(); ?> -->
							<input type="text" class="form-control" id="repovoyid" name="repovoyid">
						</div>
					</div>		
					<div class="form-group">
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
							<input type="text" name="reorderno" class="form-control" id="reorderno" value="<?=$repoin_no;?>" readonly>
						</div>
					</div>								
					<div class="form-group">
						<label for="repopratgl" class="col-sm-4 control-label text-right">Repo Pra In Date</label>
						<div class="col-sm-6">
							<div class="input-group">
								<input type="text" name="redate" id="redate" class="form-control" value="<?=date('d-m-Y');?>" readonly>
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
							<input type="text" name="reautno" class="form-control" id="reautno" required>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-right">Repo Vendor</label>
						<div class="col-sm-6">
							<input type="text" name="cpicargo" class="form-control" id="cpicargo" required>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label text-right">Billing Type</label>
						<div class="col-sm-6">
							<select name="rebilltype" id="rebilltype" class="">
								<option value="0">- select -</option>
								<option value="1">Breakdown</option>
								<option value="2">Package</option>
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
								<input type="text" name="revlift" class="form-control" id="revlift" value="0" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label text-right">Doc Fee</label>
							<div class="col-sm-2">
								<input type="text" name="" class="form-control" id="" value="IDR" readonly>
							</div>	
							<div class="col-sm-4">
								<input type="text" name="revdoc" class="form-control" id="revdoc" value="0" required>
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
								<input type="text" name="revpack20" class="form-control" id="revpack20" value="0" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label text-right">Total 20"</label>
							<div class="col-sm-2">
								<input type="text" name="" class="form-control" id="" value="IDR" readonly>
							</div>
							<div class="col-sm-4">
								<input type="text" name="revpacktot20" class="form-control" id="revpacktot20" value="0" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label text-right">40"</label>
							<div class="col-sm-2">
								<input type="text" name="" class="form-control" id="" value="IDR" readonly>
							</div>	
							<div class="col-sm-4">
								<input type="text" name="revpack40" class="form-control" id="revpack40" value="0">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label text-right">Total 40"</label>
							<div class="col-sm-2">
								<input type="text" name="" class="form-control" id="" value="IDR" readonly>
							</div>			
							<div class="col-sm-4">
								<input type="text" name="revpacktot40" class="form-control" id="revpacktot40" value="0" >
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label text-right">45"</label>
							<div class="col-sm-2">
								<input type="text" name="" class="form-control" id="" value="IDR" readonly>
							</div>			
							<div class="col-sm-4">
								<input type="text" name="revpack45" class="form-control" id="revpack45" value="0">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label text-right">Total45"</label>
							<div class="col-sm-2">
								<input type="text" name="" class="form-control" id="" value="IDR" readonly>
							</div>	
							<div class="col-sm-4">
								<input type="text" name="revpacktot45" class="form-control" id="revpacktot45" value="0" >
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
							<div class="form-group">
								<label class="col-sm-4 control-label text-right" style="color:blue;">Sub Total 2</label>
								<div class="col-sm-2">
									<input type="text" name="" class="form-control" id="" value="IDR" readonly>
								</div>								
								<div class="col-sm-4">
									<input type="text" name="reother1" class="form-control" id="reother1" value="0" required>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label text-right" style="color:blue;">Sub Total 2</label>
								<div class="col-sm-2">
									<input type="text" name="" class="form-control" id="" value="IDR" readonly>
								</div>								
								<div class="col-sm-4">
									<input type="text" name="reother2" class="form-control" id="reother2"  value="0" required>
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
				<!-- <button type="button" id="cancel" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</button> -->
			</div>	
		</div>
	</form>
</div>
