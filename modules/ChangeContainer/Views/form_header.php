<?php 
$token = get_token_item();
$group_id = $token['groupId'];
?>

<div id="editOrderFrame" class="row" style="display:none;">
	<div class="col-sm-12">
		<div class="alert alert-success text-center">
			<button id="editOrder" class="btn btn-success"><i class="fa fa-pencil"></i>&nbsp;Edit</button>
		</div>
	</div>
</div>

<div class="row">
	<form method="post" id="fPraInOrder" class="form-horizontal" role="form" enctype="multipart/form-data">
		<?= csrf_field() ?>
		<input type="hidden" name="praid" id="praid">
		<fieldset>
			<div class="col-sm-6">

<?php /* 				
				<?php if($group_id==4 || $group_id==3): ?>
				
				<div class="form-group">
					<label for="prcode" class="col-sm-5 control-label text-right">Principal</label>
					<div class="col-sm-7">
						<?php if($prcode=="0"):
							echo principal_dropdown($selected=""); 
							$cucode="";
						else:?>
							<input type="text" name="prcode" class="form-control" id="prcode" value="<?=$prcode;?>" required>
						<?php endif; ?>
					</div>
				</div>
				<div class="form-group">
					<label for="cucode" class="col-sm-5 control-label text-right">Customer</label>
					<div class="col-sm-7">
						<input type="text" name="cucode" class="form-control" id="cucode" value="<?=$cucode?>" readOnly>
					</div>
				</div> 
				
				<?php endif; ?>	

*/ ?>
				<div class="form-group">
					<label for="cpidish " class="col-sm-5 control-label text-right">Destination Port</label>
					<div class="col-sm-4">
						<?=port_dropdown("cpidish","");?>
					</div>
					<label class="col-sm-3 control-label">* Port Country</label>
				</div>	
				<div class="form-group">
					<label for="cpidisdat" class="col-sm-5 control-label text-right">Loading Date</label>
					<div class="col-sm-7">
						<div class="input-group">
							<input type="text" name="cpidisdat" id="cpidisdat" class="form-control tanggal">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>									
					</div>
				</div>
				<div class="form-group" style="display:none;">
					<label for="liftoffcharge" class="col-sm-5 control-label text-right">Lift On Charged in Depot</label>
					<div class="col-sm-7">
						<label class="control-inline fancy-checkbox custom-color-green">
							<input type="checkbox" name="liftoffcharge" id="liftoffcharge" value="1" checked>
							<span></span>
						</label>
					</div>
				</div>
				<div class="form-group" class="form-group" style="display:none;">
					<label for="cpdepo" class="col-sm-5 control-label text-right">Depot</label>
					<div class="col-sm-7">
						<?=depo_dropdown("000");?>
					</div>
				</div>	
				<h2>&nbsp;</h2>
				<div class="row">
					<div class="col-sm-8 col-sm-offset-4">
						<div class="row">
							<legend>Quantity : </legend>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<label class="col-sm-offset-4"><b>STD</b></label>
								<div class="form-group">
									<label for="std20" class="col-sm-2 control-label text-right">20"</label>
									<div class="col-sm-8">
										<input type="text" name="std20" class="form-control" id="std20" value="0" readonly>
									</div>
								</div>								
								<div class="form-group">
									<label for="std40" class="col-sm-2 control-label text-right">40"</label>
									<div class="col-sm-8">
										<input type="text" name="std40" class="form-control" id="std40" value="0" readonly>
									</div>
								</div>												
							</div>
							<div class="col-sm-6">
								<label  class="col-sm-offset-4"><b>HC</b></label>
								<div class="form-group">
									<label for="hc20" class="col-sm-2 control-label text-right">20"</label>
									<div class="col-sm-8">
										<input type="text" name="hc20" class="form-control" id="hc20" value="0" readonly>
									</div>
								</div>								
								<div class="form-group">
									<label for="hc40" class="col-sm-2 control-label text-right">40"</label>
									<div class="col-sm-8">
										<input type="text" name="hc40" class="form-control" id="hc40" value="0" readonly>
									</div>
								</div>
								<div class="form-group">
									<label for="hc45" class="col-sm-2 control-label text-right">45"</label>
									<div class="col-sm-8">
										<input type="text" name="hc45" class="form-control" id="hc45" value="0" readonly>
									</div>
								</div>											
							</div>										
						</div>									
					</div>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group">
					<label for="cpiorderno" class="col-sm-4 control-label text-right">Pra Out No</label>
					<div class="col-sm-6">
						<input type="text" name="cpiorderno" class="form-control" id="cpiorderno" value="<?=@$prain_no;?>" readonly>
					</div>
				</div>								
				<div class="form-group">
					<label for="cpipratgl" class="col-sm-4 control-label text-right">Pra Out Date</label>
					<div class="col-sm-6">
						<div class="input-group">
							<input type="text" name="cpipratgl" id="cpipratgl" class="form-control" required readonly value="<?=date('m/d/Y');?>">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>	
					</div>
				</div>								
				<div class="form-group">
					<label for="cpirefin" class="col-sm-4 control-label text-right">RO Number #</label>
					<div class="col-sm-6">
						<input type="text" name="cpirefin" class="form-control" id="cpirefin" required>
					</div>
				</div>								
				<div class="form-group">
					<label for="cpijam" class="col-sm-4 control-label text-right">Time Out</label>
					<div class="col-sm-4">
						<input type="text" name="cpijam" class="form-control" id="cpijam" required readonly value="<?=date('H:i:s');?>">
					</div>
				</div>		

				<div class="form-group">
					<label for="cpideliver" class="col-sm-4 control-label text-right">Receiver</label>
					<div class="col-sm-6">
						<input type="text" name="cpideliver" class="form-control" id="cpideliver" value="<?=@$prcode?>" readonly>
					</div>
				</div>
				<div class="form-group">
					<label for="cpideliver" class="col-sm-4 control-label text-right">File Upload</label>
					<div class="col-sm-6">
						<input type="file" name="files[]" class="form-control" id="files" multiple="true" required="">
					</div>
				</div>	
			</div>	
		</fieldset>
		<div class="form-footer">
			<div class="form-group text-center">
				<button type="submit" id="save" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
				<button type="button" id="update" class="btn btn-primary" style="display:none;"><i class="fa fa-check-circle"></i> Update</button>
			</div>	
		</div>
	</form>
</div>