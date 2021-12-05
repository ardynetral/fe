<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<?php
if(isset($data) && ($data!='')) {

}
?>


<div class="content">
	<div class="main-header">
		<h2><?=$page_title?></h2>
		<em><?=$page_title?> page</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> <?=((isset($act))&&($act=="view")?"Detail ":"Add ")?><?=$page_title?></h3>
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
							<td>
							<?php if(isset($act)&&($act=='add')):?>
								<input type="text" name="cono" class="form-control" id="cono" value="<?=@$data['cono'];?>">
							<?php else:?>
								<input type="text" name="conos" class="form-control" id="conos" value="<?=@$data['cono'];?>">
							<?php endif;?>
							</td>
							<td colspan="7"></td>
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="prcode">Principal No :</label></td>
							<td><?=principal_dropdown(@$data['prcode']);?></td>
							<td class="text-right" width="130"><label for="codate" class="text-right">Begin :</label></td>
							<td colspan="2">
								<div class="input-group">
								<input type="text" name="codate" id="codate" class="form-control tanggal" required="" value="<?=@date('d-m-Y',strtotime($data['codate']))?>">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>									
							</td>							
							<td class="text-right" width="130"><label for="coexpdate" class="text-right">Expire :</label></td>
							<td colspan="2">
								<div class="input-group">
								<input type="text" name="coexpdate" id="coexpdate" class="form-control tanggal" required="" value="<?=@date('d-m-Y',strtotime($data['coexpdate']));?>">
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
								<input type="checkbox" name="cofreedn" class="" id="cofreedn" value="<?=((isset($data['cofreedn'])&&($data['cofreedn']==1)) ? "1" : "0");?>" <?=((isset($data['cofreedn'])&&($data['cofreedn']==1)) ? "checked" : "");?>>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<label for="cofreedmg" class="text-right">Free (Damage) :</label>
								<input type="checkbox" name="cofreedmg" class="" id="cofreedmg" value="<?=((isset($data['cofreedmg'])&&($data['cofreedmg']==1)) ? "1" : "0");?>" <?=((isset($data['cofreedmg'])&&($data['cofreedmg']==1)) ? "checked" : "");?>>
							</td>	
						</tr>						
						<tr>
							<td class="text-right" width="130"><label for="covcurr" class="text-right">Approval/Repair Currency :</label></td>
							<td><?=currency_dropdown2('covcurr',@$data['covcurr']);?></td>
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
							<td><?=currency_dropdown2('colabrtcurr',@$data['colabrtcurr']);?></td>
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
							<td><?=currency_dropdown2('costrcurr',@$data['costrcurr']);?></td>
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
							<td><?=currency_dropdown2('colomtycurr',@$data['colomtycurr']);?></td>
							<td><input type="text" name="colonmty20" class="form-control" id="colonmty20" value="<?=@(isset($data['colonmty20'])&&($data['colonmty20']!="")?$data['colonmty20']:"0")?>"></td>
							<td><input type="text" name="colonmty40" class="form-control" id="colonmty40" value="<?=@(isset($data['colonmty40'])&&($data['colonmty40']!="")?$data['colonmty40']:"0")?>"></td>
							<td><input type="text" name="colonmty45" class="form-control" id="colonmty45" value="<?=@(isset($data['colonmty45'])&&($data['colonmty45']!="")?$data['colonmty45']:"0")?>"></td>
							<td></td>
							<td><input type="text" name="coloncy20" class="form-control" id="coloncy20" value="<?=@(isset($data['coloncy20'])&&($data['coloncy20']!="")?$data['coloncy20']:"0")?>"></td>
							<td><input type="text" name="coloncy40" class="form-control" id="coloncy40" value="<?=@(isset($data['coloncy40'])&&($data['coloncy40']!="")?$data['coloncy40']:"0")?>"></td>			
							<td><input type="text" name="coloncy45" class="form-control" id="coloncy45" value="<?=@(isset($data['coloncy45'])&&($data['coloncy45']!="")?$data['coloncy45']:"0")?>"></td>			
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="colocycurr" class="text-right">Lift Off :</label></td>
							<td><?=currency_dropdown2('colocycurr',@$data['colocycurr']);?></td>
							<td><input type="text" name="colofmty20" class="form-control" id="colofmty20" value="<?=@(isset($data['colofmty20'])&&($data['colofmty20']!="")?$data['colofmty20']:"0")?>"></td>
							<td><input type="text" name="colofmty40" class="form-control" id="colofmty40" value="<?=@(isset($data['colofmty40'])&&($data['colofmty40']!="")?$data['colofmty40']:"0")?>"></td>
							<td><input type="text" name="colofmty45" class="form-control" id="colofmty45" value="<?=@(isset($data['colofmty45'])&&($data['colofmty45']!="")?$data['colofmty45']:"0")?>"></td>
							<td></td>
							<td><input type="text" name="colofcy20" class="form-control" id="colofcy20" value="<?=@(isset($data['colofcy20'])&&($data['colofcy20']!="")?$data['colofcy20']:"0")?>"></td>
							<td><input type="text" name="colofcy40" class="form-control" id="colofcy40" value="<?=@(isset($data['colofcy40'])&&($data['colofcy40']!="")?$data['colofcy40']:"0")?>"></td>
							<td><input type="text" name="colofcy45" class="form-control" id="colofcy45" value="<?=@(isset($data['colofcy45'])&&($data['colofcy45']!="")?$data['colofcy45']:"0")?>"></td>
						</tr>	
						<tr>
							<td class="text-right" width="130"><label for="cowwmtycurr" class="text-right">Water Wash :</label></td>
							<td><?=currency_dropdown2('cowwmtycurr',@$data['cowwmtycurr']);?></td>
							<td><input type="text" name="cowwmty20" class="form-control" id="cowwmty20" value="<?=@(isset($data['cowwmty20'])&&($data['cowwmty20']!="")?$data['cowwmty20']:"0")?>"></td>
							<td><input type="text" name="cowwmty40" class="form-control" id="cowwmty40" value="<?=@(isset($data['cowwmty40'])&&($data['cowwmty40']!="")?$data['cowwmty40']:"0")?>"></td>
							<td><input type="text" name="cowwmty45" class="form-control" id="cowwmty45" value="<?=@(isset($data['cowwmty45'])&&($data['cowwmty45']!="")?$data['cowwmty45']:"0")?>"></td>
							<td><?=currency_dropdown2('cowwcycurr',@$data['cowwcycurr']);?></td>
							<td><input type="text" name="cowwcy20" class="form-control" id="cowwcy20" value="<?=@(isset($data['cowwcy20'])&&($data['cowwcy20']!="")?$data['cowwcy20']:"0")?>"></td>
							<td><input type="text" name="cowwcy40" class="form-control" id="cowwcy40" value="<?=@(isset($data['cowwcy40'])&&($data['cowwcy40']!="")?$data['cowwcy40']:"0")?>"></td>					
							<td><input type="text" name="cowwcy45" class="form-control" id="cowwcy45" value="<?=@(isset($data['cowwcy45'])&&($data['cowwcy45']!="")?$data['cowwcy45']:"0")?>"></td>					
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="cosc" class="text-right">Steam Wash :</label></td>
							<td><?=currency_dropdown2('coscmtycurr',@$data['coscmtycurr']);?></td>
							<td><input type="text" name="coscmty20" class="form-control" id="coscmty20" value="<?=@(isset($data['coscmty20'])&&($data['coscmty20']!="")?$data['coscmty20']:"0")?>"></td>
							<td><input type="text" name="coscmty40" class="form-control" id="coscmty40" value="<?=@(isset($data['coscmty40'])&&($data['coscmty40']!="")?$data['coscmty40']:"0")?>"></td>
							<td><input type="text" name="coscmty45" class="form-control" id="coscmty45" value="<?=@(isset($data['coscmty45'])&&($data['coscmty45']!="")?$data['coscmty45']:"0")?>"></td>
							<td><?=currency_dropdown2('cosccycurr',@$data['cosccycurr']);?></td>
							<td><input type="text" name="cosccy20" class="form-control" id="cosccy20" value="<?=@(isset($data['cosccy20'])&&($data['cosccy20']!="")?$data['cosccy20']:"0")?>"></td>
							<td><input type="text" name="cosccy40" class="form-control" id="cosccy40" value="<?=@(isset($data['cosccy40'])&&($data['cosccy40']!="")?$data['cosccy40']:"0")?>"></td>						
							<td><input type="text" name="cosccy45" class="form-control" id="cosccy45" value="<?=@(isset($data['cosccy45'])&&($data['cosccy45']!="")?$data['cosccy45']:"0")?>"></td>						
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="comb" class="text-right">Meat Bone :</label></td>
							<td><?=currency_dropdown2('combmtycurr',@$data['combmtycurr']);?></td>
							<td><input type="text" name="combmty20" class="form-control" id="combmty20" value="<?=@(isset($data['combmty20'])&&($data['combmty20']!="")?$data['combmty20']:"0")?>"></td>
							<td><input type="text" name="combmty40" class="form-control" id="combmty40" value="<?=@(isset($data['combmty40'])&&($data['combmty40']!="")?$data['combmty40']:"0")?>"></td>
							<td><input type="text" name="combmty45" class="form-control" id="combmty45" value="<?=@(isset($data['combmty45'])&&($data['combmty45']!="")?$data['combmty45']:"0")?>"></td>
							<td><?=currency_dropdown2('combcycurr',@$data['combcycurr']);?></td>
							<td><input type="text" name="combcy20" class="form-control" id="combcy20" value="<?=@(isset($data['combcy20'])&&($data['combcy20']!="")?$data['combcy20']:"0")?>"></td>
							<td><input type="text" name="combcy40" class="form-control" id="combcy40" value="<?=@(isset($data['combcy40'])&&($data['combcy40']!="")?$data['combcy40']:"0")?>"></td>						
							<td><input type="text" name="combcy45" class="form-control" id="combcy45" value="<?=@(isset($data['combcy45'])&&($data['combcy45']!="")?$data['combcy45']:"0")?>"></td>						
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="cord" class="text-right">Debrish Remove :</label></td>
							<td><?=currency_dropdown2('cordmtycurr',@$data['cordmtycurr']);?></td>
							<td><input type="text" name="cordmty20" class="form-control" id="cordmty20" value="<?=@(isset($data['cordmty20'])&&($data['cordmty20']!="")?$data['cordmty20']:"0")?>"></td>
							<td><input type="text" name="cordmty40" class="form-control" id="cordmty40" value="<?=@(isset($data['cordmty40'])&&($data['cordmty40']!="")?$data['cordmty40']:"0")?>"></td>
							<td><input type="text" name="cordmty45" class="form-control" id="cordmty45" value="<?=@(isset($data['cordmty45'])&&($data['cordmty45']!="")?$data['cordmty45']:"0")?>"></td>
							<td><?=currency_dropdown2('cordcycurr',@$data['cordcycurr']);?></td>
							<td><input type="text" name="cordcy20" class="form-control" id="cordcy20" value="<?=@(isset($data['cordcy20'])&&($data['cordcy20']!="")?$data['cordcy20']:"0")?>"></td>
							<td><input type="text" name="cordcy40" class="form-control" id="cordcy40" value="<?=@(isset($data['cordcy40'])&&($data['cordcy40']!="")?$data['cordcy40']:"0")?>"></td>
							<td><input type="text" name="cordcy45" class="form-control" id="cordcy45" value="<?=@(isset($data['cordcy45'])&&($data['cordcy45']!="")?$data['cordcy45']:"0")?>"></td>					
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="comm" class="text-right">Mark Remove :</label></td>
							<td><?=currency_dropdown2('commmtycurr',@$data['commmtycurr']);?></td>
							<td><input type="text" name="commmty20" class="form-control" id="commmty20" value="<?=@(isset($data['commmty20'])&&($data['commmty20']!="")?$data['commmty20']:"0")?>"></td>
							<td><input type="text" name="commmty40" class="form-control" id="commmty40" value="<?=@(isset($data['commmty40'])&&($data['commmty40']!="")?$data['commmty40']:"0")?>"></td>
							<td><input type="text" name="commmty45" class="form-control" id="commmty45" value="<?=@(isset($data['commmty45'])&&($data['commmty45']!="")?$data['commmty45']:"0")?>"></td>
							<td><?=currency_dropdown2('commcycurr',@$data['commcycurr']);?></td>
							<td><input type="text" name="commcy20" class="form-control" id="commcy20" value="<?=@(isset($data['commcy20'])&&($data['commcy20']!="")?$data['commcy20']:"0")?>"></td>
							<td><input type="text" name="commcy40" class="form-control" id="commcy40" value="<?=@(isset($data['commcy40'])&&($data['commcy40']!="")?$data['commcy40']:"0")?>"></td>					
							<td><input type="text" name="commcy45" class="form-control" id="commcy45" value="<?=@(isset($data['commcy45'])&&($data['commcy45']!="")?$data['commcy45']:"0")?>"></td>					
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="cocc" class="text-right">Chemical Clean :</label></td>
							<td><?=currency_dropdown2('coccmtycurr',@$data['coccmtycurr']);?></td>
							<td><input type="text" name="coccmty20" class="form-control" id="coccmty20" value="<?=@(isset($data['coccmty20'])&&($data['coccmty20']!="")?$data['coccmty20']:"0")?>"></td>
							<td><input type="text" name="coccmty40" class="form-control" id="coccmty40" value="<?=@(isset($data['coccmty40'])&&($data['coccmty40']!="")?$data['coccmty40']:"0")?>"></td>
							<td><input type="text" name="coccmty45" class="form-control" id="coccmty45" value="<?=@(isset($data['coccmty45'])&&($data['coccmty45']!="")?$data['coccmty45']:"0")?>"></td>
							<td><?=currency_dropdown2('cocccycurr',@$data['cocccycurr']);?></td>
							<td><input type="text" name="cocccy20" class="form-control" id="cocccy20" value="<?=@(isset($data['cocccy20'])&&($data['cocccy20']!="")?$data['cocccy20']:"0")?>"></td>
							<td><input type="text" name="cocccy40" class="form-control" id="cocccy40" value="<?=@(isset($data['cocccy40'])&&($data['cocccy40']!="")?$data['cocccy40']:"0")?>"></td>					
							<td><input type="text" name="cocccy45" class="form-control" id="cocccy45" value="<?=@(isset($data['cocccy45'])&&($data['cocccy45']!="")?$data['cocccy45']:"0")?>"></td>					
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="cowp" class="text-right">Sweeping :</label></td>
							<td><?=currency_dropdown2('cowpmtycurr',@$data['cowpmtycurr']);?></td>
							<td><input type="text" name="cowpmty20" class="form-control" id="cowpmty20" value="<?=@(isset($data['cowpmty20'])&&($data['cowpmty20']!="")?$data['cowpmty20']:"0")?>"></td>
							<td><input type="text" name="cowpmty40" class="form-control" id="cowpmty40" value="<?=@(isset($data['cowpmty40'])&&($data['cowpmty40']!="")?$data['cowpmty40']:"0")?>"></td>
							<td><input type="text" name="cowpmty45" class="form-control" id="cowpmty45" value="<?=@(isset($data['cowpmty45'])&&($data['cowpmty45']!="")?$data['cowpmty45']:"0")?>"></td>
							<td><?=currency_dropdown2('cowpcycurr',@$data['cowpcycurr']);?></td>
							<td><input type="text" name="cowpcy20" class="form-control" id="cowpcy20" value="<?=@(isset($data['cowpcy20'])&&($data['cowpcy20']!="")?$data['cowpcy20']:"0")?>"></td>
							<td><input type="text" name="cowpcy40" class="form-control" id="cowpcy40" value="<?=@(isset($data['cowpcy40'])&&($data['cowpcy40']!="")?$data['cowpcy40']:"0")?>"></td>						
							<td><input type="text" name="cowpcy45" class="form-control" id="cowpcy45" value="<?=@(isset($data['cowpcy45'])&&($data['cowpcy45']!="")?$data['cowpcy45']:"0")?>"></td>						
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
							<td><?=currency_dropdown2('coadmcurr',@$data['coadmcurr']);?></td>
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
							<td class="text-right" width="130"><label for="comaterai" class="text-right">Materai :</label></td>
							<td><input type="text" name="comaterai" class="form-control" id="comaterai" value="<?=@$data['comaterai']?>"></td>
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
								<?php if(isset($act)&&($act=='add')):?>
									<button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
									<a href="<?=site_url('contract')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
								<?php endif; ?>
								<?php if(isset($act)&&($act=='edit')):?>
									<button type="button" id="updateData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>&nbsp;
									<a href="<?=site_url('contract')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
								<?php endif; ?>
								<?php if(isset($act)&&($act=='view')):?>
									<a href="<?=site_url('contract')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Kembali</a>
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