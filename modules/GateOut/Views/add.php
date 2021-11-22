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
				<h3><i class="fa fa-edit"></i> <?=((isset($act))&&($act!="")?$act:"")?> <?=$page_title;?></h3>
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
				<form id="fContract" class="form-horizontal" role="form">
					<?= csrf_field() ?>
					<fieldset>
					<table class="tbl-form" width="100%">
						<!-- 9 kolom -->
					<tbody>
						<tr>
							<td class="text-right" width="130">Container No :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">PraOut Ref :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Principal :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">EIR Out</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
						</tr>
							<td class="text-right" width="130">Customer :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">ID Code :</td>
							<td ><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Ref Out No # :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Length :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Height :</td>
							<td ><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Date Out :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Type :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Term :</td>
							<td ><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Time Out</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="">CDP :</td>
							<td colspan="2">
								<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="" id="" value="0">
								<span></span></label>
								ACEP :
								<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="" id="" value="0">
								<span></span></label>
								CSC :
								<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="" id="" value="0">
								<span></span></label>								
							</td>
							<td ></td>
							<td ></td>
							<td ></td>
						</tr>					
						<tr>
							<td class="text-right" width="130">Weight (Kgs) :</td>
							<td><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td class="text-right">Weight (Lbs) :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Tare (Kgs) :</td>
							<td><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td class="text-right">Tare (Lbs) :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td class="text-right">F/E :</td>
							<td >
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
							<td class="text-right" width="130">Netto (Kgs) :</td>
							<td><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td class="text-right">Netto (Lbs) :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td class="text-right">Destination Port :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
						</tr>	
						<tr>
							<td class="text-right" width="130">Volume (Cbm) :</td>
							<td><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td class="text-right">Material :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td class="text-right">ETD :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
						</tr>	
						<tr>
							<td class="text-right" width="130">Manufacture :</td>
							<td><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td class="text-right">Manufacture Date :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td class="text-right">Vessel :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
						</tr>													
						<tr>
							<td class="text-right" width="130">Depot :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Voyage :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Sub Depot :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Vessel Operator :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Repo Type :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">To :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Receiver :</td>
							<td ><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
						</tr>						
						<tr>
							<td class="text-right" width="130">Condition (Box) :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right" width="130">Condition (Engine) :</td>
							<td ><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Activity Status :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Trucker :</td>
							<td ><select name="" class="form-control">
								<option value="">--select--</option>
							</select></td>
						</tr>						
						<tr>
							<td class="text-right" width="130"></td>
							<td></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Vehicle ID :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
						</tr>
						<tr>
							<th class="text-right" width="130">Survey out</th>
							<td></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Driver :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right">Inspector Date :</td>
							<td ><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Remark :</td>
							<td ><textarea name="" rows="2" class="form-control" style="resize: none!important;"></textarea></td>

						</tr>
						<tr>
							<td class="text-right" width="130">Inspector :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
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
								<button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
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