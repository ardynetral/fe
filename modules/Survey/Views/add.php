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
		<!-- <?php print_r(@$details['datas'])?> -->
		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> <?=((isset($act))&&($act!="")?$act:"")?> Survey </h3>
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
					<?php $readonly = (@$crno != '')?'readonly':'';?>
					<fieldset>
					<table class="tbl-form" width="100%">
						<!-- 9 kolom -->
						<input type="hidden" name="UPDATE_ID" value="<?=@$crno;?>">
					<tbody>
						<tr>
							<td class="text-right" width="130">Container No :</td>
							<td><input type="text" name="CRNO" class="form-control" id="CRNO" value="<?=@$crno;?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">PraIn No :</td>
							<td ><input <?php echo $readonly;?> type="text" name="CPIPRANO" id="CPIPRANO" class="form-control" value="<?=@$details['datas']['cpiorderno'];?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Principal :</td>
							<td><input  <?php echo $readonly;?> type="text" name="PRCODE" class="form-control" id="PRCODE" value="<?=@$details['datas']['prcode'];?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">EIR In</td>
							<td ><input  <?php echo $readonly;?> type="text" name="CPIEIR" id="CPIEIR" class="form-control" value="<?=@$details['datas']['cpieir'];?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Customer :</td>
							<td><input  <?php echo $readonly;?> type="text" name="CUCODE" class="form-control" id="CUCODE" value="<?=@$details['datas']['cucode'];?>"></td>
							<td></td>
							<td></td>							
							<td class="text-right">Ref In No # :</td>
							<td ><input  <?php echo $readonly;?> type="text" name="CPIREFIN" id="CPIREFIN" class="form-control" value="<?=@$details['datas']['cpirefin'];?>"></td>
						</tr>
						<tr>
							<td class="text-right">ID Code :</td>
							<td ><input  <?php echo $readonly;?> type="text" name="CCCODE" class="form-control" id="CCCODE" value="<?=@$details['datas']['cccode'];?>"></td>
							<td></td>
							<td></td>
							<td class="text-right">Date In :</td>
							<td ><input  <?php echo $readonly;?> type="text" name="CPITGL" id="CPITGL" class="form-control" value="<?=@$details['datas']['cpitgl'];?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Type :</td>
							<td><input  <?php echo $readonly;?> type="text" name="CTCODE" class="form-control" id="CTCODE" value="<?=@$details['datas']['ctcode'];?>"></td>
							<td class="text-right"></td>
							<td ></td>
							<td class="text-right">Time In</td>
							<td ><input  <?php echo $readonly;?> type="text" name="CPIJAM" id="CPIJAM" class="form-control" value="<?=@$details['datas']['cpijam'];?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Length :</td>
							<td><input type="text" name="CCLENGTH" class="form-control" id="CCLENGTH" value="<?=@$details['datas']['cclength'];?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Lift Off Charge :</td>
							<td >
								<label class="control-inline fancy-checkbox custom-color-green">
								<input  <?php echo $readonly;?> type="checkbox" name="CPICHRGBB" id="CPICHRGBB" value="<?=@$details['datas']['cpichrgbb'];?>" <?php echo $checked=(@$details['datas']['cpichrgbb']==1)?'checked="checked"':'' ?> >
								<span></span></label>
								Paid&nbsp;:&nbsp;
								<label class="control-inline fancy-checkbox custom-color-green">
								<input  <?php echo $readonly;?> type="checkbox" name="CPIPAIDBB" id="CPIPAIDBB" value="<?=@$details['datas']['cpipaidbb'];?>" <?php echo $checked=(@$details['datas']['cpipaidbb']==1)?'checked="checked"':'' ?> >
								<span></span></label>								
							</td>							
						</tr>
						<tr>
							<td class="text-right">Height :</td>
							<td ><input  <?php echo $readonly;?> type="text" name="CCHEIGHT" class="form-control" id="CCHEIGHT" value="<?=@$details['datas']['ccheight'];?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Receipt No</td>
							<td ><input  <?php echo $readonly;?> type="text" name="CPIRECEPTNO" id="CPIRECEPTNO" class="form-control" value="<?=@$details['datas']['cpireceptno'];?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="">CDP :</td>
							<td colspan="">
								<label class="control-inline fancy-checkbox custom-color-green">
								<input  <?php echo $readonly;?> type="checkbox" name="CRCDP" id="CRCDP" value="<?=@$details['datas']['crcdp'];?>" <?php echo $checked=(@$details['datas']['crcdp']==1)?'checked="checked"':'' ?> >
								<span></span></label>
								ACEP :
								<label class="control-inline fancy-checkbox custom-color-green">
								<input  <?php echo $readonly;?> type="checkbox" name="CRACEP" id="CRACEP" value="<?=@$details['datas']['cracep'];?>" <?php echo $checked=(@$details['datas']['crcdp']==1)?'checked="checked"':'' ?> >
								<span></span></label>
								CSC :
								<label class="control-inline fancy-checkbox custom-color-green">
								<input  <?php echo $readonly;?> type="checkbox" name="CRCSC" id="CRCSC" value="<?=@$details['datas']['crcsc'];?>" <?php echo $checked=(@$details['datas']['crcdp']==1)?'checked="checked"':'' ?> >
								<span></span></label>								
							</td>
							<td></td>
							<td></td>
							<td class="text-right">F/E :</td>
							<td >
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input  <?php echo $readonly;?> type="radio" name="CPIFE" id="CPIFE" value="1" <?php echo $checked=(@$details['datas']['cpife']==1)?'checked="checked"':'' ?> >
								<span><i></i>Full</span>
								</label>
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input  <?php echo $readonly;?> type="radio" name="CPIFE" id="CPIFE" value="0" <?php echo $checked=(@$details['datas']['cpife']!=null && @$details['datas']['cpife']==0)?'checked="checked"':'' ?>>
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
								<input  <?php echo $readonly;?> type="radio" name="CPITERM" id="CY" value="CY" <?php echo $checked=(@$details['datas']['cpiterm']=='CY')?'checked="checked"':''; ?> >
								<span><i></i>CY</span>
								</label>
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input  <?php echo $readonly;?> type="radio" name="CPITERM" id="MTY" value="MTY" <?php echo $checked=(@$details['datas']['cpiterm']=='MTY')?'checked="checked"':''; ?> >
								<span><i></i>MTY</span>
								</label>	
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input  <?php echo $readonly;?> type="radio" name="CPITERM" id="CFS" value="CFS" <?php echo $checked=(@$details['datas']['cpiterm']=='CFS')?'checked="checked"':''; ?> >
								<span><i></i>CFS</span>
								</label>
							</td>
						</tr>						
						<tr>
							<td class="text-right" width="130">Weight (Kgs) :</td>
							<td><input type="text" name="CRWEIGHTK" id="CRWEIGHTK" class="form-control" value="<?=@$details['datas']['crweightk'];?>"></td>
							<td class="text-right">Weight (Lbs) :</td>
							<td ><input type="text" name="CRWEIGHTL" id="CRWEIGHTL" class="form-control" value="<?=@$details['datas']['crweightl'];?>"></td>
							<td class="text-right">Origin Port :</td>
							<td ><input <?php echo $readonly;?> type="text" name="CPIDISH" id="CPIDISH" class="form-control" value="<?=@$details['datas']['cpidish'];?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Tare (Kgs) :</td>
							<td><input type="text" name="CRTARAK" id="CRTARAK" class="form-control" value="<?=@$details['datas']['crtarak'];?>"></td>
							<td class="text-right">Tare (Lbs) :</td>
							<td ><input type="text" name="CRTARAL" id="CRTARAL" class="form-control" value="<?=@$details['datas']['crtaral'];?>"></td>
							<td class="text-right">Discharge Date :</td>
							<td ><input type="text" name="CPIDISDAT" id="CPIDISDAT" class="form-control" value="<?=@$details['datas']['cpidisdat'];?>">		
							</td>
						</tr>
						<tr>
							<td class="text-right" width="130">Netto (Kgs) :</td>
							<td><input type="text" name="CRNETK" id="CRNETK" class="form-control" value="<?=@$details['datas']['crnetk'];?>"></td>
							<td class="text-right">Netto (Lbs) :</td>
							<td ><input type="text" name="CRNETL" id="CRNETL" class="form-control" value="<?=@$details['datas']['crnetl'];?>"></td>
							<td class="text-right"></td>
							<td ></td>
						</tr>	
						<tr>
							<td class="text-right" width="130">Volume (Cbm) :</td>
							<td><input type="text" name="CRVOL" id="CRVOL" class="form-control" value="<?=@$details['datas']['crvol'];?>"></td>
							<td class="text-right">Material :</td>
							<td ><input type="text" name="MTCODE1" id="MTCODE1" class="form-control" value="<?=@$details['datas']['mtcode1'];?>"></td>
							<td class="text-right">Ex Cargo :</td>
							<td ><input <?php echo $readonly;?> type="text" name="CPICARGO" id="CPICARGO" class="form-control" value="<?=@$details['datas']['cpicargo'];?>"></td>
						</tr>	
						<tr>
							<td class="text-right" width="130">Manufacture :</td>
							<td><input type="text" name="CRMANUF" id="CRMANUF" class="form-control" value="<?=@$details['datas']['crmanuf'];?>"></td>
							<td class="text-right">Manufacture Date :</td>
							<td ><input type="text" name="MANUFDATE" id="MANUFDATE" class="form-control" value="<?=@$details['datas']['manufdate'];?>"></td>
							<td class="text-right">Seal No :</td>
							<td ><input <?php echo $readonly;?> type="text" name="CPISEAL" id="CPISEAL" class="form-control" value="<?=@$details['datas']['cpiseal'];?>"></td>
						</tr>	
						<tr>
							<!-- <td class="text-right" width="130">Depo :</td>
							<td><input type="text" name="dpcode" class="form-control" id="DPCODE" value="<?=@$details['datas']['dpcode'];?>"></td> -->
							<td ></td>
							<td ></td>
							<td class="text-right">Ex Vessel-Voyage :</td>
							<td ><input <?php echo $readonly;?> type="text" name="CPIVOYID" id="CPIVOYID" class="form-control" value="<?=@$details['datas']['cpivoyid'];?>"></td>
						</tr>
						<tr>
							<!-- <td class="text-right" width="130">Sub Depo :</td>
							<td><input type="text" name="SDCODE" class="form-control" id="SDCODE" value="<?=@$details['datas']['sdcode'];?>"></td> -->
							<td ></td>
							<td ></td>
							<td class="text-right">Vessel Operator :</td>
							<td ><input <?php echo $readonly;?> type="text" name="CPIVES" id="CPIVES" class="form-control" value="<?=@$details['datas']['cpives'];?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Block :</td>
							<td><input type="text" name="CRPOS" class="form-control" id="CRPOS" value="<?=@$details['datas']['crpos'];?>"></td>
							<td class="text-right">Bay :</td>
							<td ><input type="text" name="CRBAY" id="CRBAY" class="form-control" value="<?=@$details['datas']['crbay'];?>"></td>
							<td class="text-right">Redeliver :</td>
							<td ><input <?php echo $readonly;?> type="text" name="CPIDELIVER" id="CPIDELIVER" class="form-control" value="<?=@$details['datas']['cpideliver'];?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Row :</td>
							<td><input type="text" name="CRROW" class="form-control" id="CRROW" value="<?=@$details['datas']['crrow'];?>"></td>
							<td class="text-right">Tier :</td>
							<td ><input <?php echo $readonly;?> type="text" name="CRTIER" id="CRTIER" class="form-control" value="<?=@$details['datas']['crtier'];?>"></td>
							<td class="text-right">DPP/Non DPP :</td>
							<td ><input <?php echo $readonly;?> type="text" name="CPIDPP" id="CPIDPP" class="form-control" value="<?=@$details['datas']['cpidpp'];?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Condition Box :</td>
							<td>
								<select name="CRLASTCOND" class="input"  >
                                  <option value="">Select Value</option>
                                  <option value='AX' <?php echo $select = (@$details['datas']['crlastcond']=='AX')?'selected="selected"':''; ?>>AX</option>
                                  <option value='AC' <?php echo $select = (@$details['datas']['crlastcond']=='AC')?'selected="selected"':''; ?>>AC</option>
                                  <option value='AU' <?php echo $select = (@$details['datas']['crlastcond']=='AU')?'selected="selected"':''; ?>>AU</option>
                                  <option value='DN' <?php echo $select = (@$details['datas']['crlastcond']=='DN')?'selected="selected"':''; ?>>DN</option>
                                  <option value='DJ' <?php echo $select = (@$details['datas']['crlastcond']=='DJ')?'selected="selected"':''; ?>>DJ</option>
                                  <option>SVCOND</option>
                                </select>
							</td>
							<td class="text-right">Condition(Engine) :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?=@$details['datas']['cccode'];?>"></td>
							<td class="text-right">Clearence No :</td>
							<td ><input  <?php echo $readonly;?> type="text" name="LECLEARNO" id="LECLEARNO" class="form-control" value="<?=@$details['datas']['leclearno'];?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Activity Status :</td>
							<td><input  <?php echo $readonly;?> type="text" name="CRLASTACT" class="form-control" id="CRLASTACT" value="<?=@$details['datas']['crlastact'];?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Contract Code :</td>
							<td ><input  <?php echo $readonly;?> type="text" name="LECONTRACTNO" id="LECONTRACTNO" class="form-control" value="<?=@$details['datas']['prcode'];?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Trucker :</td>
							<td><input  <?php echo $readonly;?> type="text" name="CUTYPE" class="form-control" id="CUTYPE" value="<?=@$details['datas']['cutype'];?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Driver :</td>
							<td ><input  <?php echo $readonly;?> type="text" name="CPIDRIVER" id="CPIDRIVER" class="form-control" value="<?=@$details['datas']['cpidriver'];?>"></td>
						</tr>
						<?php if(@$details['datas']['crlastact'] == 'WS'){?>
						<tr>
							<th class="text-right">Survey Result :</th>
							<td colspan="3"></td>
							<td class="text-right">Vehicle ID :</td>
							<td ><input <?php echo $readonly;?> type="text" name="CPINOPOL" id="CPINOPOL" class="form-control" value="<?=@$details['datas']['cpinopol'];?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Survey Date :</td>
							<td><input type="text" name="SVSURDAT" class="form-control" id="SVSURDAT" value="<?=@$details['datas']['svsurdat'];?>"></td>
							<td ></td>
							<td ></td>
							<td class="text-right">Remark :</td>
							<td ><textarea <?php echo $readonly;?> name="CPIREMARK" id="CPIREMARK" rows="2" class="form-control" style="resize: none!important;"><?=@$details['datas']['cpiremark'];?></textarea></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Condition :</td>
							<td><input type="text" name="SVCOND" class="form-control" id="SVCOND" value="<?=@$details['datas']['svcond'];?>"></td>
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
							<td ><textarea name="CPINOTES" rows="2" class="form-control" style="resize: none!important;"><?=@$details['datas']['cpinotes'];?></textarea></td>
						</tr>
						<?php }?>
						<tr>
							<td></td>
							<td colspan="5">
								<?php if(isset($act)&&($act=='view')):?>
								<!-- <button type="button" id="updateData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>&nbsp; -->
								<a href="<?=site_url('survey')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
								<?php else: ?>
								<button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
								<a id="cancel" href="<?=site_url('survey')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
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