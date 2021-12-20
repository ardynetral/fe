<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>Principal</h2>
		<em>Principal page</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> Add Principal</h3>
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

				<form id="fPrincipal" class="form-horizontal" role="form" method="POST">
					<?= csrf_field() ?>
					<fieldset>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group row">
									<label for="prcode" class="col-sm-2 control-label text-right">Principal Code</label>
									<div class="col-sm-10">
										<input type="text" name="prcode" class="form-control" id="prcode" value="<?=@$data['prcode']?>" <?=(isset($act)&&($act=="edit") ? "readonly" : "")?>>
									</div>
								</div>	
								<div class="form-group row">
									<label for="cucode" class="col-sm-2 control-label text-right">Customers Code</label>
									<div class="col-sm-10">
										<input type="text" name="cucode" class="form-control" id="cucode" value="<?=@$data['cucode']?>">
									</div>
								</div>							
								<div class="form-group row">
									<label for="prname" class="col-sm-2 control-label text-right">Name</label>
									<div class="col-sm-10">
										<input type="text" name="prname" class="form-control" id="prname" value="<?=@$data['prname']?>">
									</div>
								</div>
								<div class="form-group row">
									<label for="praddr" class="col-sm-2 control-label text-right">Address</label>
									<div class="col-sm-10">
										<input type="text" name="praddr" class="form-control" id="praddr" value="<?=@$data['praddr']?>">
									</div>
								</div>					
								<div class="form-group row">
									<label for="cncode" class="col-sm-2 control-label text-right">Country</label>
									<div class="col-sm-10">
										<?=country_dropdown(@$data['cncode'])?>
									</div>
								</div>
								<div class="form-group row">
									<label for="prremark" class="col-sm-2 control-label text-right">Remark</label>
									<div class="col-sm-10">
										<input type="text" name="prremark" class="form-control" id="prremark" value="<?=@$data['prremark']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="prflag1" class="col-sm-2 control-label text-right">Prflag 1</label>
									<div class="col-sm-10">
										<input type="text" name="prflag1" class="form-control" id="prflag1" value="<?=@$data['prflag1']?>">
									</div>
								</div>							
								<div class="form-group row">
									<label for="prflag2" class="col-sm-2 control-label text-right">Prflag 2</label>
									<div class="col-sm-10">
										<input type="text" name="prflag2" class="form-control" id="prflag2" value="<?=@$data['prflag2']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="prautpp" class="col-sm-2 control-label text-right">prautapp</label>
									<div class="col-sm-10">
										<input type="text" name="prautapp" class="form-control" id="prautapp" value="<?=@$data['prautapp']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="prautbb" class="col-sm-2 control-label text-right">prautbb</label>
									<div class="col-sm-10">
										<input type="text" name="prautbb" class="form-control" id="prautbb" value="<?=@$data['prautbb']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="prautbm" class="col-sm-2 control-label text-right">prautbm</label>
									<div class="col-sm-10">
										<input type="text" name="prautbm" class="form-control" id="prautbm" value="<?=@$data['prautbm']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="przip" class="col-sm-2 control-label text-right">przip</label>
									<div class="col-sm-10">
										<input type="text" name="przip" class="form-control" id="przip" value="<?=@$data['przip']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="prphone" class="col-sm-2 control-label text-right">prphone</label>
									<div class="col-sm-10">
										<input type="text" name="prphone" class="form-control" id="prphone" value="<?=@$data['prphone']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="prfax" class="col-sm-2 control-label text-right">prfax</label>
									<div class="col-sm-10">
										<input type="text" name="prfax" class="form-control" id="prfax" value="<?=@$data['prfax']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="premail" class="col-sm-2 control-label text-right">premail</label>
									<div class="col-sm-10">
										<input type="text" name="premail" class="form-control" id="premail" value="<?=@$data['premail']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="prcontractno" class="col-sm-2 control-label text-right">prcontractno</label>
									<div class="col-sm-10">
										<input type="text" name="prcontractno" class="form-control" id="prcontractno" value="<?=@$data['prcontractno']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="prrepono" class="col-sm-2 control-label text-right">prrepono</label>
									<div class="col-sm-10">
										<input type="text" name="prrepono" class="form-control" id="prrepono" value="<?=@$data['prrepono']?>">
									</div>
								</div>						
							</div>

							<div class="col-sm-6">
								<div class="form-group row">
									<label for="prdamageno" class="col-sm-2 control-label text-right">prdamageno</label>
									<div class="col-sm-10">
										<input type="text" name="prdamageno" class="form-control" id="prdamageno" value="<?=@$data['prdamageno']?>">
									</div>
								</div>	
								<div class="form-group row">
									<label for="prexp" class="col-sm-2 control-label text-right">prexp</label>
									<div class="col-sm-10">
										<input type="text" name="prexp" class="form-control tanggal" id="prexp" value="<?=@date('d-m-Y',strtotime($data['prexp']))?>">
									</div>
								</div>									
								<div class="form-group row">
									<label for="prtocust1" class="col-sm-2 control-label text-right">prtocust1</label>
									<div class="col-sm-10">
										<input type="text" name="prtocust1" class="form-control" id="prtocust1" value="<?=@$data['prtocust1']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="prtocust2" class="col-sm-2 control-label text-right">prtocust2</label>
									<div class="col-sm-10">
										<input type="text" name="prtocust2" class="form-control" id="prtocust2" value="<?=@$data['prtocust2']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="prinfocu" class="col-sm-2 control-label text-right">prinfocu</label>
									<div class="col-sm-10">
										<input type="text" name="prinfocu" class="form-control" id="prinfocu" value="<?=@$data['prinfocu']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="prphcust" class="col-sm-2 control-label text-right">prphcust</label>
									<div class="col-sm-10">
										<input type="text" name="prphcust" class="form-control" id="prphcust" value="<?=@$data['prphcust']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="prfacust" class="col-sm-2 control-label text-right">prfacust</label>
									<div class="col-sm-10">
										<input type="text" name="prfacust" class="form-control" id="prfacust" value="<?=@$data['prfacust']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="prcccust1" class="col-sm-2 control-label text-right">prcccust1</label>
									<div class="col-sm-10">
										<input type="text" name="prcccust1" class="form-control" id="prcccust1" value="<?=@$data['prcccust1']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="prcccust2" class="col-sm-2 control-label text-right">prcccust2</label>
									<div class="col-sm-10">
										<input type="text" name="prcccust2" class="form-control" id="prcccust2" value="<?=@$data['prcccust2']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="prinfocc" class="col-sm-2 control-label text-right">prinfocc</label>
									<div class="col-sm-10">
										<input type="text" name="prinfocc" class="form-control" id="prinfocc" value="<?=@$data['prinfocc']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="prfmmtc" class="col-sm-2 control-label text-right">prfmmtc</label>
									<div class="col-sm-10">
										<input type="text" name="prfmmtc" class="form-control" id="prfmmtc" value="<?=@$data['prfmmtc']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="prccmtc" class="col-sm-2 control-label text-right">prccmtc</label>
									<div class="col-sm-10">
										<input type="text" name="prccmtc" class="form-control" id="prccmtc" value="<?=@$data['prccmtc']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="prfield1" class="col-sm-2 control-label text-right">prfield1</label>
									<div class="col-sm-10">
										<input type="text" name="prfield1" class="form-control" id="prfield1" value="<?=@$data['prfield1']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="eirnocedex" class="col-sm-2 control-label text-right">eirnocedex</label>
									<div class="col-sm-10">
										<input type="text" name="eirnocedex" class="form-control" id="eirnocedex" value="<?=@$data['eirnocedex']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="prcono" class="col-sm-2 control-label text-right">prcono</label>
									<div class="col-sm-10">
										<input type="text" name="prcono" class="form-control" id="prcono" value="<?=@$data['prcono']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="prdmno" class="col-sm-2 control-label text-right">prdmno</label>
									<div class="col-sm-10">
										<input type="text" name="prdmno" class="form-control" id="prdmno" value="<?=@$data['prdmno']?>">
									</div>
								</div>								
								<div class="form-group row">
									<label for="prrtno" class="col-sm-2 control-label text-right">prrtno</label>
									<div class="col-sm-10">
										<input type="text" name="prrtno" class="form-control" id="prrtno" value="<?=@$data['prrtno']?>">
									</div>
								</div>							
							</div>
						</div>
						
						<div class="row">
							<div class="col-sm-12 text-center">
								<h6>&nbsp;</h6>
								<?php if(isset($act)&&($act=="add")): ?>
								<button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
								<?php else: ?>
									<button type="button" id="updateData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>&nbsp;
								<?php endif; ?>
								<a href="<?=site_url('principal')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
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

	<?= $this->include('\Modules\Principal\Views\js'); ?>

<?= $this->endSection();?>