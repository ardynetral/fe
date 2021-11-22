<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Container</h2>
		<em>container page</em>
	</div>

	<div class="main-content">

		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i>Container</h3></div>
			<div class="widget-content">
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('container/add');?>"class="btn btn-primary"><i class="fa fa-plus-square"></i>&nbsp;Add New</a>
					</div>
				</div><br>		
				<div class="row">
					<div class="col-md-12">
						<table id="ctTable" class="table table-hover" style="width:100%;">
							<thead>
								<tr>
									<th>#</th>
									<th>Container No.</th>
									<th>Type</th>
									<th>Lenght</th>
									<th>Height</th>
									<th></th>
								</tr>
							</thead>
							
							<tbody>
								<?php $i=1; foreach($container as $cr): ?>
								<tr>
									<td><?=$i;?></td>
									<td><?=$cr['crno'];?></td>
									<td><?=$cr['container_code']['ctcode'];?></td>
									<td><?=$cr['container_code']['cclength'];?></td>
									<td><?=$cr['container_code']['ccheight'];?></td>
									<td width="150">
										<a href="<?=site_url('container/view/'.$cr['crno']);?>" class="btn btn-xs btn-primary">View</a>
										<a href="<?=site_url('container/edit/'.$cr['crno'])?>" class="btn btn-xs btn-success">Edit</a>
										<a href="#" class="btn btn-xs btn-danger delete" data-kode="<?=$cr['crno']?>">Delete</a>
									</td>
								</tr>
								<?php $i++; endforeach; ?>
							</tbody>

						</table>
					</div>
				</div>
			</div>		
		</div>		
		
	</div>
</div>

<?= $this->endSection();?>

<!-- JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\Container\Views\js'); ?>	

<?= $this->endSection();?>