<div class="modal fade in" id="editDetailModal" tabindex="-1" role="dialog" aria-labelledby="editDetailModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="editDetailModalLabel">Edit Repo Tariff Detail</h4>
			</div>
			<form id="editTariffDetail" class="form-horizontal" role="form" method="post">
			<div class="modal-body">
				<?= csrf_field() ?>
				<fieldset>
					<input type="hidden" name="prcode" id="prcode">
					<input type="hidden" name="rtno" id="rtno">
					<div class="form-group">
						<label for="code" class="col-sm-3 control-label text-right">Repo IN/OUT</label>
						<div class="col-sm-9">
							<select name="rtid" id="rtid" class="form-control">
								<option value="">-select-</option>
								<option value="2">In</option>
								<option value="1">Out</option>
							</select>
						</div>					
					</div>	
					<div class="form-group">
						<label for="code" class="col-sm-3 control-label text-right">Repo Type</label>
						<div class="col-sm-9">
							<select name="retype" id="retype" class="form-control">
								<option value="">- select -</option>
							</select>
						</div>							
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label text-right">F/E</label>
						<div class="col-sm-9">
							<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="rtef" id="rtef" value="1" readonly>
								<span><i></i>Full</span>
							</label>
							<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="rtef" id="rtef" value="0" readonly>
								<span><i></i>Empty</span>
							</label>								
						</div>
					</div>					
					<hr>
					<label class="col-sm-offset-3"><h4>PACKAGE RATE</h4></label>
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">Currency</label>
						<div class="col-sm-9">
							<input type="text" name="rtpackcurr" class="form-control" id="rtpackcurr" value="IDR" readonly="">
						</div>
					</div>
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">20"</label>
						<div class="col-sm-9">
							<input type="text" name="rtpackv20" class="form-control" id="rtpackv20">
						</div>
					</div>
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">40"</label>
						<div class="col-sm-9">
							<input type="text" name="rtpackv40" class="form-control" id="rtpackv40">
						</div>
					</div>	
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">45"</label>
						<div class="col-sm-9">
							<input type="text" name="rtpackv45" class="form-control" id="rtpackv45">
						</div>
					</div>
					<hr>
					<label class="col-sm-offset-3"><h4>BREAKDOWN RATE</h4></label><br>
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">LOLO Currency</label>
						<div class="col-sm-9">
							<input type="text" name="rtlocurr" class="form-control" id="rtlocurr" value="IDR" readonly="">
						</div>
					</div>
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">Lift On 20"</label>
						<div class="col-sm-9">
							<input type="text" name="rtlon20" class="form-control" id="rtlon20">
						</div>
					</div>
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">Lift On 40"</label>
						<div class="col-sm-9">
							<input type="text" name="rtlon40" class="form-control" id="rtlon40">
						</div>
					</div>	
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">Lift On 45"</label>
						<div class="col-sm-9">
							<input type="text" name="rtlon45" class="form-control" id="rtlon45">
						</div>
					</div>
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">Lift Off 20"</label>
						<div class="col-sm-9">
							<input type="text" name="rtlof20" class="form-control" id="rtlof20">
						</div>
					</div>
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">Lift Off 40"</label>
						<div class="col-sm-9">
							<input type="text" name="rtlof40" class="form-control" id="rtlof40">
						</div>
					</div>	
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">Lift Off 45"</label>
						<div class="col-sm-9">
							<input type="text" name="rtlof45" class="form-control" id="rtlof45">
						</div>
					</div>
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">Doc Currency</label>
						<div class="col-sm-9">
							<input type="text" name="rtdoccurr" class="form-control" id="rtdoccurr" value="IDR" readonly="">
						</div>
					</div>
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">Doc Method</label>
						<div class="col-sm-9">
							<input type="text" name="rtdocm" class="form-control" id="rtdocm">
						</div>
					</div>
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">Doc Free"</label>
						<div class="col-sm-9">
							<input type="text" name="rtdocv" class="form-control" id="rtdocv">
						</div>
					</div>
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">Port Charge 20"</label>
						<div class="col-sm-9">
							<input type="text" name="rtportcharger20" class="form-control" id="rtportcharger20">
						</div>
					</div>
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">Port Charge 40"</label>
						<div class="col-sm-9">
							<input type="text" name="rtportcharger40" class="form-control" id="rtportcharger40">
						</div>
					</div>	
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">Trucking 20"</label>
						<div class="col-sm-9">
							<input type="text" name="rttruck20" class="form-control" id="rttruck20">
						</div>
					</div>	
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">Trucking 40"</label>
						<div class="col-sm-9">
							<input type="text" name="rttruck40" class="form-control" id="rttruck40">
						</div>
					</div>	
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">Haulage Currency</label>
						<div class="col-sm-9">
							<input type="text" name="rthaulcurr" class="form-control" id="rthaulcurr" value="IDR" readonly="">
						</div>
					</div>
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">Haulage 20"</label>
						<div class="col-sm-9">
							<input type="text" name="rthaulv20" class="form-control" id="rthaulv20">
						</div>
					</div>
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">Haulage 40"</label>
						<div class="col-sm-9">
							<input type="text" name="rthaulv40" class="form-control" id="rthaulv40">
						</div>
					</div>	
					<div class="form-group">
						<label for="prcode" class="col-sm-3 control-label text-right">Haulage 45"</label>
						<div class="col-sm-9">
							<input type="text" name="rthaulv45" class="form-control" id="rthaulv45">
						</div>
					</div>

					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-9">
							<button type="submit" id="updateDetail" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
							<button type="button" id="cancel" class="btn btn-default" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times-circle"></i> Close</button>
						</div>
					</div>						
				</fieldset>
			</div>
			</form>
		</div>
	</div>
</div>