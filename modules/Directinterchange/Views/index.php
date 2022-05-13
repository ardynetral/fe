<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>
<style type="text/css">
	.btn-tbl{
		margin-right: 5px;
	}
</style>
<div class="content">
	<div class="main-header">
		<h2>Direct Interchange</h2>
		<em>Direct Interchange</em>
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
				<h3><i class="fa fa-table"></i> Container</h3></div>
			<div class="widget-content">
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('directinterchange/add');?>"class="btn btn-primary"><i class="fa fa-plus-square"></i>&nbsp;Add New</a>
					</div>
				</div><br>	


				<div class="row">
					<div class="col-md-12">

						<br>
						<table id="ctTable" class="table table-hover table-bordered" style="width:100%;">
							<thead>							
								<tr>
									<th>No.</th>
									<th>Container</th>
									<th>Principal</th>
									<th>Customer</th>
									<th>Date</th>
									<th>Note</th>
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

<?= $this->endSection();?>

<!-- JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\Directinterchange\Views\js'); ?>	
	
<?= $this->endSection();?>