<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<?php
if (isset($data) && ($data != '')) {
$codate = date('d/m/Y', strtotime($data['codate']));
$coexpdate = date('d/m/Y', strtotime($data['coexpdate']));
}
?>


<div class="content">
<div class="main-header">
<h2><?= $page_title; ?></h2>
<em><?= $page_subtitle; ?></em>
</div>
<div class="main-content">

<div class="widget">
	<div class="widget-header">
		<h3><i class="fa fa-edit"></i> <?= ((isset($act)) && ($act != "") ? $act : "") ?> Estimation</h3>
	</div>
	<div class="widget-content">

		<?php if (isset($validasi)) : ?>
			<div class="alert alert-danger">
				<?= $validasi; ?>
			</div>
		<?php endif; ?>

		<?php if (isset($message)) : ?>
			<p class="alert alert-danger">
				<?php echo $message; ?>
			</p>
		<?php endif; ?>
		<div id="alert">

		</div>
		<form id="fContract" class="form-horizontal" role="form">
			<?= csrf_field() ?>
			<?php $readonly = 'readonly'; ?>
			<fieldset>
				<table class="tbl-form" width="100%">
					<!-- 9 kolom -->
					<tbody>
						<tr>
							<td class="text-right" width="130">Container No :</td>
							<td><input type="text" name="crno" class="form-control" id="crno" value="<?= ''; ?>"></td>
							<td class="text-right">EOR No :</td>
							<td colspan="3"><input <?php echo $readonly; ?> type="text" name="rpnoest" id="rpnoest" class="form-control" value="<?= ''; ?>"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Date :</td>
							<td><input type="text" name="rptglest" class="form-control" id="rptglest" value="<?= ''; ?>"></td>
							<td class="text-right">Time :</td>
							<td colspan="3"><input type="text" name="rpjamest" id="rpjamest" class="form-control" value="<?= ''; ?>"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130">ID Code :</td>
							<td><input <?php echo $readonly; ?> type="text" name="cccode" class="form-control" id="cccode" value="<?= ''; ?>"></td>
							<td class="text-right">Type :</td>
							<td colspan="3"><input <?php echo $readonly; ?> type="text" name="ctcode" id="ctcode" class="form-control" value="<?= ''; ?>"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Lenght :</td>
							<td><input <?php echo $readonly; ?> type="text" name="cclength" class="form-control" id="cclength" value="<?= ''; ?>"></td>
							<td class="text-right">Height :</td>
							<td colspan="3"><input <?php echo $readonly; ?> type="text" name="ccheight" id="ccheight" class="form-control" value="<?= ''; ?>"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Contract No :</td>
							<td><input <?php echo $readonly; ?> type="text" name="" class="form-control" id="" value="<?= ''; ?>"></td>
							<td class="text-right">Expired :</td>
							<td colspan="3"><input <?php echo $readonly; ?> type="text" name="" id="" class="form-control" value="<?= ''; ?>"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Surveyor :</td>
							<td><input <?php echo $readonly; ?> type="text" name="" class="form-control" id="" value="<?= ''; ?>"></td>
							<td class="text-right">Survey Date :</td>
							<td colspan="3"><input <?php echo $readonly; ?> type="text" name="svsurdat" id="svsurdat" class="form-control" value="<?= ''; ?>"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Est Version :</td>
							<td><input <?php echo $readonly; ?> type="text" name="" class="form-control" id="" value="<?= ''; ?>"></td>
							<td class="text-right">Survey Condition :</td>
							<td colspan="3"><input <?php echo $readonly; ?> type="text" name="svcond" id="svcond" class="form-control" value="<?= ''; ?>"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td colspan="6"></td>
						</tr>
						<tr>
							<td></td>
							<td colspan="5">
								<?php if (isset($act) && ($act == 'view')) : ?>
									<!-- <button type="button" id="updateData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>&nbsp; -->
									<button type="button" id="cancel" class="btn btn-default"><i class="fa fa-ban"></i> Back</button>
								<?php else : ?>
									<button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;

									<a href="<?= site_url('estimation') ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
								<?php endif; ?>
							</td>
						</tr>
					</tbody>
				</table>
			</fieldset>
		</form>


		<div class="row">
			<!-- List Final Estimation -->
			<div class="col-lg-4">

				<div class="widget widget-table">
					<div class="widget-header">
						<h3><i class="fa fa-table"></i> Form Input Estimation Detail</h3>
					</div>
					<div class="widget-content">
						<form id="formDetail" class="form-horizontal" role="form">
							<input type="hidden" name="csrf_test_name" value="8cfe7a8c003a7f787f5f0eb4f24fb8f5"> <input type="hidden" name="pracrnoid" id="pracrnoid">
							<fieldset>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Location</label>
									<div class="col-sm-8">
										<?= $lccode_dropdown; ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Component</label>
									<div class="col-sm-8">
										<?= $cmcode_dropdown; ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Damage Type</label>
									<div class="col-sm-8">
										<?= $dycode_dropdown; ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Repair Method</label>
									<div class="col-sm-8">
										<?= $rmcode_dropdown; ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Calculation Method</label>
									<div class="col-sm-8">
										<label class="control-inline fancy-radio custom-bgcolor-green">
											<input type="radio" name="rdcalmtd" id="rdcalmtd" value="1" checked="">
											<span><i></i>L</span>
										</label>
										<label class="control-inline fancy-radio custom-bgcolor-green">
											<input type="radio" name="rdcalmtd" id="rdcalmtd" value="2">
											<span><i></i>S</span>
										</label>
										<label class="control-inline fancy-radio custom-bgcolor-green">
											<input type="radio" name="rdcalmtd" id="rdcalmtd" value="3">
											<span><i></i>P</span>
										</label>
										<label class="control-inline fancy-radio custom-bgcolor-green">
											<input type="radio" name="rdcalmtd" id="rdcalmtd" value="4">
											<span><i></i>Q</span>
										</label>
										<label class="control-inline fancy-radio custom-bgcolor-green">
											<input type="radio" name="rdcalmtd" id="rdcalmtd" value="5">
											<span><i></i>B</span>
										</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Size</label>
									<div class="col-sm-8">
										<input type="text" name="rdsize" class="form-control" id="rdsize">
										<i class="err-crno text-danger" style="display: none;"></i>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Measurement Unit</label>
									<div class="col-sm-8">
										<input type="text" name="muname" class="form-control" id="muname">
										<i class="err-crno text-danger" style="display: none;"></i>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Quantity</label>
									<div class="col-sm-8">
										<input type="text" name="rdqtyact" class="form-control" id="rdqtyact">
										<i class="err-crno text-danger" style="display: none;"></i>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Man Hour</label>
									<div class="col-sm-8">
										<input type="text" name="rdmhr" class="form-control" id="rdmhr">
										<i class="err-crno text-danger" style="display: none;"></i>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Currency</label>
									<div class="col-sm-8">
										<input type="text" name="tucode" class="form-control" id="tucode">
										<i class="err-crno text-danger" style="display: none;"></i>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Material Cost</label>
									<div class="col-sm-8">
										<input type="text" name="rdmat" class="form-control" id="rdmat">
										<i class="err-crno text-danger" style="display: none;"></i>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Total Cost</label>
									<div class="col-sm-8">
										<input type="text" name="rdtotal" class="form-control" id="rdtotal">
										<i class="err-crno text-danger" style="display: none;"></i>
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Account</label>
									<div class="col-sm-8">
										<label class="control-inline fancy-radio custom-bgcolor-green">
											<input type="radio" name="rdaccount" id="rdaccount" value="1" checked="">
											<span><i></i>O</span>
										</label>
										<label class="control-inline fancy-radio custom-bgcolor-green">
											<input type="radio" name="rdaccount" id="rdaccount" value="0">
											<span><i></i>U</span>
										</label>
									</div>
								</div>
								<div class="form-group">
									<label for="cpideliver" class="col-sm-4 control-label text-right">File Upload</label>
									<div class="col-sm-6">
										<input type="file" name="files[]" class="form-control" id="files" multiple="true" required="">
									</div>
								</div>







								<div class="form-group">
									<div class="col-sm-offset-4 col-sm-8">
										<button type="button" id="saveDetail" class="btn btn-primary" disabled=""><i class="fa fa-check-circle"></i> Save</button>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<!-- List Final Estimation -->
			<div class="col-lg-8">
				<button type="button" id="addDetail" class="btn btn-success"><i class="fa fa-list"></i> Add Detail</button>
				<div class="widget widget-table">
					<div class="widget-header">
						<h3><i class="fa fa-table"></i> List Final Estimation</h3>
					</div>
					<div class="widget-content">
						<table class="table" id="tblList_add">
							<thead>
								<tr>
									<th>No</th>
									<th>COM</th>
									<th>DT</th>
									<th>RM</th>
									<th>CM</th>
									<th>BCE</th>
									<th>SIZE</th>
									<th>MU</th>
									<th>QTY</th>
									<th>MHR</th>
									<th>CUR</th>
									<th>DESC</th>
									<th>Lab. Cost</th>
									<th>Mat. Cost</th>
								</tr>
							</thead>
							<!-- <tbody>
						<tr>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</tbody> -->
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?= $this->endSection(); ?>

<!-- Load JS -->
<?= $this->Section('script_js'); ?>

<?= $this->include('\Modules\Estimation\Views\js'); ?>

<?= $this->endSection(); ?>