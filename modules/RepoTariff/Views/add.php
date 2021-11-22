<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>Repo Tariff</h2>
		<em>Add Repo Tariff</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> Add Repo Tariff</h3>
			</div>
			<div class="widget-content">


				<?php if(isset($validasi)):?>
					<div class="alert alert-danger">
						<?=$validasi; ?>
					</div>
				<?php endif; ?>

				<?php if(isset($message)): ?>
					<p class="alert alert-danger">
						<?php echo $message;?>
					</p>
				<?php endif;?>
				<div id="alert">
					
				</div>

				<form id="#formCType" class="form-horizontal" role="form">
					<?= csrf_field() ?>
					<fieldset>
						<div class="form-group">
							<label for="code" class="col-sm-2 control-label text-right">Tariff No</label>
							<div class="col-sm-3">
								<input type="text" name="no" class="form-control" id="no">
							</div>
						</div>	
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">Principal</label>
							<div class="col-sm-3">
								<?=principal_dropdown();?>
							</div>
						</div>	
						<div class="form-group">
							<label for="code" class="col-sm-2 control-label text-right">Begin Date</label>
							<div class="col-sm-3">
								<div class="input-group">
								<input type="text" name="bdate" id="bdate" class="form-control tanggal" required="">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>	
							</div>
						</div>	
						<div class="form-group">
							<label for="code" class="col-sm-2 control-label text-right">Exp. Date</label>
							<div class="col-sm-3">
								<div class="input-group">
								<input type="text" name="xdate" id="xdate" class="form-control tanggal" required="">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>	
							</div>
						</div>
						<div class="form-group">
							<label for="code" class="col-sm-2 control-label text-right">Remark</label>
							<div class="col-sm-3">
								<textarea name="remark" class="form-control"></textarea>
							</div>
						</div>							
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
								<button type="button" id="addDetail" class="btn btn-success"><i class="fa fa-cogs"></i> Add Detail</button>&nbsp;
								<button type="button" id="cancel" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</button>
							</div>
						</div>						
					</fieldset>
				</form>

				<div class="row">
					<div class="col-sm-12">
						<h3>Tariff Detail</h3>
						<table class="table table-bordered">
							<thead>
								<tr>
									<th>Tariff No Detail</th>
									<th>Repo Type</th>
									<th>From</th>
									<th>To</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>BLN2-1</td>
									<td>DEPOT to PORT</td>
									<td>DEPOT</td>
									<td>PORT</td>
									<td><a href="#" class="btn btn-xs btn-danger">delete</a></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

			</div> 
			<!-- end of widget_content -->

		</div>		

	</div>
</div>


<?= $this->endSection();?>

<!-- Load JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\RepoTariff\Views\js'); ?>

<?= $this->endSection();?>