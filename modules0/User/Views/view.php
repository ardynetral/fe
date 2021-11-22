<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>User</h2>
		<em>user detail page</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> User Detail</h3></div>
			<div class="widget-content">

				<?php if($data!=""): ?>	

				<div class="row">
					<div class="col-md-12">	
						<table class="table">
							<tbody>
								<tr><th width="100">Fullname</th><td width="2">:</td><td><?=$data['fullname'];?></td></tr>
								<tr><th width="100">Username</th><td width="2">:</td><td><?=$data['username'];?></td></tr>
								<tr><th width="100">Email</th><td width="2">:</td><td><?=$data['email'];?></td></tr>
								<tr><th width="100">Group</th><td width="2">:</td><td><?=$data['groups']['group_name'];?></td></tr>

								<?php if($data['group_id']==2): ?>
								<tr><th width="100">Principal Code</th><td width="2">:</td><td><?=$data['prcode'];?></td></tr>
								<?php endif; ?>

								<tr><th width="100">Status User</th><td width="2">:</td>
									<td><?= ((isset($data['is_block']) && $data['is_block']=='y') ? 'Active' : 'Not Active'); ?></td></tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('user');?>" class="btn btn-default">Back</a>
						<a href="<?=site_url('user/edit/'.$data['user_id']);?>" class="btn btn-success">Edit</a>
					</div>
				</div>

				<?php else: ?>
					<p class="alert alert-warning">Something wrong!. Data not found.</p>
				<?php endif; ?>

			</div>		
		</div>		
		
	</div>
</div>

<?= $this->endSection();?>
