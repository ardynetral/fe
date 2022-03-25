<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2><?=$page_title;?></h2>
		<em><?=$page_subtitle;?></em>
	</div>
	<div class="main-content">
		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> <?= ((isset($act)) && ($act != "") ? $act : "") ?> <?= $page_title; ?></h3>
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
				<form id="fGateOut" class="form-horizontal" role="form">
					<?= csrf_field() ?>
					<?php $readonly = 'readonly'; ?>
					<?php $cpotgl = ($data['cpotgl'] !="")?date('d-m-Y',strtotime(@$data['cpotgl'])):"";?>
					<fieldset>
						<table class="tbl-form" width="100%">
							<!-- 9 kolom -->
							<tbody>
								<!-- baris 1  -->
								<tr>
									<td class="text-right" width="130">Container No :</td>
									<td>
										<input type="hidden" name="cpid" id="cpid" value="<?=@$data['crcpid'];?>">
										<input type="text" name="crno" class="form-control" id="crno" value="<?=$data['crno']; ?>">
										<i class="err-crno text-danger"></i>
									</td>
									<td class="text-right">Pra Out Ref :</td>
									<td><input <?php echo $readonly; ?> type="text" name="cpoorderno" id="cpoorderno" class="form-control" value="<?= $data['cpoorderno']; ?>"></td>
									<td class="text-right">EIR Out</td>
									<td colspan="3"><input <?php echo $readonly; ?> type="text" name="cpoeir" id="cpoeir" class="form-control" value="<?= $data['cpoeir']; ?>"></td>
								</tr>
								<!-- baris 2  -->
								<tr>
									<td class="text-right" width="130">Principal</td>
									<td><input <?php echo $readonly; ?> type="text" name="cpopr1" id="cpopr1" class="form-control" value="<?= $data['cpopr1']; ?>"></td>
									<td class="text-right">ID Code :</td>
									<td><input <?php echo $readonly; ?> type="text" name="cccode" class="form-control" id="cccode" value="<?= $data['cccode']; ?>"></td>
									<td class="text-right">Ref Out No # :</td>
									<td colspan="3"><input <?php echo $readonly; ?> type="text" name="cporefout" id="cporefout" class="form-control" value="<?= $data['cporefout']; ?>"></td>
								</tr>
								<!-- baris 3  -->
								<tr>
									<td class="text-right" width="130">Customer</td>
									<td><input <?php echo $readonly; ?> type="text" name="cpcust1" id="cpcust1" class="form-control" value="<?= $data['cpcust1']; ?>"></td>
									<td class="text-right">Height :</td>
									<td><input <?php echo $readonly; ?> type="text" name="ccheight" class="form-control" id="ccheight" value="<?= $data['ccheight']; ?>"></td>
									<td class="text-right"> Date Out # :</td>
									<td colspan="3">
										<input <?php echo $readonly; ?> type="text" name="cpotgl" id="cpotgl" class="form-control" value="<?= $cpotgl; ?>">
										<input <?php echo $readonly; ?> type="hidden" name="cpopratgl" id="cpopratgl" class="form-control" value="<?= ''; ?>">
									</td>
								</tr>
								<!-- baris 4  -->
								<tr>
									<td class="text-right" width="130">Type</td>
									<td><input <?php echo $readonly; ?> type="text" name="ctcode" id="ctcode" class="form-control" value="<?= $data['ctcode']; ?>"></td>
									<td class="text-right">Lenght :</td>
									<td><input <?php echo $readonly; ?> type="text" name="cclength" class="form-control" id="cclength" value="<?= $data['cclength']; ?>"></td>
									<td class="text-right"> Time # :</td>
									<td colspan="3"><input <?php echo $readonly; ?> type="text" name="cpojam" id="cpojam" class="form-control" value="<?= $data['cpojam']; ?>"></td>
								</tr>
								<!-- baris 5  -->
								<tr>
									<td class="text-right" width="130">Term</td>
									<td><input <?php echo $readonly; ?> type="text" name="cpoterm" id="cpoterm" class="form-control" value="<?= $data['cpoterm']; ?>"></td>
									<td></td>
									<td colspan="2">
										<label class="control-inline fancy-checkbox custom-color-green">
											<input type="checkbox" name="crcdp" id="crcdp" value="<?=$data['crcdp']?>" readonly <?=(isset($data['crcdp'])&&($data['crcdp']==1)?"checked":"")?>>
											<span>CDP</span>
										</label>
										<label class="control-inline fancy-checkbox custom-color-green">
											<input type="checkbox" name="cracep" id="cracep" value="<?=$data['cracep']?>" readonly <?=(isset($data['cracep'])&&($data['cracep']==1)?"checked":"")?>>
											<span>ACEP</span>
										</label>	
										<label class="control-inline fancy-checkbox custom-color-green">
											<input type="checkbox" name="crcsc" id="crcsc" value="<?=$data['crcsc']?>" readonly <?=(isset($data['crcsc'])&&($data['crcsc']==1)?"checked":"")?>>
											<span>CSC</span>
										</label>	
									</td>
									<td colspan="4">
										<label class="control-inline fancy-checkbox custom-color-green">
											<input type="checkbox" name="cpochrgbm" id="cpochrgbm" value="<?=$data['cpochrgbm']?>" <?=(isset($data['cpochrgbm'])&&($data['cpochrgbm']==1)?"checked":"")?>>
											<span>Lift On Charged</span>
										</label>
										<label class="control-inline fancy-checkbox custom-color-green">
											<input type="checkbox" name="cpopaidbm" id="cpopaidbm" value="<?=$data['cpopaidbm']?>" <?=(isset($data['cpopaidbm'])&&($data['cpopaidbm']==1)?"checked":"")?>>
											<span>Paid</span>
										</label>
									</td>
								</tr>
								<!-- baris 6  -->
								<tr>
									<td class="text-right" width="130">Weight (Kgs) :</td>
									<td><input <?php echo $readonly; ?> type="text" name="crweightk" id="crweightk" class="form-control" value="<?= $data['crweightk']; ?>"></td>
									<td class="text-right">Weight (Lbs) :</td>
									<td><input <?php echo $readonly; ?> type="text" name="crweightl" class="form-control" id="crweightl" value="<?= $data['crweightl']; ?>"></td>
									<td class="text-right">Receipt No :</td>
									<td colspan="3"><input <?php echo $readonly; ?> type="text" name="cporeceptno" id="cporeceptno" class="form-control" value="<?= $data['cporeceptno']; ?>"></td>
								</tr>
								<!-- baris 7  -->
								<tr>
									<td class="text-right" width="130">Tare (Kgs) :</td>
									<td><input <?php echo $readonly; ?> type="text" name="crtarak" id="crtarak" class="form-control" value="<?= $data['crtarak']; ?>"></td>
									<td class="text-right">Tare (Lbs) :</td>
									<td><input <?php echo $readonly; ?> type="text" name="crtaral" class="form-control" id="crtaral" value="<?= $data['crtaral']; ?>"></td>
									<td class="text-right">F/E :</td>
									<td colspan="3">
										<label class="control-inline fancy-radio custom-bgcolor-green">
											<input type="radio" name="cpofe" id="" value="1" readonly <?=(isset($data['cpofe']) &&($data['cpofe']=="full")?"checked":"")?>>
											<span><i></i>Full</span>
										</label>
										<label class="control-inline fancy-radio custom-bgcolor-green">
										<input type="radio" name="cpofe" id="" value="0" readonly <?=(isset($data['cpofe']) &&($data['cpofe']=="empty")?"checked":"")?>>
											<span><i></i>Empty</span>
										</label>
									</td>
								</tr>
								<!-- baris 8  -->
								<tr>
									<td class="text-right" width="130">Netto (Kgs) :</td>
									<td><input <?php echo $readonly; ?> type="text" name="crnetk" id="crnetk" class="form-control" value="<?= $data['crnetk']; ?>"></td>
									<td class="text-right">Netto (Lbs) :</td>
									<td><input <?php echo $readonly; ?> type="text" name="crnetl" class="form-control" id="crnetl" value="<?= $data['crnetl']; ?>"></td>
									<td class="text-right">Destination Port :</td>
									<td colspan="3"><input <?php echo $readonly; ?> type="text" name="cpoload" id="cpoload" class="form-control" value="<?= $data['cpoload']; ?>"></td>
								</tr>
								<!-- baris 9  -->
								<tr>
									<td class="text-right" width="130">Volume (CBM) :</td>
									<td><input <?php echo $readonly; ?> type="text" name="crvol" id="crvol" class="form-control" value="<?= $data['crvol']; ?>"></td>
									<td class="text-right">Material :</td>
									<td><input <?php echo $readonly; ?> type="text" name="mtdesc" class="form-control" id="mtdesc" value="<?= $data['mtdesc']; ?>"></td>
									<td class="text-right">Cargo :</td>
									<td colspan="3"><input <?php echo $readonly; ?> type="text" name="cpocargo" id="cpocargo" class="form-control" value="<?= $data['cpocargo']; ?>"></td>
								</tr>
								<!-- baris 10 -->
								<tr>
									<td class="text-right" width="130">Manfacture :</td>
									<td><input <?php echo $readonly; ?> type="text" name="crmanuf" id="crmanuf" class="form-control" value="<?= $data['crmanuf']; ?>"></td>
									<td class="text-right">Manfacture Date :</td>
									<td><input <?php echo $readonly; ?> type="text" name="manufdate" id="manufdate" class="form-control" value="<?= @$data['crmandat']; ?>"></td>
									<td class="text-right">Vessel :</td>
									<td colspan="3"><input <?php echo $readonly; ?> type="text" name="vesid" id="vesid" class="form-control" value="<?= $data['cpoves']; ?>">
										<input type="hidden" name="cpovoyid" id="cpovoyid" class="form-control" value="<?= @$data['cpovoyid']; ?>"></td>
								</tr>
								<!-- baris 11 -->
								<tr>
									<td class="text-right" width="130">Condition :</td>
									<td><input <?php echo $readonly; ?> type="text" name="crlastcond" id="crlastcond" class="form-control" value="<?= $data['crlastcond']; ?>"></td>
									<td class="text-right">Activity Status :</td>
									<td><input <?php echo $readonly; ?> type="text" name="crlastact" id="crlastact" class="form-control" value="<?= $data['crlastact']; ?>"></td>
									<td class="text-right">Receiver :</td>
									<td colspan="3"><input <?php echo $readonly; ?> type="text" name="cporeceiv" id="cporeceiv" class="form-control" value="<?= $data['cporeceiv']; ?>"></td>
								</tr>

								<tr>

									<td class="text-right" width="130">Vehicle ID :</td>
									<td><input type="text" name="cponopol" class="form-control" id="cponopol" value="<?= $data['cponopol']; ?>"></td>
									<td class="text-right"><b>Survey Out</b>:</td>
									<td colspan="6"></td>
								</tr>
								<tr>
									<td class="text-right" width="130">Driver :</td>
									<td><input type="text" name="cpodriver" class="form-control" id="cpodriver" value="<?= $data['cpodriver']; ?>"></td>
									<td class="text-right">Inspector Date :</td>
									<td><input type="text" name="svsurdat" class="form-control tanggal" id="svsurdat" value="<?= date('d-m-Y',strtotime($data['svsurdat'])); ?>"></td>
									<td colspan="5"></td>
								</tr>
								<tr>
									<td class="text-right" width="130">Seal No :</td>
									<td><input type="text" name="cposeal" class="form-control" id="cposeal" value="<?= @$data['cposeal']; ?>"></td>
									<td class="text-right">Inspector:</td>
									<td><?=$surveyor;?></td>
									<td colspan="5"></td>
								</tr>



							</tbody>
						</table>
					</fieldset>
					<div class="row">
						<div class="col-sm-12 text-center">
							<button type="submit" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
							<a href="<?= site_url('gateout') ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
						</div>
					</div>
				</form>
			</div>
		</div>				
	</div>
</div>

<?= $this->endSection();?>

<!-- Load JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\GateOut\Views\js'); ?>

<?= $this->endSection();?>