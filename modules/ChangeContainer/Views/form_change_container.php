<div class="modal fade in" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Change Container<span></span></h4>
			</div>
			<div class="modal-body">

<form id="formChangeContainer" class="form-horizontal" role="form">
	<?= csrf_field() ?>
	<input type="hidden" name="pracrnoid" id="pracrnoid">
	<fieldset>
		<div class="form-group">
			<label class="col-sm-4 control-label text-right">From Container</label>
			<div class="col-sm-8">
				<input type="text" name="crno1" class="form-control" id="crno1" readonly>
			</div>
		</div>		
		<div class="form-group">
			<label class="col-sm-4 control-label text-right">To Container </label>
			<div class="col-sm-8">
				<input type="hidden" name="pra_id" class="form-control" id="pra_id" value="<?=@$data['praid']?>">
				<input type="hidden" name="order_no" class="form-control" id="order_no" value="<?=$data['cpiorderno'];?>">
				<input type="text" name="crno2" class="form-control" id="crno2">
				<i class="err-crno"></i>
			</div>
		</div>	

		<div class="form-group">
			<div class="col-sm-offset-4 col-sm-8">
				<button type="button" id="updateDetail" class="btn btn-primary"><i class="fa fa-pencil"></i> Update Container</button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times-circle"></i> Close</button>
			</div>
		</div>						
	</fieldset>
</form>	

			</div>
		</div>
	</div>
</div>