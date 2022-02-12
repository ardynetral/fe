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
					<table class="tbl-form">
						<tbody>
						<tr>
							<td class="text-right" width="130">Version :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Aut No :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Notes Approval :</td>
							<td ><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Bill On :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Approval Confirm :</td>
							<td ><input type="file" name="files[]" id="files" class="form-control" multiple="true"></td>
						</tr>							
						</tbody>
					</table>
				</fieldset>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
				<button type="button" id="saveDetail" class="btn btn-custom-primary"><i class="fa fa-check-circle"></i> Save</button>
				<button type="button" id="updateDetail" class="btn btn-custom-primary" style="display:none;"><i class="fa fa-check-circle"></i> Update Container</button>
			</div>
			</form>			
		</div>
	</div>
</div>