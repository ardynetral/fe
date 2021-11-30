<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Debitur</h2>
		<em>Debitur page</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Debitur Detail</h3></div>
			<div class="widget-content">	
				<div class="row">
					<div class="col-md-12">
						<?php if($data!=""): ?>
						<table class="table">
							<tbody>
								<tr><th width="150" class="text-right">Customer Code</th><td width="2">:</td><td><?=$data['cucode'];?></td></tr>
								<tr><th width="150" class="text-right">Name</th><td width="2">:</td><td><?=$data['cuname'];?></td></tr>
								<tr><th width="150" class="text-right">Adress</th><td width="2">:</td><td><?=$data['cuaddr'];?></td></tr>
								<tr><th width="150" class="text-right">Zip Code</th><td width="2">:</td><td><?=$data['cuzip'];?></td></tr>
								<tr><th width="150" class="text-right">Country Code</th><td width="2">:</td><td><?=$data['cncode'];?></td></tr>
								<tr><th width="150" class="text-right">Phone</th><td width="2">:</td><td><?=$data['cuphone'];?></td></tr>
								<tr><th width="150" class="text-right">Fax</th><td width="2">:</td><td><?=$data['cufax'];?></td></tr>
								<tr><th width="150" class="text-right">Contact</th><td width="2">:</td><td><?=$data['cucontact'];?></td></tr>
								<tr><th width="150" class="text-right">Email</th><td width="2">:</td><td><?=$data['cuemail'];?></td></tr>
								<tr><th width="150" class="text-right">NPWP</th><td width="2">:</td><td><?=$data['cunpwp'];?></td></tr>
								<tr><th width="150" class="text-right">SKADA</th><td width="2">:</td><td><?=$data['cuskada'];?></td></tr>
								<tr><th width="150" class="text-right">Debitur Code</th><td width="2">:</td><td><?=$data['cudebtur'];?></td></tr>
								<tr><th width="150" class="text-right">Type</th><td width="2">:</td><td><?=$data['cutype'];?></td></tr>
								<tr><th width="150" class="text-right">NPPKP</th><td width="2">:</td><td><?=$data['cunppkp'];?></td></tr>
							</tbody>
						</table>
						<?php else: ?>
							<p class="alert alert-warning">Something wrong!. Data not found.</p>
						<?php endif; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('debitur');?>" class="btn btn-default">Back</a>
						<a href="<?=site_url('debitur/edit/'.$data['cucode']);?>" class="btn btn-success">Edit</a>
					</div>
				</div>
			</div>		
		</div>		
		
	</div>
</div>

<?= $this->endSection();?>
