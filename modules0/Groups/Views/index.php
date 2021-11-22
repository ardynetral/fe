<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Groups</h2>
		<em>user groups page</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> User Groups</h3></div>
			<div class="widget-content">
				<div class="row">
					<div class="col-md-6">
						<table class="table">
							<thead>
								<tr>
									<th>#</th>
									<th>Group Name</th>
									<th>Group ID</th>
									<th>Description</th>
								</tr>
							</thead>
							<tbody>
								<?php $i=1; foreach($ugroup as $ug): ?>
								<tr>
									<td><?=$i;?></td>
									<td><?=$ug['group_name'];?></td>
									<td><?=$ug['group_id'];?></td>
									<td><?=$ug['description'];?></td>
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