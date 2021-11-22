<table id="detTable" class="table table-hover table-bordered" style="width:100%;">
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
		<?php if($data_prac==""): ?>
			<tr><td colspan="11"><p class="alert alert-warning">Data not found.</p></td></tr>
		<?php else: ?>

			<?php $i=1; foreach($data_prac as $row): ?>
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
					<td><a href="#" id="editContainer" class="btn btn-xs btn-primary edit" data-crid="<?=$row['pracrnoid']?>">edit</a></td>
				</tr>
			<?php $i++; endforeach; ?>
		<?php endif; ?>
	</tbody>
</table>