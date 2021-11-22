<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Contract</h2>
		<em>Contract page</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Contract Detail</h3></div>
			<div class="widget-content">	
				<div class="row">
					<div class="col-md-12">
						<?php if($data!=""): ?>
						<table class="table">
							<tbody>
								<tr><th width="100">Name</th><td width="2">:</td><td><?=$data['Contract_name'];?></td></tr>
								<tr><th width="100">Country</th><td width="2">:</td><td><?=$data['cncode'];?></td></tr>
							</tbody>
						</table>
						<?php else: ?>
							<p class="alert alert-warning">Something wrong!. Data not found.</p>
						<?php endif; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('Contract');?>" class="btn btn-default">Back</a>
						<a href="<?=site_url('Contract/edit/'.$data['Contract_id']);?>" class="btn btn-success">Edit</a>
					</div>
				</div>
			</div>		
		</div>		
		
	</div>
</div>

<?= $this->endSection();?>
