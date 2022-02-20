<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Final Estimasi</h4>
			</div>
			<form id="formFinalEstimasi" class="form-horizontal" role="form"  enctype="multipart/form-data">
			<div class="modal-body">

				<?= csrf_field() ?>
				<fieldset>
					<input type="hidden" name="final_crno" id="final_crno" value="<?=@$header['crno']?>">
					<input type="hidden" name="final_svid" id="final_svid" value="<?=@$header['svid']?>">
					<input type="hidden" name="final_totalrmhr" id="final_totalrmhr" value="0">
					<input type="hidden" name="final_totallab" id="final_totallab" value="0">
					<input type="hidden" name="final_totalcost" id="final_totalcost" value="0">
					<input type="hidden" name="final_total" id="final_total" value="0">
					<table class="tbl-form">
						<tbody>
<!-- 						<tr>
							<td class="text-right" width="130">Version :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
						</tr> -->
						<tr>
							<td class="text-right" width="130">Aut No :</td>
							<td><input type="text" name="autno" class="form-control" id="autno" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Notes Approval :</td>
							<td ><input type="text" name="rpnotesa" id="rpnotesa" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Bill On :</td>
							<td><?=$emkl_dropdown;?></td>
						</tr>
<!-- 						<tr> crno, svid, totalrmhr, totallab, totalcost, total, autno,
							<td class="text-right" width="130">Approval Confirm :</td>
							<td ><input type="file" name="files[]" id="files" class="form-control" multiple="true"></td>
						</tr>	 -->						
						</tbody>
					</table>
				</fieldset>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
				<button type="submit" id="saveDetail" class="btn btn-custom-primary"><i class="fa fa-check-circle"></i> Save</button>
			</div>
			</form>			
		</div>
	</div>
</div>