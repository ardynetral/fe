<p>
	<button class="btn btn-success" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i>&nbsp;Add Container</button>
	<label class="btn btn-default">
		<i class="fa fa-upload"></i>&nbsp;Add From File
		<input type="file" name="fileXls" id="fileXls" style="display: none;">
	</label>
</p>
<div class="table-responsive vscroll">
<form id="fContainerFromFile" role="form">
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
			<th>F/E</th>
			<th>Seal No</th>
			<th>Remark</th>
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
						<a href="#" id="viewContainer" class="btn btn-xs btn-primary view" data-crid="<?=$row['pracrnoid']?>" data-toggle='modal' data-target='#myModal'>view</a>
						<?php else:?>
						<a href="#" id="editContainer" class="btn btn-xs btn-primary edit" data-crid="<?=$row['pracrnoid']?>" data-toggle="modal" data-target="#myModal">edit</a>
						<?php endif; ?>
					</td>
					<td><?=$i;?></td>
					<td><?=$row['crno'];?></td>
					<td><?=$row['cccode']?></td>
					<td><?=$row['ctcode']?></td>
					<td><?=$row['cclength']?></td>
					<td><?=$row['ccheight']?></td>
					<td><?=((isset($row['cpife'])&&$row['cpife']==1)?'Full':'Empty')?></td>
					<td><?=$row['sealno']?></td>
					<td><?=$row['cpiremark']?></td>
				</tr>
			<?php $i++; endforeach; ?>
		<?php endif; ?>
	</tbody>
</table>
</form>
</div>
<p class="text-center">
	<a href="#" class="btn btn-primary" id="insertContainerFromFile">&nbsp;<i class="fa fa-save"></i>&nbsp;Save Containers&nbsp;</a>
	<a href="<?=site_url('praout')?>" class="btn btn-default">&nbsp;<i class="fa fa-refresh"></i>&nbsp;BACK&nbsp;</a>
</p>