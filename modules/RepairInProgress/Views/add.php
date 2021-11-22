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
				<h3><i class="fa fa-edit"></i> <?=((isset($act))&&($act!="")?$act:"")?>&nbsp; Maintenance Repair</h3>
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
							<td class="text-right" width="150"><label for="" class="text-right">Container No :</label></td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td></td>
							<td class="text-right" width="150">Material :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="" class="text-right">WO No :</label></td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td></td>
							<td class="text-right">Container Type :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="" class="text-right">Date :</label></td>
							<td><input type="text" name="" class="form-control" id="" value="<?=''?>"></td>
							<td></td>
							<td class="text-right">Length :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="" class="text-right">Principal :</label></td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td></td>
							<td class="text-right">Height :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="" class="text-right">Surveyor :</label></td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td></td>
							<td class="text-right">survey Condition :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="" class="text-right">Survey Date :</label></td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>	
						<tr>
							<th>Repair</th>
							<th>Date</th>
							<th>Time</th>
							<th>Cleaning</th>
							<th>Date</th>
							<th>Time</th>
						</tr>	
						<tr>
							<td >
								<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="" id="" value="0">
								<span>In WorkShop :</span></label>
							</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td>
								<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="" id="" value="0">
								<span>Start Cleaning :</span></label>
							</td>	
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
						</tr>
						<tr>
							<td >
								<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="" id="" value="0">
								<span>Start Repair :</span></label>
							</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td >
								<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="" id="" value="0">
								<span>Finish Cleaning :</span></label>
							</td>	
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
						</tr>
						<tr>
							<td >
								<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="" id="" value="0">
								<span>Finish Repair :</span></label>
							</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right" >Status</td>	
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td></td>
						</tr>
						<tr>
							<td >
								<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="" id="" value="0">
								<span>Out WorkShop :</span></label>
							</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right" >Notes</td>	
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td></td>
						</tr>	
						<tr>
							<td class="text-right" >Inspector :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td></td>
							<td class="text-right" >Total Completion :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td></td>			
						</tr>
						<tr>
							<td class="text-right">Fictive Completion :</td>
							<td>
								<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="" id="" value="0">
								<span></span></label>
							</td>
							<td></td>
							<td ></td>	
							<td></td>
							<td></td>
						</tr>						
						<tr>
							<td></td>
							<td colspan="8">
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

				<legend>Repair's Item</legend>
				<table class="table">
					<thead>
						<tr><th width="20">No.</th>
							<th>Loc</th>
							<th>Com</th>
							<th>DT</th>
							<th>RM</th>
							<th>CM</th>
							<th>SIZE</th>
							<th>MU</th>
							<th>QTY</th>
							<th>MHY</th>
							<th>COMP</th>
							<th width="80">A.QTY</th>
							<th width="80">A.MHR</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td>
								<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="" id="" value="0">
								<span>Finish Repair :</span></label>
							</td>
							<td>
								<input type="text" name="" class="form-control" id="" value="<?='';?>">
							</td>
							<td>
								<input type="text" name="" class="form-control" id="" value="<?='';?>">
							</td>
						</tr>
					</tbody>
				</table>				
			</div>
		</div>
		<!-- end .widget -->		
	</div>
</div>

<?= $this->endSection();?>

<!-- Load JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\RepairInProgress\Views\js'); ?>

<?= $this->endSection();?>		