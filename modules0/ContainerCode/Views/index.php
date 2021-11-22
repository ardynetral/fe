<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Container Code</h2>
		<em>container code page</em>
	</div>

	<?php if(session()->getFlashdata('sukses')):?>
	<div class="alert alert-success alert-dismissable">
		<a href="" class="close">×</a>
		<strong><?=session()->getFlashdata('sukses');?></strong>
	</div>
	<?php endif; ?>

	<div class="main-content">

		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Container Code</h3></div>
			<div class="widget-content">
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('ccode/add');?>"class="btn btn-primary"><i class="fa fa-plus-square"></i>&nbsp;Add New</a>
					</div>
				</div><br>		
				<div class="row">
					<div class="col-md-12">
						<?php if($ccode==""): ?>
							<p class="alert alert-warning">Something wrong!. Data not found.</p>
						<?php else: ?>
						<table>
							<tbody>
								<tr><th width="100">ID Code</th><td width="2">:</td>
									<td></td></tr>
							</tbody>
						</table>
						<br>
						<table id="ctTable" class="table table-hover table-bordered" style="width:100%;">
							<thead>
								<tr>
									<th>No.</th>
									<th>ID Code</th>
									<th>Type</th>
									<th>Length</th>
									<th>Height</th>
									<th></th>
								</tr>
							</thead>
							
							<tbody>
								<?php $i=1; foreach($ccode as $cc): ?>
								<tr>
									<td><?=$i;?></td>
									<td><?=$cc['cccode'];?></td>
									<td><?=$cc['ctcode'];?></td>
									<td><?=$cc['cclength'];?></td>
									<td><?=$cc['ccheight'];?></td>
									<td width="150">
										<a href="<?=site_url('ccode/view/'.$cc['cccode']);?>" class="btn btn-xs btn-primary">View</a>
										<a href="<?=site_url('ccode/edit/'.$cc['cccode'])?>" class="btn btn-xs btn-success">Edit</a>
										<a href="<?=site_url('ccode/delete/'.$cc['cccode'])?>" class="btn btn-xs btn-danger" id="delete" data-kode="<?=$cc['cccode'];?>">Delete</a>
									</td>
								</tr>
								<?php $i++; endforeach; ?>
							</tbody>
						</table>

						<?php endif; ?>

					</div>
				</div>
			</div>		
		</div>		
		
	</div>
</div>

<?= $this->endSection();?>

<!-- JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\ContainerCode\Views\js'); ?>	
	
<?= $this->endSection();?>