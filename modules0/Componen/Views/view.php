<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Component</h2>
		<em>component page</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Component Detail</h3></div>
			<div class="widget-content">	
				<div class="row">
					<div class="col-md-12">
						<?php if($data!=""): ?>
						<table class="table">
							<tbody>
								<tr><th width="200">Component Code</th><td width="2">:</td><td><?=$data['cmcode'];?></td></tr>
								<tr><th width="200">Description</th><td width="2">:</td><td><?=$data['cmdesc'];?></td></tr>
								<tr><th width="200">Component Code SSL</th><td width="2">:</td><td><?=$data['cmcode_ssl_ext'];?></td></tr>
							</tbody>
						</table>
						<?php else: ?>
							<p class="alert alert-warning">Something wrong!. Data not found.</p>
						<?php endif; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('componen');?>" class="btn btn-default">Back</a>
						<a href="<?=site_url('componen/edit/'.$data['cmcode']);?>" class="btn btn-success">Edit</a>
					</div>
				</div>
			</div>		
		</div>		
		
	</div>
</div>

<?= $this->endSection();?>
