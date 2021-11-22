<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Users</h2>
		<em>user management page</em>
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
				<h3><i class="fa fa-table"></i> Users</h3></div>
			<div class="widget-content">
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('users/add');?>"class="btn btn-primary"><i class="fa fa-plus-square"></i>&nbsp;Add New</a>
					</div>
				</div><br>		
				<div class="row">
					<div class="col-md-12">
						<?php if($user==""): ?>
							<p class="alert alert-warning">Something wrong!. Data not found.</p>
						<?php else: ?>

						<table id="usrTable" class="table table-hover table-bordered" style="width:100%;">
							<thead>
								<tr>
									<th>No.</th>
									<th>Fullname</th>
									<th>Email</th>
									<th>Group</th>
									<th></th>
								</tr>
							</thead>
							
							<tbody>
								<?php $i=1; foreach($user as $u): ?>
								<tr>
									<td><?=$i;?></td>
									<td><?=$u['fullname'];?></td>
									<td><?=$u['email'];?></td>
									<td><?=$u['groups']['group_name'];?></td>
									<td width="150">
										<a href="<?=site_url('users/view/'.$u['user_id']);?>" class="btn btn-xs btn-primary">View</a>
										<a href="<?=site_url('users/edit/'.$u['user_id']);?>" class="btn btn-xs btn-default">Edit</a>
										<?php if($u['is_block']=="y"): ?>
										<a href="#" class="btn btn-xs btn-success" id="sendEmail" data-uid="<?=$u['user_id']?>">Send email</a>
										<?php else: ?>
											<span class="btn btn-xs disabled">Activated</span>
										<?php endif; ?>

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

	<?= $this->include('\Modules\User\Views\js'); ?>	
	
<?= $this->endSection();?>