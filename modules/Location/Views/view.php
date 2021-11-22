<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Location</h2>
		<em>Location page</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Location Detail</h3></div>
			<div class="widget-content">	
				<div class="row">
					<div class="col-md-12">
						<?php if($data!=""): ?>
						<table class="table">
							<tbody>
								<tr><th width="100">Location Code</th><td width="2">:</td><td><?=$data['lccode'];?></td></tr>
								<tr><th width="100">Description</th><td width="2">:</td><td><?=$data['lcdesc'];?></td></tr>
							</tbody>
						</table>
						<?php else: ?>
							<p class="alert alert-warning">Something wrong!. Data not found.</p>
						<?php endif; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('location');?>" class="btn btn-default">Back</a>
						<a href="<?=site_url('location/edit/'.$data['lccode']);?>" class="btn btn-success">Edit</a>
					</div>
				</div>
			</div>		
		</div>		
		
	</div>
</div>

<?= $this->endSection();?>
