<div class="table-responsive vscroll">
<table id="detTable" class="table table-hover table-bordered" style="width:100%;">
	<thead>
		<tr>
			<th></th>
			<th>No.</th>
			<th>Container #</th>
			<th>ID Code</th>
			<th>Type</th>
			<th>Length</th>
			<th>Height</th>
			<th>Principal</th>
			<th>F/E</th>
			<th>Remark</th>
			<th>GateIn Date</th>
		</tr>
	</thead>
	
	<tbody id="listOrderPra">
		<?php if($data_prac==""): ?>
			<tr><td colspan="11"><p class="alert alert-warning">Data not found.</p></td></tr>
		<?php else: ?>

			<?php $i=1; foreach($data_prac as $row): ?>
				<tr>
					<td>
						<?php if(isset($act)&&($act=='add')):?>
						<a href="#" id="viewContainer" class="btn btn-xs btn-primary view" data-crid="<?=$row['pracrnoid']?>">view</a>
						<?php else:?>
						<a href="#" id="editContainer" class="btn btn-xs btn-primary edit" data-crid="<?=$row['pracrnoid']?>">edit</a>
						<?php endif; ?>
					</td>
					<td><?=$i;?></td>
					<td><?=$row['crno'];?></td>
					<td><?=$row['cccode']?></td>
					<td><?=$row['ctcode']?></td>
					<td><?=$row['cclength']?></td>
					<td><?=$row['ccheight']?></td>
					<td></td>
					<td><?=((isset($row['cpife'])&&$row['cpife']==1)?'Full':'Empty')?></td>
					<td><?=$row['cpiremark']?></td>
					<td></td>
				</tr>
			<?php $i++; endforeach; ?>
		<?php endif; ?>
	</tbody>
</table>
</div>
<p class="text-center">
	<a href="<?=site_url('prain')?>" class="btn btn-default">&nbsp;<i class="fa fa-refresh"></i>&nbsp;BACK&nbsp;</a>
</p>