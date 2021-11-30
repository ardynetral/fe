<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Principal</h2>
		<em>Principal page</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Principal Detail</h3></div>
			<div class="widget-content">	
				<div class="row">
					<div class="col-md-12">
						<?php if($data!=""): ?>
						<table class="table">
							<tbody>
								<tr><th width="100">Principal Code</th><td width="2">:</td><td><?=$data['prcode'];?></td></tr>
								<tr><th width="100">Customer Code</th><td width="2">:</td><td><?=$data['cucode'];?></td></tr>
								<tr><th width="100">Name</th><td width="2">:</td><td><?=$data['prname'];?></td></tr>
								<tr><th width="100">Address</th><td width="2">:</td><td><?=$data['praddr'];?></td></tr>
								<tr><th width="100">Country</th><td width="2">:</td><td><?=$data['cncode'];?></td></tr>
								<tr><th width="100">Remark</th><td width="2">:</td><td><?=$data['prremark'];?></td></tr>
							</tbody>
						</table>
						<?php else: ?>
							<p class="alert alert-warning">Something wrong!. Data not found.</p>
						<?php endif; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('damageprogress');?>" class="btn btn-default">Back</a>
						<a href="<?=site_url('damageprogress/edit/'.$data['prcode']);?>" class="btn btn-success">Edit</a>
					</div>
				</div>
			</div>		
		</div>		
		
	</div>
</div>

<?= $this->endSection();?>
