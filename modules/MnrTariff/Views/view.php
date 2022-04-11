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
				<form id="UpdateMnrTariff" class="form-horizontal" role="form" method="post">
					<?= csrf_field() ?>
					<?php $readonly = 'readonly'; ?>
					<input type="hidden" name="isoid" id="isoid" value="<?=@$data['isoid']; ?>">
					<fieldset>
						<table class="tbl-form" width="100%">
						<tbody>
							<tr>
								<td class="text-right" width="130">Eqp. Type :</td>
								<td><input type="text" name="mtcode" class="form-control" id="mtcode" value="<?=@$data['mtcode']; ?>" required></td>
								<td class="text-right" width="130">Limit :</td>
								<td><input type="text" name="_limit" class="form-control" id="_limit" value="<?=@$data['_limit']; ?>" required></td>								
							</tr>
							<tr>
								<td class="text-right" width="130">Comp. Code :</td>
								<td><?= $component_dropdown; ?></td>
								<td class="text-right" width="130">Start :</td>
								<td><input type="text" name="_start" class="form-control" id="_start" value="<?=@$data['_start']; ?>" required></td>								
							</tr>	
							<tr>
								<td class="text-right" width="130">Comp. Description :</td>
								<td><input type="text" name="comp_description" class="form-control" id="comp_description" value="<?=@$data['comp_description']; ?>" readonly></td>
								<td class="text-right" width="130">Hours :</td>
								<td><input type="text" name="_hours" class="form-control" id="_hours" value="<?=@$data['_hours']; ?>" required></td>									
							</tr>	
							<tr>
								<td class="text-right" width="130">Repair Code :</td>
								<td><?= $repair_dropdown; ?></td>
								<td class="text-right" width="130">Mtrl.Cost :</td>
								<td><input type="text" name="_mtrlcost" class="form-control" id="_mtrlcost" value="<?=@$data['_mtrlcost']; ?>" required></td>								

							</tr>	
							<tr>
								<td class="text-right" width="130">Repair Description :</td>
								<td><input type="text" name="repair_description" class="form-control" id="repair_description" value="<?=@$data['repair_description']; ?>" required readonly></td>
								<td class="text-right" width="130">INC :</td>
								<td><input type="text" name="_inc" class="form-control" id="_inc" value="<?=@$data['_inc']; ?>" required></td>								
							</tr>	
							<tr>
								<td class="text-right" width="130">Material :</td>
								<td><input type="text" name="material" class="form-control" id="material" value="<?=@$data['material']; ?>" required></td>	
								<td class="text-right" width="130">INC Hours :</td>
								<td><input type="text" name="_inchours" class="form-control" id="_inchours" value="<?=@$data['_inchours']; ?>" required></td>
							</tr>
							<tr>
								<td class="text-right" width="130">Formula :</td>
								<td><input type="text" name="formula" class="form-control" id="formula" value="<?=@$data['formula']; ?>" required></td>	
								<td class="text-right" width="130">INC Mtrl.Cost :</td>
								<td><input type="text" name="_incmtrlcost" class="form-control" id="_incmtrlcost" value="<?=@$data['_incmtrlcost']; ?>" required></td>
							</tr>
							<tr>
								<td class="text-right" width="130">Also Applies To :</td>
								<td><input type="text" name="also_applies_to" class="form-control" id="also_applies_to" value="<?=@$data['also_applies_to']; ?>" required></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td class="text-right" width="130">Location :</td>
								<td><input type="text" name="locations" class="form-control" id="locations" value="<?=@$data['locations']; ?>" required></td>	
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td class="text-right" width="130">ISO Codes :</td>
								<td><input type="text" class="form-control" value="<?=@$data['cccodes']; ?>"></td>
								<td></td>
								<td></td>
							</tr>
						<tr>
							<td></td>
							<td colspan="3">
								<a id="cancel" href="<?= site_url('mnrtariff') ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Back</a>
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