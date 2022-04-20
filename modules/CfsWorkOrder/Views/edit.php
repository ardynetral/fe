<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<?php
if(isset($data) && ($data!='')) {
	$codate = date('d/m/Y',strtotime($data['codate']));
	$coexpdate = date('d/m/Y',strtotime($data['coexpdate']));
}
?>


<div class="content">
	<div class="main-header">
		<h2><?=$page_title;?></h2>
		<em><?=$page_subtitle;?></em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> <?=((isset($act))&&($act!="")?$act:"")?>&nbsp;<?=$page_title;?></h3>
			</div>
			<div class="widget-content">

				<?php if(isset($validasi)):?>
					<div class="alert alert-danger">
						<?=$validasi; ?>
					</div>
				<?php endif; ?>

				<?php if(isset($message)): ?>
					<p class="alert alert-danger">
						<?php echo $message;?>
					</p>
				<?php endif;?>
				<div id="alert">
					
				</div>
				<form id="fWO" class="form-horizontal" role="form">
					<?= csrf_field() ?>
					<fieldset>
					<table class="tbl-form" width="100%">
						<!-- 9 kolom -->
					<tbody>
						<tr>
							<td class="text-right" width="130"><label for="cono" class="text-right">WO No :</label></td>
							<td width="300"><input type="text" name="wono" class="form-control" id="wono" value="<?=$header['wono'];?>" readonly></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="wodate" class="text-right">WO Date :</label></td>
							<td><input type="text" name="wodate" class="form-control" id="wodate" value="<?= date('d-m-Y',strtotime($header['wodate']));?>" readonly></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="woto" class="text-right">To :</label></td>
							<td><input type="text" name="woto" class="form-control" id="woto" value="<?=@$header['woto'];?>" required></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="wofrom" class="text-right">From :</label></td>
							<td><input type="text" name="wofrom" class="form-control" id="wofrom" value="<?=@$header['wofrom'];?>" required></td>
							<td></td>
						</tr>		
						<tr>
							<td class="text-right" width="130"><label for="wocc" class="text-right">CC :</label></td>
							<td><input type="text" name="wocc" class="form-control" id="wocc" value="<?=@$header['wocc'];?>" required></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="wocc" class="text-right">Notes :</label></td>
							<td><input type="text" name="wonotes" class="form-control" id="wonotes" value="<?=@$header['wonotes'];?>" required></td>
							<td></td>
						</tr>									
						<tr>
							<td class="text-right" width="130"><label for="woopr" class="text-right">Principal :</label></td>
							<td><?=principal_dropdown(@$header['woopr']);?></td>
							<td></td>
						</tr>	
						<tr>
							<td class="text-right" width="130"><label for="wotype" class="text-right">Condition Box :</label></td>
							<td>
								<!-- <input type="text" name="cono" class="form-control" id="cono" value="<?=@$data['cono'];?>"> -->
								<select name="wotype" id="wotype" class="input form-control" required>
									<option value="">- select -</option>
									<option value='AU' <?php echo $select = (@$header['wotype'] == 'AU') ? 'selected="selected"' : ''; ?>>AU</option>
									<option value='DN' <?php echo $select = (@$header['wotype'] == 'DN') ? 'selected="selected"' : ''; ?>>DN</option>
								</select>
							</td>
							<td></td>
						</tr>													
						<tr>
							<td></td>
							<td colspan="8">
								<button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>&nbsp;
								<a href="<?=site_url('wo')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
							</td>
						</tr>						
					</tbody>
					</table>
					</fieldset>
				</form>

				<legend>Header Status Container WR(Waiting Repair)</legend>
				<!-- <p><button type="button" class="btn btn-success" id="checkAll" disabled><i class="fa fa-list"></i> SAVE ALL CONTAINER</button></p> -->
				<div class="table-responsive vscroll">
				<table id="tblDetail" class="table">
					<thead>
						<tr><th width="20" style="display: none;"></th>
							<th width="20">No.</th>
							<th>Container No.</th>
							<th>Type</th>
							<th>Length</th>
							<th>Height</th>
							<th></th>
						</tr>
					</thead>
					<tbody>

						<?php 
						if($detail != NULL) :
						$no=1; 
						foreach($detail as $dt): ?>

						<tr>
						<td style="display:none;"><input type="checkbox" name="checked_cr" class="checked_cr" value="0" ></td>
						<td><?= $no ?></td>
						<td><?= $dt['crno'] ?></td>
						<td><?= $dt['ctcode'] ?></td>
						<td><?= $dt['cclength'] ?></td>
						<td><?= $dt['ccheight'] ?></td>
						<td style="display:none;"><?= $dt['svid'] ?></td>
						<td><a href="#" class="btn btn-xs btn-danger delete" data-svid="<?=$dt['svid']?>" data-crno="<?=$dt['crno']?>">delete</a></td>
						</tr>						
						<?php 
						$no++;
						endforeach; 
						endif;?>
					</tbody>
				</table>
				</div>
			</div>
		</div>
		<!-- end .widget -->
	</div>
</div>

<?= $this->endSection();?>

<!-- Load JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\CfsWorkOrder\Views\js'); ?>

<?= $this->endSection();?>