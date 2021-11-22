<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Container Code</h2>
		<em>container code page</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Container Code Detail</h3></div>
			<div class="widget-content">	
				<div class="row">
					<div class="col-md-12">
						<?php if($ccode!=""): ?>
						<table class="table">
							<tbody>
								<tr><th width="100">ID CODE</th><td width="2">:</td><td><?=$ccode['cccode'];?></td></tr>
								<tr><th width="100">Type</th><td width="2">:</td><td><?=$ccode['ctcode'];?></td></tr>
								<tr><th width="100">Length</th><td width="2">:</td><td><?=$ccode['length'];?></td></tr>
								<tr><th width="100">Height</th><td width="2">:</td><td><?=$ccode['height'];?></td></tr>
							</tbody>
						</table>
						<?php else: ?>
							<p class="alert alert-warning">Something wrong!. Data not found.</p>
						<?php endif; ?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('ccode');?>"class="btn btn-default">Back</a>
						<a class="btn btn-success">Edit</a>
					</div>
				</div>
			</div>		
		</div>		
		
	</div>
</div>

<?= $this->endSection();?>
