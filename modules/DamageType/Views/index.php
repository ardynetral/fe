<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>
<style type="text/css">
	.btn-tbl{
		margin-right: 5px;
	}
</style>
<div class="content">
	<div class="main-header">
		<h2>Damage Type</h2>
		<em>damage type page</em>
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
				<h3><i class="fa fa-table"></i> Damage Type</h3></div>
			<div class="widget-content">
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('damagetype/add');?>"class="btn btn-primary"><i class="fa fa-plus-square"></i>&nbsp;Add New</a>
					</div>
				</div><br>		
				<div class="row">
					<div class="col-md-12">
						<?php if($damagetype==""): ?>
							<p class="alert alert-warning">Something wrong!. Data not found.</p>
						<?php else: ?>
						<table>
							<tbody>
								<tr><th width="100">Damage Type</th><td width="2">:</td>
									<td></td></tr>
							</tbody>
						</table>
						<br>
						<table id="dyTable" class="table table-hover table-bordered" style="width:100%;">
							<thead>
								<tr>
									<th>No.</th>
									<th>Damage Type</th>
									<th>Description</th>
									<th></th>
								</tr>
							</thead>
							
							
						</table>

						<?php endif; ?>

					</div>
				</div>
			</div>		
		</div>		
		
	</div>
</div>

<?= $this->endSection();?>

<!-- JS -->
<?= $this->Section('script_js');?>
	<?= $this->include('\Modules\DamageType\Views\js'); ?>		
<?= $this->endSection();?>