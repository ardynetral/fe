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
				
				<form id="fEstimasi" class="form-horizontal" role="form" method="POST">
					<?= csrf_field() ?>
					<?php $readonly = 'readonly'; ?>
					<input type="hidden" name="svid" id="svid">
					<input type="hidden" name="rpcrton" id="rpcrton">
					<input type="hidden" name="rpcrtby" id="rpcrtby">
					<fieldset>
						<table class="tbl-form" width="100%">
							<!-- 9 kolom -->
							<tbody>
								<tr>
									<td class="text-right" width="130">Container No :</td>
									<td><input type="text" name="rpcrno" class="form-control" id="rpcrno" value="<?= ''; ?>">
										<i class="err-crno text-danger"></i></td>
									<td class="text-right">EOR No :</td>
									<td colspan="3"><input <?php echo $readonly; ?> type="text" name="rpnoest" id="rpnoest" class="form-control" value="<?= ''; ?>" readonly></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-right" width="130">Date :</td>
									<td><input type="text" name="rptglest" class="form-control" id="rptglest" value="<?= date('d-m-Y'); ?>" readonly></td>
									<td class="text-right">Time :</td>
									<td colspan="3"><input type="text" name="rpjamest" id="rpjamest" class="form-control" value="<?= date('H:i:s'); ?>" readonly></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-right" width="130">ID Code :</td>
									<td><input <?php echo $readonly; ?> type="text" name="cccode" class="form-control" id="cccode" value="<?= ''; ?>"></td>
									<td class="text-right">Type :</td>
									<td colspan="3"><input <?php echo $readonly; ?> type="text" name="ctcode" id="ctcode" class="form-control" value="<?= ''; ?>"></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-right" width="130">Lenght :</td>
									<td><input <?php echo $readonly; ?> type="text" name="cclength" class="form-control" id="cclength" value="<?= ''; ?>"></td>
									<td class="text-right">Height :</td>
									<td colspan="3"><input <?php echo $readonly; ?> type="text" name="ccheight" id="ccheight" class="form-control" value="<?= ''; ?>"></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-right" width="130">Contract No :</td>
									<td><input <?php echo $readonly; ?> type="text" name="cono" class="form-control" id="cono" value="<?= ''; ?>"></td>
									<td class="text-right">Expired :</td>
									<td colspan="3"><input <?php echo $readonly; ?> type="text" name="coexpdate" id="coexpdate" class="form-control" value="<?= ''; ?>"></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-right" width="130">Surveyor :</td>
									<td><input <?php echo $readonly; ?> type="text" name="syid" class="form-control" id="syid" value="<?= ''; ?>"></td>
									<td class="text-right">Survey Date :</td>
									<td colspan="3"><input <?php echo $readonly; ?> type="text" name="svsurdat" id="svsurdat" class="form-control" value="<?= ''; ?>"></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-right" width="130">Est Version :</td>
									<td><input <?php echo $readonly; ?> type="text" name="rpver" class="form-control" id="rpver" value="<?= ''; ?>"></td>
									<td class="text-right">Survey Condition :</td>
									<td colspan="3"><input <?php echo $readonly; ?> type="text" name="svcond" id="svcond" class="form-control" value="<?= ''; ?>"></td>
									<td></td>
									<td></td>
								</tr>
								<tr>
									<td class="text-right" width="130">WO Number :</td>
									<td><input <?php echo $readonly; ?> type="text" name="wono" class="form-control" id="wono" value="<?= ''; ?>"></td>
									<td class="text-right">Inspektor :</td>
									<td colspan="3"><input <?php echo $readonly; ?> type="text" name="inspektor" id="inspektor" class="form-control" value="<?= ''; ?>"></td>
									<td></td>
									<td></td>
								</tr>
							</tbody>
						</table>
					</fieldset>
				</form>					

				<legend></legend>
				<div class="row">
					<div class="col-lg-12">
						<p class="text-center">
							<button type="button" id="finishRepair" class="btn btn-success"><i class="fa fa-cogs"></i> FINISH REPAIR</button>
							<button type="button" id="finishCleaning" class="btn btn-primary"><i class="fa fa-check-circle"></i> FINISH CLEANING</button>&nbsp;

							<a href="<?= site_url('rip') ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
						</p>
						<div class="widget widget-table">
							<div class="widget-header">
								<h3><i class="fa fa-table"></i> Repair's Item</h3>
							</div>
							<div class="widget-content">
								<div class="table-responsive vscroll">
								<table class="table" id="tblList_add">
									<thead>
										<tr>
											<th></th>
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
									</tbody>
								</table>
								</div>
							</div>
						</div>
					</div>					
				</div>								
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