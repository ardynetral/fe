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
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Date Estimation :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">ID Code :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Type :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Length :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Height :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Repair Contract No :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Expired :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Surveyor :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Survey Date :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Version :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Aut No :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Notes Approval :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Bill On :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Approval Confirm :</td>
							<td ><input type="file" name="" id="" class="form-control" value="<?='';?>"></td>
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

		<div class="form-horizontal">
			<div class="row">
				<label class="col-sm-2 control-label text-right">Date Approval :</label>
				<div class="col-sm-2">
				<input type="text" name="" value="" class="form-control">
				</div>
			</div>
		</div>
		<br>
		<table class="table">
			<thead>
				<tr>
					<th>No</th>
					<th width="150">DESC</th>
					<th>QTY A</th>
					<th>SIZE A</th>
					<th>MHR A</th>
					<th>MTR A</th>
					<th>LBR A</th>
					<th>TOTAl A</th>
					<th>ACCOUNT</th>
					<th>APPROVED</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td></td>
					<td></td>
					<td><input type="text" name="" class="form-control"></td>
					<td><input type="text" name="" class="form-control"></td>
					<td><input type="text" name="" class="form-control"></td>
					<td><input type="text" name="" class="form-control"></td>
					<td><input type="text" name="" class="form-control"></td>
					<td><input type="text" name="" class="form-control"></td>
					<td></td>
					<td></td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="2" class="text-right">Total Repair (Approved)</th>
					<th><input type="text" name="" class="form-control"></th>
					<th><input type="text" name="" class="form-control"></th>
					<th></th>
					<th colspan="2" class="text-right">Total Cleaning (Approved)</th>
					<th><input type="text" name="" class="form-control"></th>
					<th><input type="text" name="" class="form-control"></th>
					<td></td>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

<?= $this->endSection();?>

<!-- Load JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\Approval\Views\js'); ?>

<?= $this->endSection();?>