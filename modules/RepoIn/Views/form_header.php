<style type="text/css">
	.hideBlock{
		display: none;
	}
	.showBlock{
		display: block;
	}
</style>
<div class="row">
	<form id="#form1" class="form-horizontal" role="form">
		<?= csrf_field() ?>
		<fieldset>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label for="cucode" class="col-sm-5 control-label text-right">Repo Type</label>
						<div class="col-sm-7">
							<select name="repoType" id="repoType" class="selects">
								<option value="">- select -</option>
								<option value="DTDOUT">DEPOT to DEPOT (OUT)</option>
								<option value="DTP">DEPOT to PORT</option>
								<option value="DTI">DEPOT to INTERCITY</option>
								<option value="DTDIN">DEPOT to DEPOT (IN)</option>
								<option value="PTD">PORT to DEPOT</option>
								<option value="ITD">INTERCITY to DEPOT</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label  class="col-sm-5 control-label text-right">From</label>
						<div id="fromDepoBlok" class="col-sm-7 hideBlock">
							<?=depo_dropdown()?>
						</div>
						<div id="fromPortBlok" class="col-sm-7 hideBlock">
							<?=port_dropdown()?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-5 control-label text-right">To</label>
						<div id="toDepoBlok" class="col-sm-7 hideBlock">
							<?=depo_dropdown()?>
						</div>
						<div id="toPortBlok" class="col-sm-7 hideBlock">
							<?=port_dropdown()?>
						</div>
					</div>

					<div class="form-group">
						<label for="cpidish " class="col-sm-5 control-label text-right">Origin Port</label>
						<div class="col-sm-4">
							<?=port_dropdown();?>
						</div>
						<label class="col-sm-3 control-label">* Port Country</label>
					</div>	
					<div class="form-group">
						<label for="cpidisdat" class="col-sm-5 control-label text-right">Discharge Date</label>
						<div class="col-sm-7">
							<div class="input-group">
								<input type="text" name="cpidisdat" id="cpidisdat" class="form-control tanggal" required>
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>									
						</div>
					</div>
					<div class="form-group">
						<label for="adress" class="col-sm-5 control-label text-right">Address</label>
						<div class="col-sm-7">
							<textarea class="form-control" style="resize: none;" rows="5" cols="32" name="address"><?=@$address?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="city" class="col-sm-5 control-label text-right">City</label>
						<div class="col-sm-7">
							<input type="text" name="city" class="form-control" id="city" value="<?=@$city?>">
						</div>
					</div>
					<div class="form-group">
						<label for="deadline" class="col-sm-5 control-label text-right">Deadline</label>
						<div class="col-sm-7">
							<div class="input-group">
								<input type="text" name="deadline" id="deadline" class="form-control tanggal" required>
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>									
						</div>
					</div>
					<div class="form-group">
						<label for="cpives" class="col-sm-5 control-label text-right">Ex Vessel</label>
						<div class="col-sm-7">
							<?=vessel_dropdown();?>
						</div>
					</div>		
					<div class="form-group">
						<label for="cpivoyid" class="col-sm-5 control-label text-right">Voyage</label>
						<div class="col-sm-7">
							<!-- <input type="text" name="name" class="form-control" id="name"> -->
							<!-- <?=voyage_dropdown(); ?> -->
							<input type="text" class="form-control" id="cpivoyid" name="cpivoyid">
						</div>
					</div>		
					<div class="form-group">
						<label for="cpdepo" class="col-sm-5 control-label text-right">Depot</label>
						<div class="col-sm-7">
							<?=depo_dropdown();?>
						</div>
					</div>	
					<div class="form-group">
						<label for="cpdepo" class="col-sm-5 control-label text-right">Sub Depot</label>
						<div class="col-sm-7">
							<?=depo_dropdown();?>
						</div>
					</div>	
				</div>

				<div class="col-sm-6">
					<div class="form-group">
						<label for="cpiorderno" class="col-sm-4 control-label text-right">Repo Pra In No</label>
						<div class="col-sm-6">
							<input type="text" name="cpiorderno" class="form-control" id="cpiorderno" value="<?=$repoin_no;?>" readonly>
						</div>
					</div>								
					<div class="form-group">
						<label for="cpipratgl" class="col-sm-4 control-label text-right">Repo Pra In Date</label>
						<div class="col-sm-6">
							<div class="input-group">
								<input type="text" name="cpipratgl" id="cpipratgl" class="form-control tanggal" required>
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							</div>	
						</div>
					</div>														
					<div class="form-group">
						<label for="cpijam" class="col-sm-4 control-label text-right">Time In</label>
						<div class="col-sm-4">
							<input type="text" name="cpijam" class="form-control" id="cpijam" required>
						</div>
						<label class="col-sm-2 control-label">hh:mm:ss</label>
					</div>
					<div class="form-group">
						<label for="cpicargo" class="col-sm-4 control-label text-right">Authorized No</label>
						<div class="col-sm-6">
							<input type="text" name="cpicargo" class="form-control" id="cpicargo" required>
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
							<select name="billType" id="billType" class="selects">
								<option value="">- select -</option>
								<option value="B">Breakdown</option>
								<option value="P">Package</option>
							</select>
						</div>
					</div>
						
					<div class="form-group breakdownBill hideBlock">
						<label class="col-sm-4 control-label text-right">Lift On Adm</label>
						<div class="col-sm-6">
							<input type="text" name="cpicargo" class="form-control" id="cpicargo" required>
						</div>
					</div>
					<div class="form-group breakdownBill hideBlock">
						<label class="col-sm-4 control-label text-right">Doc Fee</label>
						<div class="col-sm-6">
							<input type="text" name="cpicargo" class="form-control" id="cpicargo" required>
						</div>
					</div>
					<div class="form-group breakdownBill hideBlock">
						<label class="col-sm-4 control-label text-right">Haulage 20"</label>
						<div class="col-sm-6">
							<input type="text" name="cpicargo" class="form-control" id="cpicargo" required>
						</div>
					</div>
					<div class="form-group breakdownBill hideBlock">
						<label class="col-sm-4 control-label text-right">Total Haulage 20"</label>
						<div class="col-sm-6">
							<input type="text" name="cpicargo" class="form-control" id="cpicargo" required>
						</div>
					</div>
					<div class="form-group breakdownBill hideBlock">
						<label class="col-sm-4 control-label text-right">Haulage 40"</label>
						<div class="col-sm-6">
							<input type="text" name="cpicargo" class="form-control" id="cpicargo" required>
						</div>
					</div>
					<div class="form-group breakdownBill hideBlock">
						<label class="col-sm-4 control-label text-right">Total Haulage 20"</label>
						<div class="col-sm-6">
							<input type="text" name="cpicargo" class="form-control" id="cpicargo" required>
						</div>
					</div>
					<div class="form-group breakdownBill hideBlock">
						<label class="col-sm-4 control-label text-right">Haulage 45"</label>
						<div class="col-sm-6">
							<input type="text" name="cpicargo" class="form-control" id="cpicargo" required>
						</div>
					</div>
					<div class="form-group breakdownBill hideBlock">
						<label class="col-sm-4 control-label text-right">Haulage 45"</label>
						<div class="col-sm-6">
							<input type="text" name="cpicargo" class="form-control" id="cpicargo" required>
						</div>
					</div>
					<div class="form-group breakdownBill hideBlock">
						<label class="col-sm-4 control-label text-right" style="color:blue;">Sub Total</label>
						<div class="col-sm-6">
							<input type="text" name="cpicargo" class="form-control" id="cpicargo" required>
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
										<input type="text" name="name" class="form-control" id="name">
									</div>
								</div>								
								<div class="form-group">
									<label for="code" class="col-sm-2 control-label text-right">40"</label>
									<div class="col-sm-8">
										<input type="text" name="name" class="form-control" id="name">
									</div>
								</div>												
							</div>
							<div class="col-sm-6">
								<label  class="col-sm-offset-4"><b>HC</b></label>
								<div class="form-group">
									<label for="code" class="col-sm-2 control-label text-right">20"</label>
									<div class="col-sm-8">
										<input type="text" name="name" class="form-control" id="name">
									</div>
								</div>								
								<div class="form-group">
									<label for="code" class="col-sm-2 control-label text-right">40"</label>
									<div class="col-sm-8">
										<input type="text" name="name" class="form-control" id="name">
									</div>
								</div>
								<div class="form-group">
									<label for="code" class="col-sm-2 control-label text-right">45"</label>
									<div class="col-sm-8">
										<input type="text" name="name" class="form-control" id="name">
									</div>
								</div>											
							</div>										
						</div>									
					</div>
					<div class="col-sm-6">
						
						<legend><center>Additional Charges </center></legend>
						<div class="row">
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
						</div>									
					</div>
				</div>
			</div>
		</fieldset>
		<div class="form-footer">
			<div class="form-group text-center">
				<button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
				<button type="button" id="cancel" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</button>
			</div>	
		</div>
	</form>
</div>