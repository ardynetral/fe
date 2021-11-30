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
				<h3><i class="fa fa-edit"></i> <?=((isset($act))&&($act!="")?$act:"")?>&nbsp;<?=$page_title;?></h3>
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
							<td class="text-right" width="130"><label for="cono" class="text-right">WO No :</label></td>
							<td width="300"><input type="text" name="cono" class="form-control" id="cono" value="<?=@$data['cono'];?>"></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="cono" class="text-right">WO Date :</label></td>
							<td><input type="text" name="cono" class="form-control" id="cono" value="<?=@$data['cono'];?>"></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="cono" class="text-right">To :</label></td>
							<td><input type="text" name="cono" class="form-control" id="cono" value="<?=@$data['cono'];?>"></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="cono" class="text-right">From :</label></td>
							<td><input type="text" name="cono" class="form-control" id="cono" value="<?=@$data['cono'];?>"></td>
							<td></td>
						</tr>		
						<tr>
							<td class="text-right" width="130"><label for="cono" class="text-right">CC :</label></td>
							<td><input type="text" name="cono" class="form-control" id="cono" value="<?=@$data['cono'];?>"></td>
							<td></td>
						</tr>		
						<tr>
							<td class="text-right" width="130"><label for="cono" class="text-right">Principal :</label></td>
							<td><?=principal_dropdown();?></td>
							<td></td>
						</tr>	
						<tr>
							<td class="text-right" width="130"><label for="cono" class="text-right">Notes :</label></td>
							<td><input type="text" name="cono" class="form-control" id="cono" value="<?=@$data['cono'];?>"></td>
							<td></td>
						</tr>													
						<tr>
							<td></td>
							<td colspan="8">
								<?php if(isset($act)&&($act=='view')):?>
								<!-- <button type="button" id="updateData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>&nbsp; -->
								<a href="<?=site_url('wo')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
								<?php else: ?>
								<button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
								<a href="<?=site_url('wo')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
								<?php endif; ?>
							</td>
						</tr>						
					</tbody>
					</table>
					</fieldset>
				</form>

				<legend>Header Status Container WR(Waiting Repair)</legend>
				<table class="table">
					<thead>
						<tr><th width="20">No.</th>
							<th>Container No.</th>
							<th>Type</th>
							<th>Length</th>
							<th>Height</th>
						</tr>
					</thead>
					<tbody>
						<tr>
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
		<!-- end .widget -->
	</div>
</div>

<?= $this->endSection();?>

<!-- Load JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\WorkOrder\Views\js'); ?>

<?= $this->endSection();?>