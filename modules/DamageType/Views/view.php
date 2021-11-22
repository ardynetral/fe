<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Damage Type</h2>
		<em>damage type page</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Damage Type Detail</h3></div>
			<div class="widget-content">	
				<div class="row">
					<div class="col-md-12">
						<?php if($damagetype!=""): ?>
						<table class="table">
							<tbody>
								<tr><th width="100">CODE</th><td width="2">:</td><td><?=$damagetype['dycode'];?></td></tr>
								<tr><th width="100">Description</th><td width="2">:</td><td><?=$damagetype['dydesc'];?></td></tr>
								</td></tr>
							</tbody>
						</table>
						<?php else: ?>
							<p class="alert alert-warning">Something wrong!. Data not found.</p>
						<?php endif; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('damagetype');?>"class="btn btn-default">Back</a>
						<a class="btn btn-success">Edit</a>
					</div>
				</div>
			</div>		
		</div>		
		
	</div>
</div>

<?= $this->endSection();?>
