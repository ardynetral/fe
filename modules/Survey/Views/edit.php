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
				<h3><i class="fa fa-edit"></i> <?=((isset($act))&&($act!="")?$act:"")?> Survey</h3>
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
				<form id="form_input" class="form-horizontal" role="form">
					<?= csrf_field() ?>
					<fieldset>
					<table class="tbl-form" width="100%">
						<!-- 9 kolom -->
					<tbody>
						<tr>
							<td class="text-right" width="130">Container No :</td>
							<td><input type="text" name="CRNO" class="form-control" id="CRNO" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">PraIn No :</td>
							<td ><input type="text" name="CPIPRANO" id="CPIPRANO" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Principal :</td>
							<td><input type="text" name="PRCODE" class="form-control" id="PRCODE" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">EIR In</td>
							<td ><input type="text" name="CPIEIR" id="CPIEIR" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Customer :</td>
							<td><input type="text" name="CUCODE" class="form-control" id="CUCODE" value="<?='';?>"></td>
							<td></td>
							<td></td>							
							<td class="text-right">Ref In No # :</td>
							<td ><input type="text" name="CPIREFIN" id="CPIREFIN" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right">ID Code :</td>
							<td ><input type="text" name="CCCODE" class="form-control" id="CCCODE" value="<?='';?>"></td>
							<td></td>
							<td></td>
							<td class="text-right">Date In :</td>
							<td ><input type="text" name="CPITGL" id="CPITGL" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Type :</td>
							<td><input type="text" name="CTCODE" class="form-control" id="CTCODE" value="<?='';?>"></td>
							<td class="text-right"></td>
							<td ></td>
							<td class="text-right">Time In</td>
							<td ><input type="text" name="CPIJAM" id="CPIJAM" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Length :</td>
							<td><input type="text" name="CCLENGTH" class="form-control" id="CCLENGTH" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Lift Off Charge :</td>
							<td >
								<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="CPICHRGBB" id="CPICHRGBB" value="0">
								<span></span></label>
								Paid&nbsp;:&nbsp;
								<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="CPIPAIDBB" id="CPIPAIDBB" value="0">
								<span></span></label>								
							</td>							
						</tr>
						<tr>
							<td class="text-right">Height :</td>
							<td ><input type="text" name="CCHEIGHT" class="form-control" id="CCHEIGHT" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Receipt No</td>
							<td ><input type="text" name="CPIRECEPTNO" id="CPIRECEPTNO" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="">CDP :</td>
							<td colspan="">
								<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="CRCDP" id="CRCDP" value="0">
								<span></span></label>
								ACEP :
								<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="CRACEP" id="CRACEP" value="0">
								<span></span></label>
								CSC :
								<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="CRCSC" id="CRCSC" value="0">
								<span></span></label>								
							</td>
							<td></td>
							<td></td>
							<td class="text-right">F/E :</td>
							<td >
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="CPIFE" id="CPIFE" value="1">
								<span><i></i>Full</span>
								</label>
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="CPIFE" id="CPIFE" value="0">
								<span><i></i>Empty</span>
								</label>								
							</td>
						</tr>
						<tr>
							<td class="text-right" width=""></td>
							<td colspan="">								
							</td>
							<td></td>
							<td></td>
							<td class="text-right">Term :</td>
							<td >
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="CPITERM" id="CY" value="CY">
								<span><i></i>CY</span>
								</label>
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="CPITERM" id="MTY" value="MTY">
								<span><i></i>MTY</span>
								</label>	
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="CPITERM" id="CFS" value="CFS">
								<span><i></i>CFS</span>
								</label>
							</td>
						</tr>						
						<tr>
							<td class="text-right" width="130">Weight (Kgs) :</td>
							<td><input type="text" name="CRWEIGHTK" id="CRWEIGHTK" class="form-control" value="<?='';?>"></td>
							<td class="text-right">Weight (Lbs) :</td>
							<td ><input type="text" name="CRWEIGHTL" id="CRWEIGHTL" class="form-control" value="<?='';?>"></td>
							<td class="text-right">Origin Port :</td>
							<td ><input type="text" name="CPIDISH" id="CPIDISH" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Tare (Kgs) :</td>
							<td><input type="text" name="CRTARAK" id="CRTARAK" class="form-control" value="<?='';?>"></td>
							<td class="text-right">Tare (Lbs) :</td>
							<td ><input type="text" name="CRTARAL" id="CRTARAL" class="form-control" value="<?='';?>"></td>
							<td class="text-right">Discharge Date :</td>
							<td ><input type="text" name="CPIDISDAT" id="CPIDISDAT" class="form-control" value="<?='';?>">		
							</td>
						</tr>
						<tr>
							<td class="text-right" width="130">Netto (Kgs) :</td>
							<td><input type="text" name="CRNETK" id="CRNETK" class="form-control" value="<?='';?>"></td>
							<td class="text-right">Netto (Lbs) :</td>
							<td ><input type="text" name="CRNETL" id="CRNETL" class="form-control" value="<?='';?>"></td>
							<td class="text-right"></td>
							<td ></td>
						</tr>	
						<tr>
							<td class="text-right" width="130">Volume (Cbm) :</td>
							<td><input type="text" name="CRVOL" id="CRVOL" class="form-control" value="<?='';?>"></td>
							<td class="text-right">Material :</td>
							<td ><input type="text" name="MTCODE1" id="MTCODE1" class="form-control" value="<?='';?>"></td>
							<td class="text-right">Ex Cargo :</td>
							<td ><input type="text" name="CPICARGO" id="CPICARGO" class="form-control" value="<?='';?>"></td>
						</tr>	
						<tr>
							<td class="text-right" width="130">Manufacture :</td>
							<td><input type="text" name="CRMANUF" id="CRMANUF" class="form-control" value="<?='';?>"></td>
							<td class="text-right">Manufacture Date :</td>
							<td ><input type="text" name="MANUFDATE" id="MANUFDATE" class="form-control" value="<?='';?>"></td>
							<td class="text-right">Seal No :</td>
							<td ><input type="text" name="CPISEAL" id="CPISEAL" class="form-control" value="<?='';?>"></td>
						</tr>	
						<tr>
							<!-- <td class="text-right" width="130">Depo :</td>
							<td><input type="text" name="DPCODE" class="form-control" id="DPCODE" value="<?='';?>"></td> -->
							<td ></td>
							<td ></td>
							<td class="text-right">Ex Vessel-Voyage :</td>
							<td ><input type="text" name="CPIVOYID" id="CPIVOYID" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<!-- <td class="text-right" width="130">Sub Depo :</td>
							<td><input type="text" name="SDCODE" class="form-control" id="SDCODE" value="<?='';?>"></td> -->
							<td ></td>
							<td ></td>
							<td class="text-right">Vessel Operator :</td>
							<td ><input type="text" name="CPIVES" id="CPIVES" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Block :</td>
							<td><input type="text" name="CRPOS" class="form-control" id="CRPOS" value="<?='';?>"></td>
							<td class="text-right">Bay :</td>
							<td ><input type="text" name="CRBAY" id="CRBAY" class="form-control" value="<?='';?>"></td>
							<td class="text-right">Redeliver :</td>
							<td ><input type="text" name="CPIDELIVER" id="CPIDELIVER" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Row :</td>
							<td><input type="text" name="CRROW" class="form-control" id="CRROW" value="<?='';?>"></td>
							<td class="text-right">Tier :</td>
							<td ><input type="text" name="CRTIER" id="CRTIER" class="form-control" value="<?='';?>"></td>
							<td class="text-right">DPP/Non DPP :</td>
							<td ><input type="text" name="CPIDPP" id="CPIDPP" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Condition Box :</td>
							<td>
								<select name="CRLASTCOND" class="input"  >
                                  <option value="">Select Value</option>
                                  <option value='AX'>AX</option>
                                  <option value='AC'>AC</option>
                                  <option value='AU'>AU</option>
                                  <option value='DN'>DN</option>
                                  <option value='DJ'>DJ</option>
                                  <option>SVCOND</option>
                                </select>
							</td>
							<td class="text-right">Condition(Engine) :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td class="text-right">Clearence No :</td>
							<td ><input type="text" name="LECLEARNO" id="LECLEARNO" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Activity Status :</td>
							<td><input type="text" name="CRLASTACT" class="form-control" id="CRLASTACT" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Contract Code :</td>
							<td ><input type="text" name="LECONTRACTNO" id="LECONTRACTNO" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Trucker :</td>
							<td><input type="text" name="CUTYPE" class="form-control" id="CUTYPE" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Driver :</td>
							<td ><input type="text" name="CPIDRIVER" id="CPIDRIVER" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<th class="text-right">Survey Result :</th>
							<td colspan="3"></td>
							<td class="text-right">Vehicle ID :</td>
							<td ><input type="text" name="CPINOPOL" id="CPINOPOL" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Survey Date :</td>
							<td><input type="text" name="SVSURDAT" class="form-control" id="SVSURDAT" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Remark :</td>
							<td ><textarea name="CPIREMARK" id="CPIREMARK" rows="2" class="form-control" style="resize: none!important;"></textarea></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Condition :</td>
							<td><input type="text" name="SVCOND" class="form-control" id="SVCOND" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right"></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Surveyor :</td>
							<td><input type="text" name="SYID" class="form-control" id="" value="<?= $uname;?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Notes :</td>
							<td ><textarea name="CPINOTES" rows="2" class="form-control" style="resize: none!important;"></textarea></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="5">
								<?php if(isset($act)&&($act=='view')):?>
								<!-- <button type="button" id="updateData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>&nbsp; -->
								<a href="<?=site_url('survey')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
								<?php else: ?>
								<button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
								<a href="<?=site_url('survey')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
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

	<?= $this->include('\Modules\Survey\Views\js'); ?>

<?= $this->endSection();?>