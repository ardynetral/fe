
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
					<th>Ref in No.</th>
					<th></th>
				</tr>
			</thead>
			
			<tbody>
				<?php $i=1; foreach($data_pra as $row): ?>
				<?php if(substr($row['cpiorderno'],0,2)=="PI"): ?>
					<tr>
						<td><?=$i;?></td>
						<td><?=$row['cpiorderno'];?></td>
						<td><?=$row['cpipratgl']?></td>
						<td><?=$row['cpives']?></td>
						<td><?=$row['cpivoyid']?></td>
						<td><?=$row['cpirefin']?></td>
						<td>
						<?php if($row['appv']==0): ?>

							<a href="<?=site_url('prain/edit/'.$row['praid']);?>" id="editPraIn" class="btn btn-xs btn-warning">edit</a>

							<?php if($group_id!=1): ?>
							<a href="<?=site_url('prain/approve_order/'.$row['praid']);?>" id="" class="btn btn-xs btn-primary" data-praid="<?=$row['praid'];?>">Approval</a>
							<?php endif; ?>
								
							<a href="#" id="" class="btn btn-xs btn-danger delete" data-kode="<?=$row['praid']?>">delete</a>
								
						<?php elseif($row['appv']==1): ?>

							<a href="<?=site_url('prain/proforma/'.$row['praid']);?>" id="" class="btn btn-xs btn-primary" data-praid="<?=$row['praid'];?>">Proforma</a>
							
							<?php if((check_bukti_bayar($row['praid'])==true)&&($group_id!=1)):?>
							<a href="<?=site_url('prain/approval2/'.$row['praid']);?>" id="" class="btn btn-xs btn-success approve" data-praid="<?=$row['praid'];?>">Approval 2</a>
							<?php endif;?>

						<?php elseif($row['appv']==2): ?>
							<a href="<?=site_url('prain/view/'.$row['praid']);?>" id="" class="btn btn-xs btn-default" data-praid="<?=$row['praid'];?>">view</a>
							<a href="<?=site_url('prain/final_order/'.$row['praid']);?>" class="btn btn-xs btn-info">Cetak kitir</a>

						<?php endif; ?>
					</td>
					</tr>
				<?php $i++; endif; ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>


<?php endif; ?>	