<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Groups</h2>
		<em>Set Privilege page</em>
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
				<h3><i class="fa fa-table"></i> Set Privilege</h3></div>
			<div class="widget-content">

				<div class="row">
					<div class="col-md-4">
						<b>Group ID : <?=$ugroup; ?></b>
					</div>
				</div>
				<br>

				<?php if($data ==''): ?>
					<p class="alert alert-warning"> Data not found.</p>
				<?php else : ?>

				<div class="row">
					<div class="col-md-12">

						<br>
						<form id="fSetPrivilege" method="POST" action="#">
							<input type="hidden" name="group_id" id="group_id" value="<?=$ugroup;?>">
						<table id="ctTable" class="table table-hover table-bordered display" style="width:100%;">
							<thead>
								<tr>
									<th class="text-center" width="40" rowspan="2">ID</th>
									<th class="text-center" width="400" rowspan="2">Module Name</th>
									<th class="text-center" colspan="7">Access</th>
								</tr>
								<tr>
									<th width="50">Approve</th>
									<th width="50">View</th>
									<th width="50">Insert</th>
									<th width="50">Edit</th>
									<th width="50">Delete</th>
									<th width="60">Print PDF</th>
									<th width="60">Print Excel</th>
								</tr>								
							</thead>
							
							<tbody>

								<?= $tbody; ?>

							</tbody>
						</table>
						<div class="row"><div class="col col-sm-4 col-sm-offset-4">
							<p>&nbsp;</p>
							<a href="<?=site_url('groups')?>" class="btn btn-block btn-primary"> Finish </a>
						</div></div>
						</form>
					</div>
				</div>

				<?php endif; ?>

			</div>		
		</div>		
		
	</div>
</div>

<?= $this->endSection();?>

<!-- JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\Groups\Views\js'); ?>	
	
<?= $this->endSection();?>