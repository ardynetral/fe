<?php if($data_pra ==''): ?>
	<p class="alert alert-warning"> Data not found.</p>
<?php else : ?>

<div class="row">
	<div class="col-md-12">

		<br>
		<table id="ctTable" class="table table-hover table-bordered" style="width:100%;">
			<thead>
				<tr>
					<th>No.</th>
					<th>Pra In Ref.</th>
					<th>Date</th>
					<th>Vessel</th>
					<th>Voyage</th>
					<th></th>
				</tr>
			</thead>
			
			<tbody>
				<?php $i=1; foreach($data_pra as $row): ?>
					<tr>
						<td><?=$i;?></td>
						<td><?=$row['cpiorderno'];?></td>
						<td><?=$row['cpipratgl']?></td>
						<td><?=$row['cpives']?></td>
						<td><?=$row['cpivoyid']?></td>
						<td>
						<?php if(has_approval==true): ?>
<<<<<<< HEAD
            
							<?php if($row['appv']==0): ?>
								<a href="<?=site_url('prain/view/'.$row['praid']);?>" id="" class="btn btn-xs btn-default" data-praid="<?=$row['praid'];?>">view</a>
								<a href="<?=site_url('prain/approve_order/'.$row['praid']);?>" id="" class="btn btn-xs btn-primary" data-praid="<?=$row['praid'];?>">Approval</a>
								<?php if(has_delete==true): ?>
								<a href="#" id="deletePraIn" class="btn btn-xs btn-danger">delete</a>
								<?php endif; ?>											
=======
	
									<?php if($row['appv']==0): ?>
								<a href="<?=site_url('prain/view/'.$row['praid']);?>" id="" class="btn btn-xs btn-default" data-praid="<?=$row['praid'];?>">view</a>
									<a href="<?=site_url('prain/approve_order/'.$row['praid']);?>" id="" class="btn btn-xs btn-primary" data-praid="<?=$row['praid'];?>">Approval</a>
										<?php if(has_delete==true): ?>
										<a href="#" id="deletePraIn" class="btn btn-xs btn-danger">delete</a>
										<?php endif; ?>											
>>>>>>> cd1ad87e995100840895862f246b6cf14957748d
						
							<?php elseif($row['appv']==1): ?>
								<a href="<?=site_url('prain/proforma/'.$row['praid']);?>" id="" class="btn btn-xs btn-primary" data-praid="<?=$row['praid'];?>">Proforma</a>
								<a href="<?=site_url('prain/approval2/'.$row['praid']);?>" id="" class="btn btn-xs btn-success approve" data-praid="<?=$row['praid'];?>">Approval 2</a>
						
							<?php elseif($row['appv']==2): ?>
								<!-- <a href="<?=site_url('prain/view/'.$row['praid']);?>" id="" class="btn btn-xs btn-default" data-praid="<?=$row['praid'];?>">view</a> -->
								<a href="<?=site_url('prain/final_order/'.$row['praid']);?>" class="btn btn-xs btn-info">Cetak Kitir</a>
						
<<<<<<< HEAD
							<?php endif; ?>	
						
						<?php endif; ?>
=======
									<?php endif; ?>	
						
							<?php endif; ?>
>>>>>>> cd1ad87e995100840895862f246b6cf14957748d


						</td>
					</tr>
				<?php $i++; endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<?php endif; ?>	