<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<?php
// if(isset($data) && ($data!='')) {
// 	$codate = date('d/m/Y',strtotime($data['codate']));
// 	$coexpdate = date('d/m/Y',strtotime($data['coexpdate']));
// }
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
							<td>
								<input type="text" name="crno" class="form-control" id="crno" value="<?=$data['crno'];?>">
								<div class="err-crno" style="display:none;padding-top:5px;color:red;"></div>
							</td>
							<td ></td>
							<td ></td>
							<td class="text-right">PraIn No :</td>
							<td colspan="3"><input type="text" name="cpiorderno" id="cpiorderno" class="form-control" value="<?=$data['cpiorderno'];?>" readonly></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Principal :</td>
							<td><input type="text" name="cpopr" class="form-control" id="cpopr" value="<?=$data['cpopr'];?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">EIR In</td>
							<td colspan="3"><input type="text" name="cpieir" id="cpieir" class="form-control" value="<?=$data['cpieir'];?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Customer :</td>
							<td><input type="text" name="cpcust" class="form-control" id="cpcust" value="<?=$data['cpcust'];?>"></td>
							<td class="text-right">ID Code :</td>
							<td ><input type="text" name="cccode" class="form-control" id="cccode" value="<?=$data['cccode'];?>"></td>
							<td class="text-right">Ref In No # :</td>
							<td colspan="3"><input type="text" name="cpirefin" id="cpirefin" class="form-control" value="<?=$data['cpirefin'];?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Length :</td>
							<td><input type="text" name="cclength" class="form-control" id="cclength" value="<?=$data['cclength'];?>"></td>
							<td class="text-right">Height :</td>
							<td ><input type="text" name="ccheight" class="form-control" id="ccheight" value="<?=$data['ccheight'];?>"></td>
							<td class="text-right">Date In :</td>
							<td colspan="3"><input type="text" name="cpipratgl" id="cpipratgl" class="form-control" value="<?=$data['cpipratgl'];?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Type :</td>
							<td><input type="text" name="ctcode" class="form-control" id="ctcode" value="<?=$data['ctcode'];?>"></td>
							<td class="text-right">Term :</td>
							<td ><input type="text" name="cpiterm" class="form-control" id="cpiterm" value="<?=$data['cpiterm'];?>"></td>
							<td class="text-right">Time In</td>
							<td colspan="3"><input type="text" name="cpijam" id="cpijam" class="form-control" value="<?=date('H:i:s', strtotime($data['cpijam']));?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130"></td>
							<td></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Lift Off Charge :</td>
							<td width="60">
								<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="liftoffcharge" id="liftoffcharge" value="0" checked>
								<span></span></label>
							</td>
							<td width="80" class="text-right">Paid :</td>
							<td >
								<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="cpipaidbb" id="cpipaidbb" value="<?=$data['cpipaidbb']?>" <?=((isset($data['cpipaidbb']))&&($data['cpipaidbb']=="1")?"checked":"")?>>
								<span></span></label>								
							</td>							
						</tr>
						<tr>
							<td class="text-right" width="130"></td>
							<td></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Receipt No</td>
							<td colspan="3"><input type="text" name="cpireceptno" id="cpireceptno" class="form-control" value="<?=$data['cpireceptno'];?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Depot :</td>
							<td><?=depo_dropdown("000");?></td>
							<td ></td>
							<td ></td>
							<td class="text-right">F/E :</td>
							<td colspan="3">
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="cpife" id="cpife" value="1">
								<span><i></i>Full</span>
								</label>
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="cpife" id="cpife" value="0" checked>
								<span><i></i>Empty</span>
								</label>								
							</td>
						</tr>
						<tr>
							<td class="text-right" width="130" style="display:none;">Sub Depot :</td>
							<td style="display:none;"><input type="text" name="spdepo" class="form-control" id="spdepo" value="<?=$data['spdepo'];?>"></td>
							<td ></td>
							<td ></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Ex Cargo :</td>
							<td colspan="3"><input type="text" name="cpicargo" id="cpicargo" class="form-control" value="<?=$data['cpicargo'];?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130"></td>
							<td></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Ex Vessel :</td>
							<td colspan="3"><input type="text" name="vesid" id="vesid" class="form-control" value="<?=$data['vesid'];?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130"></td>
							<td></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Voyage :</td>
							<td colspan="3">
								<input type="text" name="voyno" id="voyno" class="form-control" value="<?=$data['voyno'];?>">
								<input type="hidden" name="cpivoy" id="cpivoy" class="form-control" value="<?=$data['cpivoy'];?>">
							</td>
						</tr>
						<tr>
							<td class="text-right" width="130"></td>
							<td></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Vessel Operator :</td>
							<td colspan="3"><input type="text" name="vesopr" id="vesopr" class="form-control" value="<?=$data['vesopr'];?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Origin Port :</td>
							<td><input type="text" name="poport" class="form-control" id="poport" value="<?=$data['poport'];?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Redeliverer :</td>
							<td colspan="3"><input type="text" name="cpideliver" id="cpideliver" class="form-control" value="<?=$data['cpideliver'];?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Discharge Date :</td>
							<td><input type="text" name="cpidisdat" class="form-control" id="cpidisdat" value="<?=$data['cpidisdat'];?>"></td>
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
							<td><input type="text" name="cpinopol" class="form-control" id="cpinopol" value="<?=$data['cpinopol'];?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Condition :</td>
							<td colspan="3"><input type="text" name="crlastcond" id="crlastcond" class="form-control" value="<?=$data['crlastcond'];?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Driver :</td>
							<td><input type="text" name="cpidriver" class="form-control" id="cpidriver" value="<?=$data['cpidriver'];?>"></td>
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

					</tbody>
				</table>
			</fieldset>
			<div class="row">
				<div class="col-sm-12 text-center">
					<?php if(isset($act)&&($act=='view')):?>
					<!-- <button type="button" id="updateData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>&nbsp; -->
					<a href="<?=site_url('gatein')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
					<?php else: ?>
					<button type="submit" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
					<a href="<?=site_url('gatein')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>								
					<?php endif; ?>						
				</div>	
			</div>
		</form>
	</div>
</div>

<?= $this->endSection();?>

<!-- Load JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\GateIn\Views\js'); ?>

<?= $this->endSection();?>