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
		<h2>Contract</h2>
		<em>Contract page</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> <?=((isset($act))&&($act=="view")?"Detail ":"Add ")?>Contract</h3>
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
							<td class="text-right" width="130"><label for="cono" class="text-right">Contract No :</label></td>
							<td><input type="text" name="cono" class="form-control" id="cono" value="<?=@$data['cono'];?>"></td>
							<td colspan="7"></td>
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="prcode">Principal No :</label></td>
							<td><?=principal_dropdown(@$data['prcode']);?></td>
							<td class="text-right" width="130"><label for="codate" class="text-right">Begin :</label></td>
							<td colspan="2">
								<div class="input-group">
								<input type="text" name="codate" id="codate" class="form-control tanggal" required="" value="<?=@$codate?>">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>									
							</td>							
							<td class="text-right" width="130"><label for="coexpdate" class="text-right">Expire :</label></td>
							<td colspan="2">
								<div class="input-group">
								<input type="text" name="coexpdate" id="coexpdate" class="form-control tanggal" required="" value="<?=@$coexpdate;?>">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>									
							</td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="cofre">Free days (In depo) :</label></td>
							<td>
								<input type="text" name="cofree" class="form-control" id="cofree" value="<?=@$data['cofree']?>">
							</td>
							<td class="text-right" width="130"><label for="cofreedn" class="text-right">Free (Repair) :</label></td>
							<td colspan="6">
								<input type="checkbox" name="cofreedn" class="" id="cofreedn" <?=((isset($data['cofreedn'])&&($data['cofreedn']==1)) ? "checked" : "");?>>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<label for="cofreedmg" class="text-right">Free (Damage) :</label>
								<input type="checkbox" name="cofreedmg" class="" id="cofreedmg" <?=((isset($data['cofreedmg'])&&($data['cofreedmg']==1)) ? "checked" : "");?>>
							</td>	
						</tr>						
						<tr>
							<td class="text-right" width="130"><label for="covcurr" class="text-right">Approval/Repair Currency :</label></td>
							<td><?=currency_dropdown('covcurr',@$data['covcurr']);?></td>
							<td colspan="4"></td>
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="covapp" class="text-right">Approval Value :</label></td>
							<td><input type="text" name="covapp" class="form-control" id="covapp" value="<?=@$data['covapp']?>"></td>
						</tr>
						<tr>
							<th width="130">&nbsp;</th>
							<th>Value #1</th>
							<th>Value #2</th>
							<th>Value #3</th>
							<th colspan="3"></th>
						</tr>	
						<tr>
							<td class="text-right" width="130"><label for="cov" class="text-right">Repair Value :</label></td>
							<td><input type="text" name="cov1" class="form-control" id="cov1" value="<?=@$data['cov1']?>"></td>
							<td><input type="text" name="cov2" class="form-control" id="cov2" value="<?=@$data['cov2']?>"></td>
							<td><input type="text" name="cov3" class="form-control" id="cov3" value="<?=@$data['cov3']?>"></td>
							<td colspan="3"></td>							
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="covd" class="text-right">Repair Day :</label></td>
							<td><input type="text" name="covd1" class="form-control" id="covd1" value="<?=@$data['covd1']?>"></td>
							<td><input type="text" name="covd2" class="form-control" id="covd2" value="<?=@$data['covd2']?>"></td>
							<td><input type="text" name="covd3" class="form-control" id="covd3" value="<?=@$data['covd3']?>"></td>
							<td colspan="3"></td>							
						</tr>	
						<tr>
							<td class="text-right" width="130"><label for="colabrtcurr" class="text-right">Labour Rate Currency :</label></td>
							<td><?=currency_dropdown('colabrtcurr',@$data['colabrtcurr']);?></td>
							<td colspan="3"></td>
						</tr>
						<tr>
							<th width="130">&nbsp;</th>
							<th>Box(Dry)</th>
							<th>Box(Reefer)</th>
							<th>Engine</th>
							<th>Chasis</th>
							<th colspan="3"></th>							
						</tr>						
						<tr>
							<td class="text-right" width="130"><label for="colab" class="text-right">Labour Rate :</label></td>
							<td><input type="text" name="colabrtb" class="form-control" id="colabrtb" value="<?=@$data['colabrtb']?>"></td>
							<td><input type="text" name="colabrtbr" class="form-control" id="colabrtbr" value="<?=@$data['colabrtbr']?>"></td>
							<td><input type="text" name="colabrte" class="form-control" id="colabrte" value="<?=@$data['colabrte']?>"></td>
							<td><input type="text" name="colabrtc" class="form-control" id="colabrtc" value="<?=@$data['colabrtc']?>"></td>
							<td colspan="3"></td>							
						</tr>
						<tr>
							<th width="130">&nbsp;</th>
							<th>Currency</th>
							<th>20"</th>
							<th>40"</th>
							<th>45"</th>
							<th>Currency</th>
							<th>20"</th>
							<th>40"</th>
							<th>45"</th>
						
						</tr>	
						<tr>
							<td class="text-right" width="130"><label for="costrcurr" class="text-right">Storage :</label></td>
							<td><?=currency_dropdown('costrcurr',@$data['costrcurr']);?></td>
							<td><input type="text" name="costrv20" class="form-control" id="costrv20" value="<?=@$data['costrv20']?>"></td>
							<td><input type="text" name="costrv40" class="form-control" id="costrv40" value="<?=@$data['costrv40']?>"></td>
							<td><input type="text" name="costrv45" class="form-control" id="costrv45" value="<?=@$data['costrv45']?>"></td>
							<td colspan="4"></td>							
						</tr>	
						<tr>
							<th width="130">&nbsp;</th>
							<th>Container Owner</th>
							<th></th>
							<th></th>
							<th>Cargo Owner</th>
							<th></th>
							<th></th>
							<th></th>
							<th colspan="2"></th>							
						</tr>	
						<tr>
							<td class="text-right" width="130"><label for="lifton" class="text-right">Lift On :</label></td>
							<td><?=currency_dropdown('colomtycurr',@$data['colomtycurr']);?></td>
							<td><input type="text" name="colonmty20" class="form-control" id="colonmty20" value="<?=@$data['colonmty20']?>"></td>
							<td><input type="text" name="colonmty40" class="form-control" id="colonmty40" value="<?=@$data['colonmty40']?>"></td>
							<td><input type="text" name="colonmty45" class="form-control" id="colonmty45" value="<?=@$data['colonmty45']?>"></td>
							<td></td>
							<td><input type="text" name="coloncy20" class="form-control" id="coloncy20" value="<?=@$data['coloncy20']?>"></td>
							<td><input type="text" name="coloncy40" class="form-control" id="coloncy40" value="<?=@$data['coloncy40']?>"></td>			
							<td><input type="text" name="coloncy45" class="form-control" id="coloncy45" value="<?=@$data['coloncy45']?>"></td>			
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="colocycurr" class="text-right">Lift Off :</label></td>
							<td><?=currency_dropdown('colocycurr',@$data['colocycurr']);?></td>
							<td><input type="text" name="colofmty20" class="form-control" id="colofmty20" value="<?=@$data['colofmty20']?>"></td>
							<td><input type="text" name="colofmty40" class="form-control" id="colofmty40" value="<?=@$data['colofmty40']?>"></td>
							<td><input type="text" name="colofmty45" class="form-control" id="colofmty45" value="<?=@$data['colofmty45']?>"></td>
							<td></td>
							<td><input type="text" name="colofcy20" class="form-control" id="colofcy20" value="<?=@$data['colofcy20']?>"></td>
							<td><input type="text" name="colofcy40" class="form-control" id="colofcy40" value="<?=@$data['colofcy40']?>"></td>
							<td><input type="text" name="colofcy45" class="form-control" id="colofcy45" value="<?=@$data['colofcy45']?>"></td>
						</tr>	
						<tr>
							<td class="text-right" width="130"><label for="cowwmtycurr" class="text-right">Water Wash :</label></td>
							<td><?=currency_dropdown('cowwmtycurr',@$data['cowwmtycurr']);?></td>
							<td><input type="text" name="cowwmty20" class="form-control" id="cowwmty20" value="<?=@$data['cowwmty20']?>"></td>
							<td><input type="text" name="cowwmty40" class="form-control" id="cowwmty40" value="<?=@$data['cowwmty40']?>"></td>
							<td><input type="text" name="cowwmty45" class="form-control" id="cowwmty45" value="<?=@$data['cowwmty45']?>"></td>
							<td><?=currency_dropdown('cowwcycurr',@$data['cowwcycurr']);?></td>
							<td><input type="text" name="cowwcy20" class="form-control" id="cowwcy20" value="<?=@$data['cowwcy20']?>"></td>
							<td><input type="text" name="cowwcy40" class="form-control" id="cowwcy40" value="<?=@$data['cowwcy40']?>"></td>					
							<td><input type="text" name="cowwcy45" class="form-control" id="cowwcy45" value="<?=@$data['cowwcy45']?>"></td>					
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="cosc" class="text-right">Steam Wash :</label></td>
							<td><?=currency_dropdown('coscmtycurr',@$data['coscmtycurr']);?></td>
							<td><input type="text" name="coscmty20" class="form-control" id="coscmty20" value="<?=@$data['coscmty20']?>"></td>
							<td><input type="text" name="coscmty40" class="form-control" id="coscmty40" value="<?=@$data['coscmty40']?>"></td>
							<td><input type="text" name="coscmty45" class="form-control" id="coscmty45" value="<?=@$data['coscmty45']?>"></td>
							<td><?=currency_dropdown('cosccycurr',@$data['cosccycurr']);?></td>
							<td><input type="text" name="cosccy20" class="form-control" id="cosccy20" value="<?=@$data['cosccy20']?>"></td>
							<td><input type="text" name="cosccy40" class="form-control" id="cosccy40" value="<?=@$data['cosccy40']?>"></td>						
							<td><input type="text" name="cosccy45" class="form-control" id="cosccy45" value="<?=@$data['cosccy45']?>"></td>						
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="comb" class="text-right">Meat Bone :</label></td>
							<td><?=currency_dropdown('combmtycurr',@$data['combmtycurr']);?></td>
							<td><input type="text" name="combmty20" class="form-control" id="combmty20" value="<?=@$data['combmty20']?>"></td>
							<td><input type="text" name="combmty40" class="form-control" id="combmty40" value="<?=@$data['combmty40']?>"></td>
							<td><input type="text" name="combmty45" class="form-control" id="combmty45" value="<?=@$data['combmty45']?>"></td>
							<td><?=currency_dropdown('combcycurr',@$data['combcycurr']);?></td>
							<td><input type="text" name="combcy20" class="form-control" id="combcy20" value="<?=@$data['combcy20']?>"></td>
							<td><input type="text" name="combcy40" class="form-control" id="combcy40" value="<?=@$data['combcy40']?>"></td>						
							<td><input type="text" name="combcy45" class="form-control" id="combcy45" value="<?=@$data['combcy45']?>"></td>						
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="cord" class="text-right">Debrish Remove :</label></td>
							<td><?=currency_dropdown('cordmtycurr',@$data['cordmtycurr']);?></td>
							<td><input type="text" name="cordmty20" class="form-control" id="cordmty20" value="<?=@$data['cordmty20']?>"></td>
							<td><input type="text" name="cordmty40" class="form-control" id="cordmty40" value="<?=@$data['cordmty40']?>"></td>
							<td><input type="text" name="cordmty45" class="form-control" id="cordmty45" value="<?=@$data['cordmty45']?>"></td>
							<td><?=currency_dropdown('cordcycurr',@$data['cordcycurr']);?></td>
							<td><input type="text" name="cordcy20" class="form-control" id="cordcy20" value="<?=@$data['cordcy20']?>"></td>
							<td><input type="text" name="cordcy40" class="form-control" id="cordcy40" value="<?=@$data['cordcy40']?>"></td>
							<td><input type="text" name="cordcy45" class="form-control" id="cordcy45" value="<?=@$data['cordcy45']?>"></td>					
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="comm" class="text-right">Mark Remove :</label></td>
							<td><?=currency_dropdown('commmtycurr',@$data['commmtycurr']);?></td>
							<td><input type="text" name="commmty20" class="form-control" id="commmty20" value="<?=@$data['commmty20']?>"></td>
							<td><input type="text" name="commmty40" class="form-control" id="commmty40" value="<?=@$data['commmty40']?>"></td>
							<td><input type="text" name="commmty45" class="form-control" id="commmty45" value="<?=@$data['commmty45']?>"></td>
							<td><?=currency_dropdown('commcycurr',@$data['commcycurr']);?></td>
							<td><input type="text" name="commcy20" class="form-control" id="commcy20" value="<?=@$data['commcy20']?>"></td>
							<td><input type="text" name="commcy40" class="form-control" id="commcy40" value="<?=@$data['commcy40']?>"></td>					
							<td><input type="text" name="commcy45" class="form-control" id="commcy45" value="<?=@$data['commcy45']?>"></td>					
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="cocc" class="text-right">Chemical Clean :</label></td>
							<td><?=currency_dropdown('coccmtycurr',@$data['coccmtycurr']);?></td>
							<td><input type="text" name="coccmty20" class="form-control" id="coccmty20" value="<?=@$data['coccmty20']?>"></td>
							<td><input type="text" name="coccmty40" class="form-control" id="coccmty40" value="<?=@$data['coccmty40']?>"></td>
							<td><input type="text" name="coccmty45" class="form-control" id="coccmty45" value="<?=@$data['coccmty45']?>"></td>
							<td><?=currency_dropdown('cocccycurr',@$data['cocccycurr']);?></td>
							<td><input type="text" name="cocccy20" class="form-control" id="cocccy20" value="<?=@$data['cocccy20']?>"></td>
							<td><input type="text" name="cocccy40" class="form-control" id="cocccy40" value="<?=@$data['cocccy40']?>"></td>					
							<td><input type="text" name="cocccy45" class="form-control" id="cocccy45" value="<?=@$data['cocccy45']?>"></td>					
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="cowp" class="text-right">Sweeping :</label></td>
							<td><?=currency_dropdown('cowpmtycurr',@$data['cowpmtycurr']);?></td>
							<td><input type="text" name="cowpmty20" class="form-control" id="cowpmty20" value="<?=@$data['cowpmty20']?>"></td>
							<td><input type="text" name="cowpmty40" class="form-control" id="cowpmty40" value="<?=@$data['cowpmty40']?>"></td>
							<td><input type="text" name="cowpmty45" class="form-control" id="cowpmty45" value="<?=@$data['cowpmty45']?>"></td>
							<td><?=currency_dropdown('cowpcycurr',@$data['cowpcycurr']);?></td>
							<td><input type="text" name="cowpcy20" class="form-control" id="cowpcy20" value="<?=@$data['cowpcy20']?>"></td>
							<td><input type="text" name="cowpcy40" class="form-control" id="cowpcy40" value="<?=@$data['cowpcy40']?>"></td>						
							<td><input type="text" name="cowpcy45" class="form-control" id="cowpcy45" value="<?=@$data['cowpcy45']?>"></td>						
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="coadmm" class="text-right">Administrtion (Cashier) :</label></td>
							<td>
								<label class="control-inline fancy-radio custom-color-green">
									<input type="radio" name="coadmm" id="coadmm" value="1" <?=((isset($data['coadmm']) && ($data['coadmm']==1) ? "checked" : ""))?>>
									<span><i></i>By Order</span>
								</label>
							</td>
							<td>
								<label class="control-inline fancy-radio custom-color-green">
									<input type="radio" name="coadmm" id="coadmm" value="0" <?=((isset($data['coadmm']) && ($data['coadmm']==0) ? "checked" : ""))?>>
									<span><i></i>By Container</span>
								</label>								
							</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>						
							<td></td>						
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="coadmcurr" class="text-right">Adm Currency (Cashier) :</label></td>
							<td><?=currency_dropdown('coadmcurr',@$data['coadmcurr']);?></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>						
							<td></td>						
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="coadmv" class="text-right">Adm Currency (Cashier) :</label></td>
							<td><input type="text" name="coadmv" class="form-control" id="coadmv" value="<?=@$data['coadmv']?>"></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>						
							<td></td>						
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="cotax" class="text-right">Tax :</label></td>
							<td><input type="text" name="cotax" class="form-control" id="cotax" value="<?=@$data['cotax']?>"></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>						
							<td></td>						
						</tr>						
						<tr>
							<td></td>
							<td colspan="8">
								<?php if(isset($act)&&($act=='view')):?>
								<!-- <button type="button" id="updateData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>&nbsp; -->
								<button type="button" id="deleteData" class="btn btn-danger"><i class="fa fa-times-circle"></i> Delete</button>&nbsp;
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

	</div>
</div>


<?= $this->endSection();?>

<!-- Load JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\Contract\Views\js'); ?>

<?= $this->endSection();?>