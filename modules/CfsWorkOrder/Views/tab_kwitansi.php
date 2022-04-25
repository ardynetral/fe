<div class="row">
	<div class="col-sm-12">
		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> WO Receipt</h3>
			</div>
			<div class="widget-content">
				<form id="fReceipt" class="form-horizontal" role="form">
					<?= csrf_field() ?>
					<input type="hidden" name="woreceptid" id="woreceptid">
					<input type="hidden" name="woreceptdate" id="woreceptdate">					
					<fieldset>
					<table class="tbl-form" width="100%">
					<tbody>
						<tr>
							<td class="text-right">Desc Biaya1</td>
							<td><input type="text" name="wodescbiaya1" id="wodescbiaya1" class="form-control"></td>
							<td class="text-right">Biaya1</td>
							<td><input type="text" name="wobiaya1" id="wobiaya1" class="form-control"></td>
						</tr>
						<tr>
							<td class="text-right">Desc Biaya2</td>
							<td><input type="text" name="wodescbiaya2" id="wodescbiaya2" class="form-control"></td>
							<td class="text-right">Biaya2</td>
							<td><input type="text" name="wobiaya2" id="wobiaya2" class="form-control"></td>
						</tr>
						<tr>
							<td class="text-right">Desc Biaya3</td>
							<td><input type="text" name="wodescbiaya3" id="wodescbiaya3" class="form-control"></td>
							<td class="text-right">Biaya3</td>
							<td><input type="text" name="wobiaya3" id="wobiaya3" class="form-control"></td>
						</tr>
						<tr>
							<td class="text-right">Desc Biaya4</td>
							<td><input type="text" name="wodescbiaya4" id="wodescbiaya4" class="form-control"></td>
							<td class="text-right">Biaya4</td>
							<td><input type="text" name="wobiaya4" id="wobiaya4" class="form-control"></td>
						</tr>
						<tr>
							<td class="text-right">Desc Biaya5</td>
							<td><input type="text" name="wodescbiaya5" id="wodescbiaya5" class="form-control"></td>
							<td class="text-right">Biaya5</td>
							<td><input type="text" name="wobiaya5" id="wobiaya5" class="form-control"></td>
						</tr>
						<tr>
							<td class="text-right">Desc Biaya6</td>
							<td><input type="text" name="wodescbiaya6" id="wodescbiaya6" class="form-control"></td>
							<td class="text-right">Biaya6</td>
							<td><input type="text" name="wobiaya6" id="wobiaya6" class="form-control"></td>
						</tr>
						<tr>
							<td class="text-right">Desc Biaya7</td>
							<td><input type="text" name="wodescbiaya7" id="wodescbiaya7" class="form-control"></td>
							<td class="text-right">Biaya7</td>
							<td><input type="text" name="wobiaya7" id="wobiaya7" class="form-control"></td>
						</tr>
						<tr><td colspan="4"><hr></td></tr>
						<tr>
							<td class="text-right">PPH</td>
							<td><input type="text" name="wototal_pajak" id="wototal_pajak" class="form-control"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right">ADMINISTRATION</td>
							<td><input type="text" name="wobiaya_adm" id="wobiaya_adm" class="form-control"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right">MATERAI</td>
							<td><input type="text" name="womaterai" id="womaterai" class="form-control"></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td class="text-right">BIAYA LAIN</td>
							<td><input type="text" name="wototbiaya_lain" id="wototbiaya_lain" class="form-control"></td>
							<td></td>
							<td></td>
						</tr>			
						<tr>
							<td class="text-right">PPH23</td>
							<td><input type="text" name="wototpph23" id="wototpph23" class="form-control"></td>
							<td></td>
							<td></td>
						</tr>			
						<tr>
							<th class="text-right">TOTAL TAGIHAN</th>
							<td><input type="text" name="wototal_tagihan" id="wototal_tagihan" class="form-control" readonly></td>
							<td></td>
							<td></td>
						</tr>	
						<tr>
							<td></td>
							<td colspan="3">
								<button type="submit" id="saveDataReceipt" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save Receipt</button>&nbsp;
								<a href="<?=site_url('cfswo')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
							</td>
						</tr>							
					</tbody>
					</table>					
				</form>
			</div>
		</div>
	</div>
</div>