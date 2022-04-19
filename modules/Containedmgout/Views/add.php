<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>Container Hold/Release/By Pass</h2>
		<em>add Container Hold/Release/By Pass</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> Add Container Hold/Release/By Pass</h3>
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
						<div class="form-group" style="display:none;">
							<label for="chorderno" class="col-sm-2 control-label text-right">Order Id.</label>
							<div class="col-sm-3">
								<input type="text" name="chorderno" class="form-control" id="chorderno" value="0">
							</div>
						</div>	
						<div class="form-group">
							<label for="crno" class="col-sm-2 control-label text-right">Container No</label>
							<div class="col-sm-3">
								<input type="text" name="crno" class="form-control" id="crno">
							</div>
						</div>	
						<div class="form-group">
							<label for="chtype" class="col-sm-2 control-label text-right">Action</label>
							<div class="col-sm-3">
								<!-- <input type="text" name="chtype" class="form-control" id="chtype"> -->
								<select name="chtype" id="chtype" class="form-control" required>
									<option value="">- Select -</option>
									<option value="HL">HOLD</option>
									<option value="AC">UN-HOLD</option>
									<option value="AC">BY PASS</option>

								</select>
							</div>
							<!--  HL = HOLD,  AC = UN-HOLD, AC = BY PASS -->
						</div>							
						<div class="form-group">
							<label for="chfrom" class="col-sm-2 control-label text-right">From</label>
							<div class="col-sm-3">
								<input type="text" name="chfrom" class="form-control" id="chfrom">
							</div>
						</div>	
						<div class="form-group">
							<label for="chto" class="col-sm-2 control-label text-right">To</label>
							<div class="col-sm-3">
								<input type="text" name="chto" class="form-control" id="chto">
							</div>
						</div>		
						<div class="form-group">
							<label for="chnote" class="col-sm-2 control-label text-right">Note</label>
							<div class="col-sm-3">
								<input type="text" name="chnote" class="form-control" id="chnote">
							</div>
						</div>							
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
								<a href="<?=site_url('containedmgout')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
							</div>
						</div>						
					</fieldset>
				</form>
			</div>
		</div>		

	</div>
</div>


<?= $this->endSection();?>

<!-- Load JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\Containedmgout\Views\js'); ?>

<?= $this->endSection();?>