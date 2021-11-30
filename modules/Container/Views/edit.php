<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>Container</h2>
		<em>add container </em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> Add Container</h3>
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


				<!-- <form method="post" action="<?=site_url('/set_user')?>" class="form-horizontal" role="form"> -->
				<form id="#formContainer" class="form-horizontal" role="form">
					<?= csrf_field() ?>
					<fieldset>
						<div class="form-group">
							<label class="col-sm-2 control-label text-right">Container No. </label>
							<div class="col-sm-3">
								<input type="text" name="crno" class="form-control" id="crno" value="<?=$container['crno']?>">
							</div>
						</div>	
						<div class="form-group">
							<label class="col-sm-2 control-label text-right">ID Code </label>
							<div class="col-sm-3">
								<?=$ccode_dropdown;?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label text-right">Owner</label>
							<div class="col-sm-3">
								<input type="text" name="crowner" class="form-control" id="crowner" value="<?=$container['crowner']?>">
							</div>
						</div>	
						<div class="form-group">
							<label class="col-sm-2 control-label text-right">Container Type</label>
							<div class="col-sm-1">
								<input type="text" id="ctcode_view" class="form-control" value="<?=$container['container_code']['ctcode']?>" disabled>
								<input type="hidden" name="ctcode" class="form-control" id="ctcode" value="<?=$container['container_code']['ctcode']?>">
							</div>
						</div>	
						<div class="form-group row">
							<label class="col-sm-2 control-label text-right"></label>
							<div class="col-sm-2">
								<label class="control-inline fancy-checkbox custom-bgcolor-green">
									<input type="checkbox" name="crcdp" id="crcdp" value="<?=$container['crcdp']?>" <?=((isset($container['crcdp']) && $container['crcdp']==1) ? ' checked' : '');?>>
									<span>CDP</span>
								</label>
								<label class="control-inline fancy-checkbox custom-bgcolor-green">
									<input type="checkbox" name="cracep" id="cracep" value="<?=$container['cracep']?>" <?=((isset($container['cracep']) && $container['cracep']==1) ? ' checked' : '');?>>
									<span>ACEP</span>
								</label>
							</div>						
						</div>
						<div class="form-group row">
							<label class="col-sm-2 control-label text-right"></label>
							<div class="col-sm-6">
								<label class="control-inline fancy-checkbox custom-bgcolor-green">
									<input type="checkbox" name="crcsc" id="crcsc" value="<?=$container['crcsc']?>" <?=((isset($container['crcsc']) && $container['crcsc']==1) ? ' checked' : '');?>>
									<span>CSC</span>
								</label>
								<label class="control-inline">
									<input type="text" name="crmmyy" id="crmmyy" placeholder="MMYY" class="form-control" value="<?=$container['crmmyy']?>">
								</label>
								<span class="control-inline">MMYY</span>
							</div>
						</div>	
						<div class="form-group row">
							<label class="col-sm-2 control-label text-right">Lenght/ Height</label>
							<div class="col-sm-2">
								<label class="control-inline">
									<input type="text" name="cclength" id="cclength" class="form-control col-xs" value="<?=$container['container_code']['cclength']?>" disabled>
								</label>
								<span class="control-inline">feet</span>	
							</div>	
							<div class="col-sm-2">
								<label class="control-inline">
									<input type="text" name="ccheight" id="ccheight" class="form-control col-xs" value="<?=$container['container_code']['ccheight']?>" disabled>
								</label>
								<span class="control-inline">feet</span>	
							</div>	
						</div>	
						<div class="form-group row">
							<label class="col-sm-2 control-label text-right">Gross Weight</label>
							<div class="col-sm-2">
								<label class="control-inline">
									<input type="text" name="crweightk" id="crweightk" class="form-control col-xs" value="<?=$container['crweightk']?>">
								</label>
								<span class="control-inline">Kgs</span>	
							</div>	
							<div class="col-sm-2">
								<label class="control-inline">
									<input type="text" name="crweightl" id="crweightl" class="form-control col-xs" value="<?=$container['crweightl']?>">
								</label>
								<span class="control-inline">Lbs</span>	
							</div>	
						</div>							
						<div class="form-group row">
							<label class="col-sm-2 control-label text-right">Tarre</label>
							<div class="col-sm-2">
								<label class="control-inline">
									<input type="text" name="crtarak" id="crtarak" class="form-control col-xs" value="<?=$container['crtarak']?>">
								</label>
								<span class="control-inline">Kgs</span>	
							</div>	
							<div class="col-sm-2">
								<label class="control-inline">
									<input type="text" name="crtaral" id="crtaral" class="form-control col-xs" value="<?=$container['crtaral']?>">
								</label>
								<span class="control-inline">Lbs</span>	
							</div>	
						</div>	
						<div class="form-group row">
							<label class="col-sm-2 control-label text-right">Netto</label>
							<div class="col-sm-2">
								<label class="control-inline">
									<input type="text" name="crnetk" id="crnetk" class="form-control col-xs" value="<?=$container['crnetk']?>">
								</label>
								<span class="control-inline">Kgs</span>	
							</div>	
							<div class="col-sm-2">
								<label class="control-inline">
									<input type="text" name="crnetl" id="crnetl" class="form-control col-xs" value="<?=$container['crnetl']?>">
								</label>
								<span class="control-inline">Lbs</span>	
							</div>	
						</div>	
						<div class="form-group row">
							<label class="col-sm-2 control-label text-right">Volume</label>
							<div class="col-sm-2">
								<label class="control-inline">
									<input type="text" name="crvol" id="crvol" class="form-control col-xs" value="<?=$container['crvol']?>">
								</label>
								<span class="control-inline">Cbm</span>	
							</div>	
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label text-right">Material</label>
							<div class="col-sm-3">
								<?=$material;?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label text-right">Manufacture</label>
							<div class="col-sm-2">
								<input type="text" name="crmanuf" id="crmanuf" class="form-control" value="<?=$container['crmanuf']?>">
							</div>
						</div>	
						<div class="form-group">
							<label class="col-sm-2 control-label text-right">Manufacture Date</label>
							<div class="col-sm-2">
								<input type="text" name="crmandat" id="crmandat" class="form-control" value="<?=$container['crmandat']?>">
								<p class="help-block text-right textarea-msg"><span class="text-muted">(MM/YY)</span></p>
							</div>
						</div>																									
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="button" id="updateContainer" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>
								<a href="<?=site_url('container')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
							</div>
						</div>						
					</fieldset>
				</form>						
			</div>
		</div>		

	</div>
</div>


<?= $this->endSection();?>


<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\Container\Views\js'); ?>

<?= $this->endSection();?>
