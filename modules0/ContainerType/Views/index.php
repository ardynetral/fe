<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Container Type</h2>
		<em>container type page</em>
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
				<h3><i class="fa fa-table"></i> Container Type</h3></div>
			<div class="widget-content">
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('ctype/add');?>"class="btn btn-primary"><i class="fa fa-plus-square"></i>&nbsp;Add New</a>
					</div>
				</div><br>		
				<div class="row">
					<div class="col-md-12">
						<table>
							<tbody>
								<tr><th width="100">Type</th><td width="2">:</td>
									<td></td></tr>
								<tr><th>Description</th><td>:</td>
									<td></td></tr>
							</tbody>
						</table>
						<br>
						<table id="ctTable" class="table table-hover table-bordered" style="width:100%;">
							<thead>
								<tr>
									<th>No.</th>
									<th>Code</th>
									<th>Description</th>
									<th></th>
								</tr>
							</thead>
							

							<tbody>
								<?php $i=1; foreach($ctype as $ct): ?>
								<tr>
									<td><?=$i;?></td>
									<td><?=$ct['ctcode'];?></td>
									<td><?=$ct['ctdesc'];?></td>
									<td width="150">
										<a href="<?=site_url('ctype/view/'.$ct['ctcode']);?>" class="btn btn-xs btn-primary">View</a>
										<a href="<?=site_url('ctype/edit/'.$ct['ctcode'])?>" class="btn btn-xs btn-success">Edit</a>
										<a href="<?=site_url('ctype/delete/'.$ct['ctcode'])?>" class="btn btn-xs btn-danger" id="delete" data-kode="<?=$ct['ctcode'];?>">Delete</a>
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
<script type="text/javascript">
$(document).ready(function(){
	$("#ctTable").DataTable({
	    select: {
	        style: 'single'
	    }
	});

});
</script>			
<?= $this->endSection();?>