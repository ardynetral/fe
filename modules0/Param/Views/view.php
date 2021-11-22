<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Param</h2>
		<em>Param page</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Param Detail</h3></div>
			<div class="widget-content">	
				<div class="row">
					<div class="col-md-12">
						<?php if($data!=""): ?>
						<table class="table">
							<tbody>
								<tr><th width="100">Param ID</th><td width="2">:</td><td><?=$data['param_id'];?></td></tr>
								<tr><th width="100">Tab</th><td width="2">:</td><td><?=$data['tabs'];?></td></tr>
								<tr><th width="100">Param</th><td width="2">:</td><td><?=$data['param'];?></td></tr>
								<tr><th width="100">Description</th><td width="2">:</td><td><?=$data['description'];?></td></tr>
							</tbody>
						</table>
						<?php else: ?>
							<p class="alert alert-warning">Something wrong!. Data not found.</p>
						<?php endif; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('param');?>" class="btn btn-default">Back</a>
						<a href="<?=site_url('param/edit/'.$data['id']);?>" class="btn btn-success">Edit</a>
					</div>
				</div>
			</div>		
		</div>		
		
	</div>
</div>

<?= $this->endSection();?>
