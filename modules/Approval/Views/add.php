<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
<div class="main-header">
<h2><?= $page_title; ?></h2>
<em><?= $page_subtitle; ?></em>
</div>
<div class="main-content">

<div class="widget">
	<div class="widget-header">
		<h3><i class="fa fa-edit"></i> <?= ((isset($act)) && ($act != "") ? $act : "") ?> Approval</h3>
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
		<form id="fEstimasi" class="form-horizontal" role="form" method="POST">
			<?= csrf_field() ?>
			<?php $readonly = 'readonly'; ?>
			<input type="hidden" name="svid" id="svid">
<!-- 			<input type="hidden" name="syid" id="syid"> -->
			<input type="hidden" name="rpcrton" id="rpcrton">
			<input type="hidden" name="rpcrtby" id="rpcrtby">
			<fieldset>
				<table class="tbl-form" width="100%">
					<!-- 9 kolom -->
					<tbody>
						<tr>
							<td class="text-right" width="130">Container No :</td>
							<td><input type="text" name="rpcrno" class="form-control" id="rpcrno" value="<?= ''; ?>">
								<i class="err-crno text-danger"></i></td>
							<td class="text-right">EOR No :</td>
							<td colspan="3"><input <?php echo $readonly; ?> type="text" name="rpnoest" id="rpnoest" class="form-control" value="<?= ''; ?>" readonly></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Date :</td>
							<td><input type="text" name="rptglest" class="form-control" id="rptglest" value="<?= date('d-m-Y'); ?>" readonly></td>
							<td class="text-right">Time :</td>
							<td colspan="3"><input type="text" name="rpjamest" id="rpjamest" class="form-control" value="<?= date('H:i:s'); ?>" readonly></td>
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
							<td><input <?php echo $readonly; ?> type="text" name="cono" class="form-control" id="cono" value="<?= ''; ?>"></td>
							<td class="text-right">Expired :</td>
							<td colspan="3"><input <?php echo $readonly; ?> type="text" name="coexpdate" id="coexpdate" class="form-control" value="<?= ''; ?>"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Surveyor :</td>
							<td><input <?php echo $readonly; ?> type="text" name="syid" class="form-control" id="syid" value="<?= ''; ?>"></td>
							<td class="text-right">Survey Date :</td>
							<td colspan="3"><input <?php echo $readonly; ?> type="text" name="svsurdat" id="svsurdat" class="form-control" value="<?= ''; ?>"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Est Version :</td>
							<td><input <?php echo $readonly; ?> type="text" name="rpver" class="form-control" id="rpver" value="<?= ''; ?>"></td>
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
							</td>
						</tr>
					</tbody>
				</table>
			</fieldset>
		</form>

		<div class="row">
			<!-- List Final Estimation -->

<!-- start modal -->
<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">??</button>
				<h4 class="modal-title" id="myModalLabel">Form Add Estimation Detail</h4>
			</div>
			<div class="modal-body">

						<form method="post" id="formDetail" class="form-horizontal" role="form"  enctype="multipart/form-data">
							<fieldset>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Location</label>
									<div class="col-sm-8">
										<input type="hidden" name="act" id="act" value="add">
										<input type="hidden" name="det_crno" id="det_crno">
										<input type="hidden" name="det_svid" id="det_svid">
										<input type="hidden" name="rpid" id="rpid">
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
											<input type="radio" name="rdcalmtd" id="rdcalmtd" value="L">
											<span><i></i>L</span>
										</label>
										<label class="control-inline fancy-radio custom-bgcolor-green">
											<input type="radio" name="rdcalmtd" id="rdcalmtd" value="S">
											<span><i></i>S</span>
										</label>
										<label class="control-inline fancy-radio custom-bgcolor-green">
											<input type="radio" name="rdcalmtd" id="rdcalmtd" value="P">
											<span><i></i>P</span>
										</label>
										<label class="control-inline fancy-radio custom-bgcolor-green">
											<input type="radio" name="rdcalmtd" id="rdcalmtd" value="Q">
											<span><i></i>Q</span>
										</label>
										<label class="control-inline fancy-radio custom-bgcolor-green">
											<input type="radio" name="rdcalmtd" id="rdcalmtd" value="B">
											<span><i></i>B</span>
										</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Size</label>
									<div class="col-sm-8">
										<input type="text" name="rdsize" class="form-control" id="rdsize">
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Measurement Unit</label>
									<div class="col-sm-8">
										<!-- <input type="text" name="muname" class="form-control" id="muname"> -->
										<select name="muname" class="form-control" id="muname">
											<option value=""> - select - </option>
											<option value="%">%</option>
											<option value="20">20</option>
											<option value="40">40</option>
											<option value="cm">cm</option>
											<option value="cm2">cm2</option>
											<option value="ft">ft</option>
											<option value="ft2">ft2</option>
											<option value="m">m</option>
											<option value="m2">m2</option>
											<option value="mm">mm</option>
											<option value="pc">pc</option>
											<option value="pcs">pcs</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Quantity</label>
									<div class="col-sm-8">
										<input type="text" name="rdqtyact" class="form-control" id="rdqtyact">
										
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Man Hour</label>
									<div class="col-sm-8">
										<input type="text" name="rdmhr" class="form-control" id="rdmhr">
										
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Currency</label>
									<div class="col-sm-8">
										<?=currency_dropdown2('tucode','');?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Material Cost</label>
									<div class="col-sm-8">
										<input type="text" name="rdmat" class="form-control" id="rdmat">
										
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Total Cost</label>
									<div class="col-sm-8">
										<input type="text" name="rdtotal" class="form-control" id="rdtotal">
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Account</label>
									<div class="col-sm-8">
										<label class="control-inline fancy-radio custom-bgcolor-green">
											<input type="radio" name="rdaccount" id="rdaccount" value="O">
											<span><i></i>O</span>
										</label>
										<label class="control-inline fancy-radio custom-bgcolor-green">
											<input type="radio" name="rdaccount" id="rdaccount" value="U" checked="">
											<span><i></i>U</span>
										</label>
									</div>
								</div>
								<div class="form-group">
									<label for="cpideliver" class="col-sm-4 control-label text-right">File Upload</label>
									<div class="col-sm-6">
										<input type="file" name="files[]" class="form-control" id="files" multiple="true">
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-4 col-sm-8">
										<button type="submit" id="saveDetail" class="btn btn-primary" disabled=""><i class="fa fa-check-circle"></i> Save</button>
										<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
									</div>
								</div>
							</fieldset>
						</form>

			</div>
		</div>
	</div>
</div>
<!-- end modal -->

			<!-- List Final Estimation -->
			<div class="col-lg-12">
				<p>
					<button type="button" id="addDetail" class="btn btn-success" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus"></i> Add Detail</button>
					<a href="#" id="nextEstimasi" class="btn btn-primary" data-svid=""><i class="fa fa-edit"></i>Revisi Estimasi</a>&nbsp;
					<button type="button" id="finalEstimasi" class="btn btn-primary"  data-toggle="modal" data-target="#finalModal"><i class="fa fa-check-circle"></i> Final Estimasi</button>&nbsp;

					<a href="<?= site_url('approval') ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
				</p>
				<div class="widget widget-table">
					<div class="widget-header">
						<h3><i class="fa fa-table"></i> List Final Estimation</h3>
					</div>
					<div class="widget-content">
						<div class="table-responsive vscroll">
						<table class="table" id="tblList_add">
							<thead>
								<tr>
									<th></th>
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
									<th>Mat.fCost</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?= $this->include('\Modules\Approval\Views\add_final_estimasi'); ?>

<?= $this->endSection(); ?>

<!-- Load JS -->
<?= $this->Section('script_js'); ?>

<?= $this->include('\Modules\Approval\Views\js'); ?>

<?= $this->endSection(); ?>