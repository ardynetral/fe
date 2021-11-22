<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Debitur</h2>
		<em>Debitur page</em>
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
				<h3><i class="fa fa-table"></i> Debitur</h3></div>
			<div class="widget-content">
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('debitur/add');?>"class="btn btn-primary"><i class="fa fa-plus-square"></i>&nbsp;Add New</a>
					</div>
				</div><br>	

				<?php if($data ==''): ?>
					<p class="alert alert-warning"> Data not found.</p>
				<?php else : ?>

				<div class="row">
					<div class="col-md-12">

						<br>
						<table id="ctTable" class="table table-hover table-bordered" style="width:100%;">
							<thead>
								<tr>
									<th>No.</th>
									<th>Customer Code</th>
									<th>Customer Name</th>
									<th></th>
								</tr>
							</thead>
							
							<tbody>
								<?php $i=1; foreach($data as $row): ?>
									<tr>
										<td><?=$i;?></td>
										<td><?=$row['cucode'];?></td>
										<td><?=$row['cuname'];?></td>
										<td width="150">
											<a href="<?=site_url('debitur/view/'.$row['cucode']);?>" class="btn btn-xs btn-primary">View</a>
											<a href="<?=site_url('debitur/edit/'.$row['cucode'])?>" class="btn btn-xs btn-success">Edit</a>
											<a href="#" class="btn btn-xs btn-danger delete" id="delete" data-kode="<?=$row['cucode'];?>">Delete</a>
										</td>
									</tr>
								<?php $i++; endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>

				<?php endif; ?>

			</div>		
		</div>		
		
	</div>
</div>

<?= $this->endSection();?>

<!-- JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\Debitur\Views\js'); ?>	
	
<?= $this->endSection();?>