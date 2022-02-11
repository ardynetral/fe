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
		<form id="fEstimasi" class="form-horizontal" role="form" method="POST">
			<?= csrf_field() ?>
			<?php $readonly = 'readonly'; ?>
			<input type="hidden" name="svid" id="svid" value="<?=$header['svid']?>">
<!-- 			<input type="hidden" name="syid" id="syid"> -->
			<input type="hidden" name="rpcrton" id="rpcrton" value="<?=$header['svcrton']?>">
			<input type="hidden" name="rpcrtby" id="rpcrtby" value="<?=$header['svcrtby']?>">
			<fieldset>
				<table class="tbl-form" width="100%">
					<!-- 9 kolom -->
					<tbody>
						<tr>
							<td class="text-right" width="130">Container No :</td>
							<td><input type="text" name="rpcrno" class="form-control" id="rpcrno" value="<?= $header['crno']; ?>">
								<i class="err-crno text-danger"></i></td>
							<td class="text-right">EOR No :</td>
							<td colspan="3"><input <?php echo $readonly; ?> type="text" name="rpnoest" id="rpnoest" class="form-control" value="<?= $header['rpnoest']; ?>"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Date :</td>
							<td><input type="text" name="rptglest" class="form-control" id="rptglest" value="<?= date('d-m-Y',strtotime($header['rptglest'])); ?>"></td>
							<td class="text-right">Time :</td>
							<td colspan="3"><input type="text" name="rpjamest" id="rpjamest" class="form-control" value="<?= '' ?>"></td>
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
							<td><input <?php echo $readonly; ?> type="text" name="cono" class="form-control" id="cono" value="<?= $header['cono']; ?>"></td>
							<td class="text-right">Expired :</td>
							<td colspan="3"><input <?php echo $readonly; ?> type="text" name="coexpdate" id="coexpdate" class="form-control" value="<?= $header['coexpdate']; ?>"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Surveyor :</td>
							<td><input <?php echo $readonly; ?> type="text" name="syid" class="form-control" id="syid" value="<?= $header['syid']; ?>"></td>
							<td class="text-right">Survey Date :</td>
							<td colspan="3"><input <?php echo $readonly; ?> type="text" name="svsurdat" id="svsurdat" class="form-control" value="<?= $header['svsurdat']; ?>"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Est Version :</td>
							<td><input <?php echo $readonly; ?> type="text" name="rpver" class="form-control" id="rpver" value="<?= $header['rpver']; ?>"></td>
							<td class="text-right">Survey Condition :</td>
							<td colspan="3"><input <?php echo $readonly; ?> type="text" name="svcond" id="svcond" class="form-control" value="<?= $header['svcond']; ?>"></td>
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
									<button type="submit" id="updateData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>&nbsp;

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
						<form method="post" id="formUpdateDetail" class="form-horizontal" role="form"  enctype="multipart/form-data">
							<fieldset>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Location</label>
									<div class="col-sm-8">
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
									<label class="col-sm-4 control-label">Files</label>
									<div class="col-sm-6" id="fileList"></div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-4 col-sm-8">
										<button type="submit" id="updateDetail" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>
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
						<div class="table-responsive">
						<table class="table" id="tblList_edit">
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
									<th></th>
								</tr>
							</thead>
							<tbody>						
							<?php
							if($detail=="") {
								$dt_detail="";
							} else {
								$no=1;
								foreach($detail as $row) {
									echo '<tr>';
									echo '<td class="no">'.$no.'</td>';
									echo '<td class="lccode" style="display:none">'.$row['lccode'].'</td>';
									echo '<td class="cmcode">'.$row['cmcode'].'</td>';
									echo '<td class="dycode">'.$row['dycode'].'</td>';
									echo '<td class="rmcode">'.$row['rmcode'].'</td>';
									echo '<td class="rdcalmtd">'.$row['rdcalmtd'].'</td>';
									echo '<td class="rdmhr"></td>';
									echo '<td class="rdsize">'.$row['rdsize'].'</td>';
									echo '<td class="muname">'.$row['muname'].'</td>';
									echo '<td class="rdqty">'.$row['rdqty'].'</td>';
									echo '<td class="rdmhr">'.$row['rdmhr'].'</td>';
									echo '<td class="curr_symbol">'.$row['curr_symbol'].'</td>';
									echo '<td class="rddesc">'.$row['rddesc'].'</td>';
									echo '<td class="rdlab">'.number_format($row['rdlab'],2).'<span style="display:none;">'.$row['rdlab'].'</span></td>';
									echo '<td class="rdmat">'.number_format($row['rdmat'],2).'<span style="display:none;">'.$row['rdmat'].'</span></td>';
									echo '<td>
											<a href="#" class="btn btn-primary btn-xs edit">edit</a>
											<a href="#" class="btn btn-danger btn-xs delete" data-svid="'.$row['svid'].'" data-rpid="'.$row['rpid'].'" data-rdno="'.$row['rdno'].'">delete</a></td>';
									echo '</tr>';
									$no++;
								}
							}?>
							</tbody>
						</table>
						</div>
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