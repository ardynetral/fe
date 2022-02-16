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
<!-- <?php print_r(@$details['datas']) ?> -->
<div class="widget">
	<div class="widget-header">
		<h3><i class="fa fa-edit"></i> <?= ((isset($act)) && ($act != "") ? $act : "") ?> Survey </h3>
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
		<form id="form_input" class="form-horizontal" role="form">
			<?= csrf_field() ?>
			<?php $readonly = 'readonly'; ?>
			<fieldset>
				<table class="tbl-form" width="100%">
					<!-- 9 kolom -->
					<input type="hidden" name="UPDATE_ID" value="<?= @$crno; ?>">
					<input type="hidden" name="SVID" class="form-control" id="SVID" value="<?= @$svid; ?>">
					<input type="hidden" name="SYID" class="form-control" id="SYID" value="<?= $uname; ?>">
					<tbody>
						<tr>
							<td class="text-right" width="130">Container No :</td>
							<td><input type="text" name="CRNO" class="form-control" id="CRNO" value="<?= @$crno; ?>"></td>
							<td class="text-right">PraIn No :</td>
							<td><input <?php echo $readonly; ?> type="text" name="CPIPRANO" id="CPIPRANO" class="form-control" value="<?= @$details['datas']['cpiorderno']; ?>"></td>
							<td class="text-right">Origin Port :</td>
							<td><input <?php echo $readonly; ?> type="text" name="CPIDISH" id="CPIDISH" class="form-control" value="<?= @$details['datas']['cpidish']; ?>"></td>

						</tr>
						<tr>
							<td class="text-right" width="130">Principal :</td>
							<td><input <?php echo $readonly; ?> type="text" name="PRCODE" class="form-control" id="PRCODE" value="<?= @$details['datas']['prcode']; ?>"></td>
							<td class="text-right">EIR In</td>
							<td><input <?php echo $readonly; ?> type="text" name="CPIEIR" id="CPIEIR" class="form-control" value="<?= @$details['datas']['cpieir']; ?>"></td>
							<td class="text-right">Discharge Date :</td>
							<td><input <?php echo $readonly; ?> type="text" name="CPIDISDAT" id="CPIDISDAT" class="form-control" value="<?= @$details['datas']['cpidisdat']; ?>">
							</td>

						</tr>
						<tr>
							<td class="text-right" width="130">Customer :</td>
							<td><input <?php echo $readonly; ?> type="text" name="CUCODE" class="form-control" id="CUCODE" value="<?= @$details['datas']['cucode']; ?>"></td>
							<td class="text-right">DO No # :</td>
							<td><input <?php echo $readonly; ?> type="text" name="CPIREFIN" id="CPIREFIN" class="form-control" value="<?= @$details['datas']['cpirefin']; ?>"></td>
							<td class="text-right">Ex Cargo :</td>
							<td><input <?php echo $readonly; ?> type="text" name="CPICARGO" id="CPICARGO" class="form-control" value="<?= @$details['datas']['cpicargo']; ?>"></td>

						</tr>
						<tr>
							<td class="text-right">ID Code :</td>
							<td><input <?php echo $readonly; ?> type="text" name="CCCODE" class="form-control" id="CCCODE" value="<?= @$details['datas']['cccode']; ?>"></td>
							<td class="text-right">Date In :</td>
							<td><input <?php echo $readonly; ?> type="text" name="CPITGL" id="CPITGL" class="form-control" value="<?= @$details['datas']['cpitgl']; ?>"></td>
							<td class="text-right">Ex Vessel-Voyage :</td>
							<td><input <?php echo $readonly; ?> type="text" name="CPIVOYID" id="CPIVOYID" class="form-control" value="<?= @$details['datas']['cpivoyid']; ?>"></td>

						</tr>
						<tr>
							<td class="text-right" width="130">Type :</td>
							<td><input <?php echo $readonly; ?> type="text" name="CTCODE" class="form-control" id="CTCODE" value="<?= @$details['datas']['ctcode']; ?>"></td>
							<td class="text-right">Time In</td>
							<td><input <?php echo $readonly; ?> type="text" name="CPIJAM" id="CPIJAM" class="form-control" value="<?= @$details['datas']['cpijam']; ?>"></td>
							<td class="text-right">Vessel Operator :</td>
							<td><input <?php echo $readonly; ?> type="text" name="CPIVES" id="CPIVES" class="form-control" value="<?= @$details['datas']['cpives']; ?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Length :</td>
							<td><input <?php echo $readonly; ?> type="text" name="CCLENGTH" class="form-control" id="CCLENGTH" value="<?= @$details['datas']['cclength']; ?>"></td>
							<td class="text-right">Lift Off Charge :</td>
							<td>
								<label class="control-inline fancy-checkbox custom-color-green">
									<input <?php echo $readonly; ?> type="checkbox" name="CPICHRGBB" id="CPICHRGBB" value="<?= @$details['datas']['cpichrgbb']; ?>" <?php echo $checked = (@$details['datas']['cpichrgbb'] == 1) ? 'checked="checked"' : '' ?>>
									<span></span></label>
								Paid&nbsp;:&nbsp;
								<label class="control-inline fancy-checkbox custom-color-green">
									<input <?php echo $readonly; ?> type="checkbox" name="CPIPAIDBB" id="CPIPAIDBB" value="<?= @$details['datas']['cpipaidbb']; ?>" <?php echo $checked = (@$details['datas']['cpipaidbb'] == 1) ? 'checked="checked"' : '' ?>>
									<span></span></label>
							</td>
							<td class="text-right">F/E :</td>
							<td>
								<label class="control-inline fancy-radio custom-bgcolor-green">
									<input <?php echo $readonly; ?> type="radio" name="CPIFE" id="CPIFE" value="1" <?php echo $checked = (@$details['datas']['cpife'] == 1) ? 'checked="checked"' : '' ?>>
									<span><i></i>Full</span>
								</label>
								<label class="control-inline fancy-radio custom-bgcolor-green">
									<input <?php echo $readonly; ?> type="radio" name="CPIFE" id="CPIFE" value="0" <?php echo $checked = (@$details['datas']['cpife'] != null && @$details['datas']['cpife'] == 0) ? 'checked="checked"' : '' ?>>
									<span><i></i>Empty</span>
								</label>
							</td>
						</tr>
						<tr>
							<td class="text-right">Height :</td>
							<td><input <?php echo $readonly; ?> type="text" name="CCHEIGHT" class="form-control" id="CCHEIGHT" value="<?= @$details['datas']['ccheight']; ?>"></td>
							<td class="text-right" width="">CDP :</td>
							<td colspan="">
								<label class="control-inline fancy-checkbox custom-color-green">
									<input <?php echo $readonly; ?> type="checkbox" name="CRCDP" id="CRCDP" value="<?= @$details['datas']['crcdp']; ?>" <?php echo $checked = (@$details['datas']['crcdp'] == 1) ? 'checked="checked"' : '' ?>>
									<span></span></label>
								ACEP :
								<label class="control-inline fancy-checkbox custom-color-green">
									<input <?php echo $readonly; ?> type="checkbox" name="CRACEP" id="CRACEP" value="<?= @$details['datas']['cracep']; ?>" <?php echo $checked = (@$details['datas']['crcdp'] == 1) ? 'checked="checked"' : '' ?>>
									<span></span></label>
								CSC :
								<label class="control-inline fancy-checkbox custom-color-green">
									<input <?php echo $readonly; ?> type="checkbox" name="CRCSC" id="CRCSC" value="<?= @$details['datas']['crcsc']; ?>" <?php echo $checked = (@$details['datas']['crcdp'] == 1) ? 'checked="checked"' : '' ?>>
									<span></span></label>
							</td>
							<td class="text-right">Term :</td>
							<td>
								<label class="control-inline fancy-radio custom-bgcolor-green">
									<input <?php echo $readonly; ?> type="radio" name="CPITERM" id="CY" value="CY" <?php echo $checked = (@$details['datas']['cpiterm'] == 'CY') ? 'checked="checked"' : ''; ?>>
									<span><i></i>CY</span>
								</label>
								<label class="control-inline fancy-radio custom-bgcolor-green">
									<input <?php echo $readonly; ?> type="radio" name="CPITERM" id="MTY" value="MTY" <?php echo $checked = (@$details['datas']['cpiterm'] == 'MTY') ? 'checked="checked"' : ''; ?>>
									<span><i></i>MTY</span>
								</label>
								<label class="control-inline fancy-radio custom-bgcolor-green">
									<input <?php echo $readonly; ?> type="radio" name="CPITERM" id="CFS" value="CFS" <?php echo $checked = (@$details['datas']['cpiterm'] == 'CFS') ? 'checked="checked"' : ''; ?>>
									<span><i></i>CFS</span>
								</label>
							</td>
						</tr>

						<tr>
							<td class="text-right" width="130">Weight (Kgs) :</td>
							<td><input type="text" name="CRWEIGHTK" id="CRWEIGHTK" class="form-control" value="<?= @$details['datas']['crweightk']; ?>"></td>
							<td class="text-right">Weight (Lbs) :</td>
							<td><input type="text" name="CRWEIGHTL" id="CRWEIGHTL" class="form-control" value="<?= @$details['datas']['crweightl']; ?>"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Tare (Kgs) :</td>
							<td><input type="text" name="CRTARAK" id="CRTARAK" class="form-control" value="<?= @$details['datas']['crtarak']; ?>"></td>
							<td class="text-right">Tare (Lbs) :</td>
							<td><input type="text" name="CRTARAL" id="CRTARAL" class="form-control" value="<?= @$details['datas']['crtaral']; ?>"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Netto (Kgs) :</td>
							<td><input type="text" name="CRNETK" id="CRNETK" class="form-control" value="<?= @$details['datas']['crnetk']; ?>"></td>
							<td class="text-right">Netto (Lbs) :</td>
							<td><input type="text" name="CRNETL" id="CRNETL" class="form-control" value="<?= @$details['datas']['crnetl']; ?>"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Volume (Cbm) :</td>
							<td><input type="text" name="CRVOL" id="CRVOL" class="form-control" value="<?= @$details['datas']['crvol']; ?>"></td>
							<td class="text-right">Material :</td>
							<td><input type="text" name="MTCODE1" id="MTCODE1" class="form-control" value="<?= @$details['datas']['mtcode1']; ?>"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Manufacture :</td>
							<td><input type="text" name="CRMANUF" id="CRMANUF" class="form-control" value="<?= @$details['datas']['crmanuf']; ?>"></td>
							<td class="text-right">Manufacture Date :</td>
							<td><input type="text" name="CRCMANDAT" id="CRCMANDAT" class="form-control" value="<?= @$details['datas']['CRCMANDAT']; ?>"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Condition Box :</td>
							<td>
								<select name="CRLASTCOND" id="CRLASTCOND" class="input">
									<option value="">Select Value</option>
									<option value='AX' <?php echo $select = (@$details['datas']['crlastcond'] == 'AX') ? 'selected="selected"' : ''; ?>>AX</option>
									<option value='AC' <?php echo $select = (@$details['datas']['crlastcond'] == 'AC') ? 'selected="selected"' : ''; ?>>AC</option>
									<option value='AU' <?php echo $select = (@$details['datas']['crlastcond'] == 'AU') ? 'selected="selected"' : ''; ?>>AU</option>
									<option value='DN' <?php echo $select = (@$details['datas']['crlastcond'] == 'DN') ? 'selected="selected"' : ''; ?>>DN</option>
									<option value='DJ' <?php echo $select = (@$details['datas']['crlastcond'] == 'DJ') ? 'selected="selected"' : ''; ?>>DJ</option>
								</select>
							</td>
							<td class="text-right">Cleaning :</td>
							<td><?=cleaning_method("RMCODE","WW")?></td>
							<td></td>
							<td></td>

						</tr>
						<tr>
							<td></td>
							<td colspan="5">
								<?php if (isset($act) && ($act == 'view')) : ?>
									<!-- <button type="button" id="updateData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>&nbsp; -->
									<a href="<?= site_url('survey') ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
								<?php else : ?>
									<button type="button" id="saveData" class="btn btn-primary" disabled="disabled"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
									<a id="cancel" href="<?= site_url('survey') ?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
								<?php endif; ?>
							</td>
						</tr>
					</tbody>
				</table>
			</fieldset>
		</form>
	</div>
</div>

<?= $this->endSection(); ?>

<!-- Load JS -->
<?= $this->Section('script_js'); ?>

<?= $this->include('\Modules\Survey\Views\js'); ?>

<?= $this->endSection(); ?>