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
				<h3><i class="fa fa-edit"></i> <?=((isset($act))&&($act!="")?$act:"")?> Estimation</h3>
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
				<form id="fContract" class="form-horizontal" role="form">
					<?= csrf_field() ?>
					<fieldset>
					<table class="tbl-form" width="">
						<!-- 9 kolom -->
					<tbody>
						<tr>
							<td class="text-right" width="130">Location :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Componen :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Damage Type :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Repair Method :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Calculation Method :</td>
							<td>
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="cpife" id="cpife" value="1">
								<span><i></i>L</span>
								</label>
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="cpife" id="cpife" value="0">
								<span><i></i>S</span>
								</label>
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="cpife" id="cpife" value="1">
								<span><i></i>P</span>
								</label>
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="cpife" id="cpife" value="0">
								<span><i></i>Q</span>
								</label>
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="cpife" id="cpife" value="0">
								<span><i></i>B</span>
								</label>		
							</td>
						</tr>
						<tr>
							<td class="text-right" width="130">Calculation Method :</td>
							<td>
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="cpife" id="cpife" value="1">
								<span><i></i>B</span>
								</label>
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="cpife" id="cpife" value="1">
								<span><i></i>C</span>
								</label>
							</td>
						</tr>	
						<tr>
							<td class="text-right" width="130">Size :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Measurement :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Quantity :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Man Hour :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Currency :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Labour Cost :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Material Cost :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Total Cost :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Account :</td>
							<td>
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="cpife" id="cpife" value="1">
								<span><i></i>O</span>
								</label>
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="cpife" id="cpife" value="1">
								<span><i></i>U</span>
								</label>
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="cpife" id="cpife" value="1">
								<span><i></i>I</span>
								</label>
							</td>
						</tr>
						<tr>
							<td class="text-right" width="130">Upload File :</td>
							<td><input type="file" name="" class="form-control" id="" value="<?='';?>"></td>
						</tr>
						<tr><td colspan="2"></td></tr>
						<tr>
							<td></td>
							<td >
								<?php if(isset($act)&&($act=='view')):?>
								<!-- <button type="button" id="updateData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>&nbsp; -->
								<button type="button" id="cancel" class="btn btn-default"><i class="fa fa-ban"></i> Back</button>								
								<?php else: ?>
								<button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
								<button type="button" id="cancel" class="btn btn-default text-right"><i class="fa fa-ban"></i> Cancel</button>								
								<?php endif; ?>
							</td>
						</tr>
					</tbody>
				</table>
			</fieldset>
		</form>
	</div>
</div>

<?= $this->endSection();?>

<!-- Load JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\Estimation\Views\js'); ?>

<?= $this->endSection();?>