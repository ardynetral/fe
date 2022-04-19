<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

 <style type="text/css">
	.hideBlock{
		display: none;
	}
	.showBlock{
		display: block;
	}
</style>

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

				<form id="Rtheader" class="form-horizontal" role="form" method="post">
					<?= csrf_field() ?>
					<fieldset>
						<div class="form-group">
							<label for="prcode" class="col-sm-2 control-label text-right">Principal</label>
							<div class="col-sm-3">
								<?=principal_dropdown();?>
							</div>
						</div>	
						<div class="form-group">
							<label for="code" class="col-sm-2 control-label text-right">Tariff No</label>
							<div class="col-sm-3">
								<input type="text" name="rtno" class="form-control" id="rtno">
							</div>
						</div>	
						<div class="form-group">
							<label for="code" class="col-sm-2 control-label text-right">Begin Date</label>
							<div class="col-sm-3">
								<div class="input-group">
								<input type="text" name="rtdate" id="rtdate" class="form-control tanggal" required="">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>	
							</div>
						</div>	
						<div class="form-group">
							<label for="code" class="col-sm-2 control-label text-right">Exp. Date</label>
							<div class="col-sm-3">
								<div class="input-group">
								<input type="text" name="rtexpdate" id="rtexpdate" class="form-control tanggal" required="">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
								</div>	
							</div>
						</div>
						<div class="form-group">
							<label for="code" class="col-sm-2 control-label text-right">Remark</label>
							<div class="col-sm-3">
								<textarea name="rtremarks" id="rtremarks" class="form-control"></textarea>
							</div>
						</div>							
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="submit" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
								<button type="button" id="addDetail" class="btn btn-success" data-toggle="modal" data-target="#repoTariffDetailModal" data-backdrop="static" disabled><i class="fa fa-cogs"></i> Add Detail</button>&nbsp;
								<button type="button" id="cancel" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</button>
							</div>
						</div>						
					</fieldset>
				</form>

				<div class="row">
					<div class="col-sm-12">
						<h3>Tariff Detail</h3>
						<div class="table-responsive vscroll">
						<table class="table table-bordered" id="rt-detail">
							<thead>
								<tr>
									<th></th>
									<th>Repo IN/OUT</th>
									<th>Repo Type</th>
									<th>Principal</th>
									<th>Full/Empty</th>
									<th>Pack Curr</th>
									<th>Pack20</th>
									<th>Pack40</th>
									<th>Pack45</th>
									<th>Doc Curr</th>
									<th>Doc Method</th>
									<th>Doc Free</th>
									<th>Haul Curr</th>
									<th>Haul20</th>
									<th>Haul40</th>
									<th>Haul45</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
						</div>
					</div>
				</div>

			</div> 
			<!-- end of widget_content -->

		</div>		

	</div>
</div>


<?= $this->include('\Modules\RepoTariff\Views\add_detail'); ?>

<?= $this->endSection();?>

<!-- Load JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\RepoTariff\Views\js'); ?>

<?= $this->endSection();?>