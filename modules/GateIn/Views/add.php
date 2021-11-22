<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<?php
if(isset($data) && ($data!='')) {
	$codate = date('d/m/Y',strtotime($data['codate']));
	$coexpdate = date('d/m/Y',strtotime($data['coexpdate']));
}
?>


<div class="content">
	<div class="main-header">
		<h2><?=$page_title;?></h2>
		<em><?=$page_subtitle;?></em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> <?=((isset($act))&&($act!="")?$act:"")?> Gate In</h3>
			</div>
			<div class="widget-content">

				<?php if(isset($validasi)):?>
					<div class="alert alert-danger">
						<?=$validasi; ?>
					</div>
				<?php endif; ?>

				<?php if(isset($message)): ?>
					<p class="alert alert-danger">
						<?php echo $message;?>
					</p>
				<?php endif;?>
				<div id="alert">
					
				</div>
				<form id="fGateIns" class="form-horizontal" role="form">
					<?= csrf_field() ?>
					<fieldset>
					<table class="tbl-form" width="100%">
						<!-- 9 kolom -->
					<tbody>
						<tr>
							<td class="text-right" width="130">Container No :</td>
							<td><input type="text" name="crno" class="form-control" id="crno" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">PraIn No :</td>
							<td colspan="3"><input type="text" name="cpiorderno" id="cpiorderno" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Principal :</td>
							<td><input type="text" name="cpopr" class="form-control" id="cpopr" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">EIR In</td>
							<td colspan="3"><input type="text" name="cpieir" id="cpieir" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Customer :</td>
							<td><input type="text" name="cpcust" class="form-control" id="cpcust" value="<?='';?>"></td>
							<td class="text-right">ID Code :</td>
							<td ><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Ref In No # :</td>
							<td colspan="3"><input type="text" name="cpiefin" id="cpiefin" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Length :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Height :</td>
							<td ><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Date In :</td>
							<td colspan="3"><input type="text" name="cpitgl" id="cpitgl" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Type :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Term :</td>
							<td ><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Time In</td>
							<td colspan="3"><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130"></td>
							<td></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Lift Off Charge :</td>
							<td width="60">
								<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="" id="" value="0">
								<span></span></label>
							</td>
							<td width="80" class="text-right">Paid :</td>
							<td >
								<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="" id="" value="0">
								<span></span></label>								
							</td>							
						</tr>
						<tr>
							<td class="text-right" width="130"></td>
							<td></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Receipt No</td>
							<td colspan="3"><input type="text" name="cpireceptno" id="cpireceptno" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Depot :</td>
							<td><?=depo_dropdown();?></td>
							<td ></td>
							<td ></td>
							<td class="text-right">F/E :</td>
							<td colspan="3">
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="cpife" id="cpife" value="1">
								<span><i></i>Full</span>
								</label>
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="cpife" id="cpife" value="0">
								<span><i></i>Empty</span>
								</label>								
							</td>
						</tr>
						<tr>
							<td class="text-right" width="130">Sub Depot :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Ex Cargo :</td>
							<td colspan="3"><input type="text" name="cpicargo" id="cpicargo" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130"></td>
							<td></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Ex Vessel :</td>
							<td colspan="3"><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130"></td>
							<td></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Voyage :</td>
							<td colspan="3"><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130"></td>
							<td></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Vessel Operator :</td>
							<td colspan="3"><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Origin Port :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Redeliverer :</td>
							<td colspan="3"><input type="text" name="cpideliver" id="cpideliver" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Discharge Date :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td ></td>
							<td ></td>
							<td ></td>
							<td ></td>
						</tr>
<!-- 						<tr>
							<td class="text-right" width="130">Trucker :</td>
							<td><select name="" class="form-control">
								<option value="">--select--</option>
							</select></td>
							<td ></td>
							<td ></td>

						</tr> -->
						<tr>
							<td class="text-right" width="130">Vehicle ID :</td>
							<td><input type="text" name="cpinopol" class="form-control" id="cpinopol" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Condition :</td>
							<td colspan="3"><input type="text" name="condition" id="condition" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Driver :</td>
							<td><input type="text" name="cpidriver" class="form-control" id="cpidriver" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Cleaning :</td>
							<td colspan="3"><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Activity Status :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td ></td>
							<td ></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="5">
								<?php if(isset($act)&&($act=='view')):?>
								<!-- <button type="button" id="updateData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>&nbsp; -->
								<button type="button" id="cancel" class="btn btn-default"><i class="fa fa-ban"></i> Back</button>								
								<?php else: ?>
								<button type="submit" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
								<button type="button" id="cancel" class="btn btn-default text-right"><i class="fa fa-ban"></i> Cancel</button>								
								<?php endif; ?>
							</td>
						</tr>						
					</tbody>
				</table>
			</fieldset>
		</form>
	</div>
</div>

<?= $this->endSection();?>

<!-- Load JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\GateIn\Views\js'); ?>

<?= $this->endSection();?>