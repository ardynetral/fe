<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<?php
if(isset($data) && ($data!='')) {
	$codate = date('d/m/Y',strtotime($data['codate']));
	$coexpdate = date('d/m/Y',strtotime($data['coexpdate']));
}
?>


<div class="content">
	<div class="main-header">
		<h2><?=$page_title;?></h2>
		<em><?=$page_subtitle;?></em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> <?=((isset($act))&&($act!="")?$act:"")?> Estimation</h3>
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
				<form id="fContract" class="form-horizontal" role="form">
					<?= csrf_field() ?>
					<fieldset>
					<table class="tbl-form" width="100%">
						<!-- 9 kolom -->
					<tbody>
						<tr>
							<td class="text-right" width="130">Container No :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">EOR No :</td>
							<td colspan="3"><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Date :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Time :</td>
							<td colspan="3"><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">ID Code :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Type :</td>
							<td colspan="3"><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Lenght :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Height :</td>
							<td colspan="3"><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Contract No :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Expired :</td>
							<td colspan="3"><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Surveyor :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Survey Date :</td>
							<td colspan="3"><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr>
							<td class="text-right" width="130">Est Version :</td>
							<td><input type="text" name="" class="form-control" id="" value="<?='';?>"></td>
							<td class="text-right">Survey Condition :</td>
							<td colspan="3"><input type="text" name="" id="" class="form-control" value="<?='';?>"></td>
							<td ></td>
							<td ></td>
						</tr>
						<tr><td colspan="6"></td></tr>
						<tr>
							<td></td>
							<td colspan="5">
								<?php if(isset($act)&&($act=='view')):?>
								<!-- <button type="button" id="updateData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Update</button>&nbsp; -->
								<button type="button" id="cancel" class="btn btn-default"><i class="fa fa-ban"></i> Back</button>								
								<?php else: ?>
								<button type="button" id="saveData" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
								<button type="button" id="addDetail" class="btn btn-success"><i class="fa fa-list"></i> Add Detail</button>&nbsp;
								<a href="<?=site_url('estimation')?>" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</a>
								<?php endif; ?>
							</td>
						</tr>	
					</tbody>
				</table>
			</fieldset>
		</form>

		<div class="row">
			<div class="col-lg-4"> 
				<div class="widget widget-table">
					<div class="widget-header">
						<h3><i class="fa fa-table"></i> Form Input Estimation Detail</h3>
					</div>
					<div class="widget-content">
						<form id="formDetail" class="form-horizontal" role="form">
							<input type="hidden" name="csrf_test_name" value="8cfe7a8c003a7f787f5f0eb4f24fb8f5">		<input type="hidden" name="pracrnoid" id="pracrnoid">
							<fieldset>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Container No. </label>
									<div class="col-sm-8">
										<input type="hidden" name="pra_id" class="form-control" id="pra_id">
										<input type="text" name="crno" class="form-control" id="crno">
										<i class="err-crno text-danger" style="display: none;"></i>
									</div>
								</div>	
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">ID Code </label>
									<div class="col-sm-8">
										<?=ccode_dropdown("");?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Container Type</label>
									<div class="col-sm-8">
										<input type="text" id="ctcode" class="form-control" readonly="">
									</div>
								</div>	

								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Lenght</label>
									<div class="col-sm-8">
										<input type="text" name="cclength" id="cclength" class="form-control" readonly="">
									</div>	
								</div>	
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Height</label>
									<div class="col-sm-8">
										<input type="text" name="ccheight" id="ccheight" class="form-control" readonly="">
									</div>	
								</div>			
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">F/E</label>
									<div class="col-sm-8">
										<label class="control-inline fancy-radio custom-bgcolor-green">
											<input type="radio" name="cpife" id="cpife" value="1">
											<span><i></i>Full</span>
										</label>
										<label class="control-inline fancy-radio custom-bgcolor-green">
											<input type="radio" name="cpife" id="cpife" value="0" checked="">
											<span><i></i>Empty</span>
										</label>				
									</div>	
								</div>					
					<!-- 			<div class="form-group">
									<label class="col-sm-4 control-label text-right">Hold</label>
									<div class="col-sm-8">
										<label class="control-inline fancy-checkbox custom-color-green"> -->
											<input type="hidden" name="cpishold" id="cpishold" value="0">
					<!-- 						<span></span>
										</label>
									</div>	
								</div>	 -->
								<div class="form-group">
									<label class="col-sm-4 control-label text-right">Remark</label>
									<div class="col-sm-8">
										<input type="text" name="cpiremark" id="cpiremark" class="form-control">
									</div>	
								</div>			
								<div class="form-group">
									<label for="cpideliver" class="col-sm-4 control-label text-right">File Upload</label>
									<div class="col-sm-6">
										<input type="file" name="files[]" class="form-control" id="files" multiple="true" required="">
									</div>
								</div>						
								<div class="form-group">
									<div class="col-sm-offset-4 col-sm-8">
										<button type="button" id="saveDetail" class="btn btn-primary" disabled=""><i class="fa fa-check-circle"></i> Save</button>
									</div>
								</div>						
							</fieldset>
						</form>			
					</div>
				</div>
			</div>
			<div class="col-lg-8">
				<div class="widget widget-table">
					<div class="widget-header">
						<h3><i class="fa fa-table"></i> List Final Estimation</h3>
					</div>
					<div class="widget-content">
						<table class="table" id="tblList_add">
							<thead>
								<tr>
									<th>No</th>
									<th>COM</th>
									<th>DT</th>
									<th>RM</th>
									<th>CM</th>
									<th>BCE</th>
									<th>SIZE</th>
									<th>MU</th>
									<th>QTY</th>
									<th>MHR</th>
									<th>CUR</th>
									<th>DESC</th>
									<th>Lab. Cost</th>
									<th>Mat. Cost</th>
								</tr>
							</thead>
							<!-- <tbody>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								</tr>
							</tbody> -->
						</table>
					</div>
				</div>				
			</div>
		</div>
	</div>
</div>

<?= $this->endSection();?>

<!-- Load JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\Estimation\Views\js'); ?>

<?= $this->endSection();?>