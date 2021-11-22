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
					<th>Repo In Ref.</th>
					<th>Date</th>
					<th>Principal</th>
					<th>Vessel</th>
					<th>Voyage</th>
					<th></th>
				</tr>
			</thead>
			
			<tbody>
				<?php $i=1; foreach($data as $row): ?>
					<tr>
						<td><?=$nomor++;?></td>
						<td><?=$row['CPIPRANO'];?></td>
						<td><?=$row['CPIPRATGL']?></td>
						<td><?=$row['CPOPR']?></td>
						<td><?=$row['VESID']?></td>
						<td><?=$row['CPIVOY']?></td>
						<td>
							<a href="#" id="deleteRepoIn" class="btn btn-xs btn-primary">view</a>
							<!-- <a href="#" id="deletePraIn" class="btn btn-xs btn-success">edit</a> -->
							<a href="#" class="btn btn-xs btn-info print_order" data-repoid="<?=$row['CPID']?>">print</a>
							<a href="#" id="deleteRepoIn" class="btn btn-xs btn-danger">delete</a>
						</td>
					</tr>
				<?php $i++; endforeach; ?>
			</tbody>
		</table>
	</div>
	<div class="col-sm-12">
		<?=$pager->links('repoin');?>
	</div>		
</div>

<?php endif; ?>	