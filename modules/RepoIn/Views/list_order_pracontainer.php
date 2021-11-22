
<?php if($data_container==""): ?>
	<p class="alert alert-warning">Data not found.</p>
<?php else: ?>
<table id="ctTable" class="table table-hover table-bordered" style="width:100%;">
	<thead>
		<tr>
			<th>No.</th>
			<th>Container #</th>
			<th>ID Code</th>
			<th>Type</th>
			<th>Length</th>
			<th>Height</th>
			<th>F/E</th>
			<th>Hold/Release</th>
			<th>Remark</th>
			<th>GateIn Date</th>
			<th></th>
		</tr>
	</thead>
	
	<tbody id="listOrderPra">
		<?php $i=1; foreach($data_container as $row): ?>
			<tr>
				<td><?=$i;?></td>
				<td><?=$row['crno'];?></td>
				<td><?=$row['cccode']?></td>
				<td><?=$row['ctcode']?></td>
				<td><?=$row['cclength']?></td>
				<td><?=$row['ccheight']?></td>
				<td><?=((isset($row['cpife'])&&$row['cpife']==1)?"Full":"Empty");?></td>
				<td><?=((isset($row['cpishold'])&&$row['cpishold']==1)?"Hold":"Release")?></td>
				<td><?=$row['cpiremark']?></td>
				<td></td>
				<td><a href="#" id="editPraContainer" class="btn btn-xs btn-primary">edit</a></td>
			</tr>
		<?php $i++; endforeach; ?>
	</tbody>
</table>
<?php endif; ?>