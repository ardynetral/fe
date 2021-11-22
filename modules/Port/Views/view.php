<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Port</h2>
		<em>Port page</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Port Detail</h3></div>
			<div class="widget-content">	
				<div class="row">
					<div class="col-md-12">
						<?php if($data!=""): ?>
						<table class="table">
							<tbody>
								<tr><th width="100">ID</th><td width="2">:</td><td><?=$data['poid'];?></td></tr>
								<tr><th width="100">Port</th><td width="2">:</td><td><?=$data['poport'];?></td></tr>
								<tr><th width="100">Country</th><td width="2">:</td><td><?=$data['cncode'];?></td></tr>
								<tr><th width="100">Description</th><td width="2">:</td><td><?=$data['podesc'];?></td></tr>
							</tbody>
						</table>
						<?php else: ?>
							<p class="alert alert-warning">Something wrong!. Data not found.</p>
						<?php endif; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('port');?>" class="btn btn-default">Back</a>
						<a href="<?=site_url('port/edit/'.$data['poport']);?>" class="btn btn-success">Edit</a>
					</div>
				</div>
			</div>		
		</div>		
		
	</div>
</div>

<?= $this->endSection();?>
