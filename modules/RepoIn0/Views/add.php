<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>Pra In</h2>
		<em>Order Pra in</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> Order Pra In</h3>
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
						<div class="col-sm-6">
							<div class="form-group">
								<label for="code" class="col-sm-5 control-label text-right">Principal</label>
								<div class="col-sm-7">
									<?php if($prcode=="0"):
										echo principal_dropdown($selected=""); 
										$cucode="";
									else:
										?>
									<input type="text" name="prcode" class="form-control" id="prcode" value="<?=$prcode;?>">
								<?php endif; ?>
								</div>
							</div>	
							<div class="form-group">
								<label for="cucode" class="col-sm-5 control-label text-right">Customer</label>
								<div class="col-sm-7">
									<input type="text" name="cucode" class="form-control" id="cucode" value="<?=$cucode?>">
								</div>
							</div>	
							<div class="form-group">
								<label for="cpidish " class="col-sm-5 control-label text-right">Origin Port</label>
								<div class="col-sm-4">
									<?=port_dropdown();?>
								</div>
								<label class="col-sm-3 control-label">* Port Country</label>
							</div>	
							<div class="form-group">
								<label for="cpidisdat" class="col-sm-5 control-label text-right">Discharge Date</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input type="text" name="cpidisdat" id="cpidisdat" class="form-control">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									</div>									
								</div>
							</div>
							<div class="form-group">
								<label for="liftoffcharge" class="col-sm-5 control-label text-right">Lift Off Charged in Depot</label>
								<div class="col-sm-7">
									<input type="checkbox" name="liftoffcharge" class="" id="liftoffcharge">
								</div>
							</div>
							<div class="form-group">
								<label for="cpdepo" class="col-sm-5 control-label text-right">Depot</label>
								<div class="col-sm-7">
									<?=depo_dropdown();?>
								</div>
							</div>	
							<h2>&nbsp;</h2>
							<div class="row">
								<div class="col-sm-8 col-sm-offset-4">
									<div class="row">
										<legend>Quantity : </legend>
									</div>
									<div class="row">
										<div class="col-sm-6">
											<label class="col-sm-offset-4"><b>STD</b></label>
											<div class="form-group">
												<label for="code" class="col-sm-2 control-label text-right">20"</label>
												<div class="col-sm-8">
													<input type="text" name="name" class="form-control" id="name">
												</div>
											</div>								
											<div class="form-group">
												<label for="code" class="col-sm-2 control-label text-right">40"</label>
												<div class="col-sm-8">
													<input type="text" name="name" class="form-control" id="name">
												</div>
											</div>												
										</div>
										<div class="col-sm-6">
											<label  class="col-sm-offset-4"><b>HC</b></label>
											<div class="form-group">
												<label for="code" class="col-sm-2 control-label text-right">20"</label>
												<div class="col-sm-8">
													<input type="text" name="name" class="form-control" id="name">
												</div>
											</div>								
											<div class="form-group">
												<label for="code" class="col-sm-2 control-label text-right">40"</label>
												<div class="col-sm-8">
													<input type="text" name="name" class="form-control" id="name">
												</div>
											</div>
											<div class="form-group">
												<label for="code" class="col-sm-2 control-label text-right">45"</label>
												<div class="col-sm-8">
													<input type="text" name="name" class="form-control" id="name">
												</div>
											</div>											
										</div>										
									</div>									
								</div>
							</div>
						</div>

						<div class="col-sm-6">
							<div class="form-group">
								<label for="cpiorderno" class="col-sm-4 control-label text-right">Pra In No</label>
								<div class="col-sm-6">
									<input type="text" name="cpiorderno" class="form-control" id="cpiorderno" readonly>
								</div>
							</div>								
							<div class="form-group">
								<label for="cpipratgl" class="col-sm-4 control-label text-right">Pra In Date</label>
								<div class="col-sm-6">
									<div class="input-group">
										<input type="text" name="cpipratgl" id="cpipratgl" class="form-control">
										<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
									</div>	
								</div>
							</div>								
							<div class="form-group">
								<label for="cpirefin" class="col-sm-4 control-label text-right">Reff In No #</label>
								<div class="col-sm-6">
									<input type="text" name="cpirefin" class="form-control" id="cpirefin">
								</div>
							</div>								
							<div class="form-group">
								<label for="cpijam" class="col-sm-4 control-label text-right">Time In</label>
								<div class="col-sm-4">
									<input type="text" name="cpijam" class="form-control" id="cpijam">
								</div>
								<label class="col-sm-2 control-label">hh:mm:ss</label>
							</div>								
							<div class="form-group">
								<label for="cpives" class="col-sm-4 control-label text-right">Vessel</label>
								<div class="col-sm-6">
									<?=vessel_dropdown();?>
								</div>
							</div>															
							<div class="form-group">
								<label for="cpivoyid" class="col-sm-4 control-label text-right">Vessel Operator</label>
								<div class="col-sm-6">
									<input type="text" name="cpivoyid" class="form-control" id="cpivoyid">
								</div>
							</div>
							<div class="form-group">
								<label for="code" class="col-sm-4 control-label text-right">Voyage</label>
								<div class="col-sm-6">
									<input type="text" name="name" class="form-control" id="name">
								</div>
							</div>								
							<div class="form-group">
								<label for="cpicargo" class="col-sm-4 control-label text-right">Ex Cargo</label>
								<div class="col-sm-6">
									<input type="text" name="cpicargo" class="form-control" id="cpicargo">
								</div>
							</div>
							<div class="form-group">
								<label for="cpideliver" class="col-sm-4 control-label text-right">Redeliverer</label>
								<div class="col-sm-6">
									<input type="text" name="cpideliver" class="form-control" id="cpideliver">
								</div>
							</div>
						</div>	
					</fieldset>
					<div class="form-footer">
						<div class="form-group text-center">
							<button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
							<button type="button" id="cancel" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</button>
						</div>	
					</div>
				</form>
			</div>
		</div>		

	</div>
</div>


<?= $this->endSection();?>

<!-- Load JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\PraIn\Views\js'); ?>

<?= $this->endSection();?>