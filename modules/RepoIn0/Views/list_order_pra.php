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
					<th>Principal</th>
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
						<td><?=$row['cpopr']?></td>
						<td><?=$row['cpives']?></td>
						<td><?=$row['cpivoyid']?></td>
						<td>
							<?php if($row['cpilunas']==1): ?>
							<a href="#" id="deletePraIn" class="btn btn-xs btn-primary">view</a>
							<?php elseif($row['cpilunas']==0): ?>
							<a href="#" id="deletePraIn" class="btn btn-xs btn-primary">view</a>
							<!-- <a href="#" id="deletePraIn" class="btn btn-xs btn-success">edit</a> -->
							<a href="#" class="btn btn-xs btn-info print_order" data-praid="<?=$row['praid']?>">print</a>
							<a href="#" id="deletePraIn" class="btn btn-xs btn-danger">delete</a>
							<?php endif; ?>
						</td>
					</tr>
				<?php $i++; endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<?php endif; ?>	