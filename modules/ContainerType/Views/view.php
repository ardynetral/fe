<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Container Type</h2>
		<em>container type page</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Container Type Detail</h3></div>
			<div class="widget-content">	
				<div class="row">
					<div class="col-md-12">
						<table class="table">
							<tbody>
								<tr><th width="100">CODE</th><td width="2">:</td><td><?=$ctype['ctcode'];?></td></tr>
								<tr><th>DESCRIPTION</th><td>:</td><td><?=$ctype['ctdesc'];?></td></tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('containertype');?>"class="btn btn-default">Back</a>
						<a class="btn btn-success">Edit</a>
					</div>
				</div>
			</div>		
		</div>		
		
	</div>
</div>

<?= $this->endSection();?>