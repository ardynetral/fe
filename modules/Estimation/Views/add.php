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
				<h3><i class="fa fa-edit"></i> <?=((isset($act))&&($act!="")?$act:"")?> Estimation</h3>
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
							<td class="text-right">EOR No :</td>
							<td colspan="3"><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Date :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Time :</td>
							<td colspan="3"><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">ID Code :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Type :</td>
							<td colspan="3"><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Lenght :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Height :</td>
							<td colspan="3"><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Contract No :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Expired :</td>
							<td colspan="3"><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Surveyor :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Survey Date :</td>
							<td colspan="3"><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Est Version :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Survey Condition :</td>
							<td colspan="3"><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr><td colspan="6"></td></tr>
						<tr>
							<td></td>
							<td colspan="5">
								<?php if(isset($act)&&($act=='view')):?>
								<!-- <button type="button" id="updateData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>&nbsp; -->
								<button type="button" id="cancel" class="btn btn-default"><i class="fa fa-ban"></i> Back</button>								
								<?php else: ?>
								<button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
								<button type="button" id="addDetail" class="btn btn-success"><i class="fa fa-list"></i> Add Detail</button>&nbsp;
								<button type="button" id="cancel" class="btn btn-default text-right"><i class="fa fa-ban"></i> Cancel</button>								
								<?php endif; ?>
							</td>
						</tr>	
					</tbody>
				</table>
			</fieldset>
		</form>

		<table class="table">
			<thead>
				<tr>
					<th>No</th>
					<th>COM</th>
					<th>DT</th>
					<th>RM</th>
					<th>CM</th>
					<th>BCE</th>
					<th>SIZE</th>
					<th>MU</th>
					<th>QTY</th>
					<th>MHR</th>
					<th>CUR</th>
					<th>DESC</th>
					<th>Lab. Cost</th>
					<th>Mat. Cost</th>
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
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

<?= $this->endSection();?>

<!-- Load JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\Estimation\Views\js'); ?>

<?= $this->endSection();?>