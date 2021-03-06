<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<?php
if(isset($data) && ($data!='')) {
	$codate = date('d/m/Y',strtotime($data['codate']));
	$coexpdate = date('d/m/Y',strtotime($data['coexpdate']));
}
?>

<!-- STYLE FORM INPUT UNDERLINE -->
<style>
	.input-underline {
		border-top: none!important;
	    border-left: none!important;
	    border-right: none!important;
	    text-align: right!important;		
	}
	.hide-block { display:none!important; }
</style>


<div class="content">
	<div class="main-header">
		<h2><?=$page_title;?></h2>
		<em><?=$page_subtitle;?></em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> <?=((isset($act))&&($act!="")?$act:"")?>&nbsp;<?=$page_title;?></h3>
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
				<form id="fWO" class="form-horizontal" role="form">
					<?= csrf_field() ?>
					<input type="hidden" name="wonoid" id="wonoid">
					<fieldset>
					<table class="tbl-form" width="100%">
						<!-- 9 kolom -->
					<tbody>
						<tr>
<!-- 							<td class="text-right" width="130"><label for="cono" class="text-right">WO No :</label></td>
							<td width="300"><input type="text" name="wono" class="form-control" id="wono" value="<?=@$wo_number;?>" readonly></td> -->
							<td class="text-right" width="130"><label for="wodate" class="text-right">WO Date :</label></td>
							<td width="300"><input type="text" name="wodate" class="form-control" id="wodate" value="<?= date('d-m-Y');?>" readonly></td>
							<td class="text-right" width="130"><label for="wotype" class="text-right">WO Type :</label></td>
							<td width="300">
								<select name="wotype" id="wotype" class="form-control">
									<option value="">-select-</option>
									<option value="1">Stuffing</option>
									<option value="2">Stripping</option>
									<option value="3">Delivery</option>
									<option value="4">Sale Unit</option>
									<option value="5">Modifkasi</option>
									<option value="6">Other</option>
								</select>
							</td>

						</tr>
						<tr>
							<td class="text-right" width="130"><label for="woto" class="text-right">To :</label></td>
							<td><input type="text" name="woto" class="form-control" id="woto" value="<?=@$data['woto'];?>"></td>
							<td class="text-right" width="130" class="hide-block">
								<label for="wodate" class="text-right hide-block">Use Container In :</label>
								<label for="wodate" class="text-right"> On Stock Depo :</label>
							</td>
							<td>
								<label class="control-inline fancy-checkbox custom-color-green hide-block">
								<input type="checkbox" name="wopraorderin" id="wopraorderin" value="0">
								<span></span>
								</label>

								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="wostok" id="wostok" value="1" checked>
								<span><i></i>Yes</span>
								</label>
								<label class="control-inline fancy-radio custom-bgcolor-green">
								<input type="radio" name="wostok" id="wostok" value="0">
								<span><i></i>No</span>
								</label>								
							</td>
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="wofrom" class="text-right">From :</label></td>
							<td><input type="text" name="wofrom" class="form-control" id="wofrom" value="<?=@$data['wofrom'];?>"></td>
							<td class="text-right" width="130">
								<label for="wodate" class="text-right hide-block">Use Container Outs :</label>
								<label for="wodoro" class="text-right">RO/DO :</label>
							</td>
							<td>
								<label class="control-inline fancy-checkbox custom-color-green hide-block">
								<input type="checkbox" name="wopraorderout" id="wopraorderout" value="0">
								<span></span>	
								</label>
								<input type="text" name="wodoro" class="form-control" id="wodoro">
							</td>
						</tr>
						<tr>
							<td class="text-right" width="130"><label for="wocc" class="text-right">CC :</label></td>
							<td><input type="text" name="wocc" class="form-control" id="wocc" value="<?=@$data['wocc'];?>"></td>

							<td class="text-right" width="130"><label for="wocc" class="text-right">SI/SJ:</label></td>
							<td><input type="text" name="wosinum" class="form-control" id="wosinum" value="<?=@$data['wosinum'];?>"></td>
						</tr>		
						<tr>
							<td class="text-right" width="130"><label for="wocc" class="text-right">Notes :</label></td>
							<td><input type="text" name="wonotes" class="form-control" id="wonotes" value="<?=@$data['wonotes'];?>"></td>
							<td class="text-right" width="130"><label for="woopr" class="text-right">Principal :</label></td>
							<td><?=principal_dropdown();?></td>
						</tr>
						<tr>

<!-- 							<td class="text-right hide-block" width="130">
								<label for="wodate" class="text-right">Change Stock :</label>
							</td>
							<td class="hide-block">
								<label class="control-inline fancy-checkbox custom-color-green">
								<input type="checkbox" name="wostock" id="wostock" value="0">
								<span></span>
								</label>
							</td>		 -->					
						</tr>									
							
						<tr><td colspan="9">&nbsp;</td></tr>									
						<tr>
							<td colspan="9" class="text-center">
								<button type="submit" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
								<a href="<?=site_url('cfswo')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
							</td>
						</tr>						
					</tbody>
					</table>
					</fieldset>
				</form>

				<div id="contentDetail" class="hide-block">
					<legend>&nbsp;</legend>
					<ul class="nav nav-tabs" role="tablist">
						<li class="active"><a href="#proforma" role="tab" data-toggle="tab" aria-expanded="false" id="tab_proforma">PROFORMA</a></li>
						<li class=""><a href="#rab" role="tab" data-toggle="tab" aria-expanded="false" id="tab_rab">RAB</a></li>
						<li class="" id="tContainer"><a href="#container" role="tab" data-toggle="tab" aria-expanded="true" id="tab_container">CONTAINER</a></li>
						<li class=""><a href="#barang" role="tab" data-toggle="tab" aria-expanded="false" id="tab_barang">BARANG</a></li>
					</ul>

					<div class="tab-content">
						<div class="tab-pane fade active in" id="proforma">
							<?= $this->include('\Modules\CfsWorkOrder\Views\tab_proforma');?>
						</div>
						<div class="tab-pane fade" id="rab">
							<?= $this->include('\Modules\CfsWorkOrder\Views\tab_rab');?>
						</div>					
						<div class="tab-pane fade" id="container">
							<?= $this->include('\Modules\CfsWorkOrder\Views\tab_container');?>
						</div>
						<div class="tab-pane fade" id="barang">
							<?= $this->include('\Modules\CfsWorkOrder\Views\tab_barang');?>
						</div>
					</div>
					
				</div>
			</div>
		</div>
		<!-- end .widget -->
	</div>
</div>

<?= $this->endSection();?>

<!-- Load JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\CfsWorkOrder\Views\js'); ?>

<?= $this->endSection();?>