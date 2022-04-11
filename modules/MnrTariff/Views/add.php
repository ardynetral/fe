<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2><?=$page_title?></h2>
		<em><?=$page_subtitle?> page</em>
	</div>

	<?php if(session()->getFlashdata('sukses')):?>
	<div class="alert alert-success alert-dismissable">
		<a href="" class="close">×</a>
		<strong><?=session()->getFlashdata('sukses');?></strong>
	</div>
	<?php endif; ?>

	<div class="main-content">
		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> <?=$page_title?></h3>
			</div>
			<div class="widget-content">
				<form id="MnrTariff" class="form-horizontal" role="form" method="post">
					<?= csrf_field() ?>
					<?php $readonly = 'readonly'; ?>
					<fieldset>
						<table class="tbl-form" width="100%">
						<tbody>
							<tr>
								<td class="text-right" width="130">Eqp. Type :</td>
								<td><input type="text" name="mtcode" class="form-control" id="mtcode" required></td>
								<td class="text-right" width="130">Limit :</td>
								<td><input type="text" name="_limit" class="form-control" id="limit" required></td>								
							</tr>
							<tr>
								<td class="text-right" width="130">Comp. Code :</td>
								<td><?= $component_dropdown; ?></td>
								<td class="text-right" width="130">Start :</td>
								<td><input type="text" name="_start" class="form-control" id="start" required></td>								
							</tr>	
							<tr>
								<td class="text-right" width="130">Comp. Description :</td>
								<td><input type="text" name="comp_description" class="form-control" id="comp_description" required readonly></td>
								<td class="text-right" width="130">Hours :</td>
								<td><input type="text" name="_hours" class="form-control" id="hours" required></td>									
							</tr>	
							<tr>
								<td class="text-right" width="130">Repair Code :</td>
								<td><?= $repair_dropdown; ?></td>
								<td class="text-right" width="130">Mtrl.Cost :</td>
								<td><input type="text" name="_mtrlcost" class="form-control" id="mtrlcost" required></td>								

							</tr>	
							<tr>
								<td class="text-right" width="130">Repair Description :</td>
								<td><input type="text" name="repair_description" class="form-control" id="repair_description" required readonly></td>
								<td class="text-right" width="130">INC :</td>
								<td><input type="text" name="_inc" class="form-control" id="inc" required></td>								
							</tr>	
							<tr>
								<td class="text-right" width="130">Material :</td>
								<td><input type="text" name="material" class="form-control" id="material" required></td>	
								<td class="text-right" width="130">INC Hours :</td>
								<td><input type="text" name="_inchours" class="form-control" id="inchours" required></td>
							</tr>
							<tr>
								<td class="text-right" width="130">Formula :</td>
								<td><input type="text" name="formula" class="form-control" id="formula" required></td>	
								<td class="text-right" width="130">INC Mtrl.Cost :</td>
								<td><input type="text" name="_incmtrlcost" class="form-control" id="incmtrlcost" required></td>
							</tr>
							<tr>
								<td class="text-right" width="130">Also Applies To :</td>
								<td><input type="text" name="also_applies_to" class="form-control" id="also_applies_to" required></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td class="text-right" width="130">Location :</td>
								<td><input type="text" name="locations" class="form-control" id="locations" required></td>	
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td class="text-right" width="130">ISO Codes :</td>
								<td><?= $cccodes_dropdown; ?></td>
								<td></td>
								<td></td>
							</tr>
						<tr>
							<td></td>
							<td colspan="3">
								<button type="submit" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
								<a id="cancel" href="<?= site_url('mnrtariff') ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
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

<!-- JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\MnrTariff\Views\js'); ?>	
	
<?= $this->endSection();?>