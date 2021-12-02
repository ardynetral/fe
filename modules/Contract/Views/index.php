<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Contract</h2>
		<em>Contract page</em>
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
				<h3><i class="fa fa-table"></i> Contract</h3></div>
			<div class="widget-content">
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('contract/add');?>"class="btn btn-primary"><i class="fa fa-plus-square"></i>&nbsp;Add New</a>
					</div>
				</div><br>	

				<div class="row">
					<div class="col-md-12">

						<br>
						<table id="ctTable" class="table table-hover table-bordered" style="width:100%;">
							<thead>
								<tr>
									<th>No.</th>
									<th>Contract No.</th>
									<th>Principal</th>
									<th>Begin Date</th>
									<th>End Date</th>
									<th></th>
								</tr>
							</thead>
<<<<<<< HEAD
	
=======
							
>>>>>>> cd1ad87e995100840895862f246b6cf14957748d
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

	<?= $this->include('\Modules\Contract\Views\js'); ?>	
	
<?= $this->endSection();?>