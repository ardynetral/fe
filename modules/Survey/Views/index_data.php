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
					<th>Container No</th>
					<th>Principal</th>
					<th>In Date</th>
					<th>Survey Date</th>
					<th>Condition</th>
					<th></th>
				</tr>
			</thead>
			
			<tbody>
				<?php $i=1; 
				foreach($data as $row): ?>
					<tr>
						<td><?=$nomor++;?></td>
						<td><?=$row['CRNO']?></td>
						<td><?=$row['PRCODE']?></td>
						<td><?=$row['CPITGL']?></td>
						<td><?=$row['SVSURDAT']?></td>
						<td><?=$row['SVCOND']?></td>
						<td>
							<a href="#" id="" class="btn btn-xs btn-primary" data-praid="">view</a>

							<?php if(has_edit==true): ?>
							<a href="#" id="editPraIn" class="btn btn-xs btn-success">edit</a>
							<?php endif; ?>
							
							<?php if(has_print==true): ?>
							<a href="#" class="btn btn-xs btn-info" data-praid="">print</a>
							<?php endif; ?>

							<?php if(has_delete==true): ?>
							<a href="#" id="deletePraIn" class="btn btn-xs btn-danger">delete</a>
							<?php endif; ?>

						</td>
					</tr>
				<?php $i++; endforeach; ?>
			</tbody>
		</table>

	</div>
	<div class="col-sm-12">
		<p><?=$pager->links('survey');?></p>
	</div>			
</div>

<?php endif; ?>	