<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>User</h2>
		<em>add user</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> Add User</h3>
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
							<label for="fullname" class="col-sm-2 control-label text-right">Fullname</label>
							<div class="col-sm-3">
								<input type="text" name="fullname" class="form-control" id="fullname">
							</div>
						</div>	
						<div class="form-group">
							<label for="username" class="col-sm-2 control-label text-right">Username</label>
							<div class="col-sm-3">
								<input type="text" name="username"class="form-control" id="username">
							</div>
						</div>						
						<div class="form-group">
							<label for="email" class="col-sm-2 control-label text-right">Email</label>
							<div class="col-sm-3">
								<input type="text" name="email"class="form-control" id="email">
							</div>
						</div>
						<div class="form-group">
							<label for="password" class="col-sm-2 control-label text-right">Password</label>
							<div class="col-sm-3">
								<input type="text" name="password"class="form-control" id="password">
							</div>
						</div>
						<div class="form-group">
							<label for="repeat_password" class="col-sm-2 control-label text-right">Repeat Password</label>
							<div class="col-sm-3">
								<input type="text" name="repeat_password" class="form-control" id="repeat_password">
							</div>
						</div>																				
						<div class="form-group">
							<label for="group" class="col-sm-2 control-label text-right">Group User</label>
							<div class="col-sm-3">
								<?=$group;?>
							</div>
						</div>
						<div class="form-group" id="input_principal">
							<label for="principal" class="col-sm-2 control-label text-right">Principal</label>
							<div class="col-sm-4" id="pr-dropdown">
								
							</div>
						</div>						
						<div class="form-group">
							<div class="col-sm-offset-2 col-sm-10">
								<button type="button" id="saveUser" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>&nbsp;
								<button type="button" id="cancel" class="btn btn-default"><i class="fa fa-times-circle"></i> Cancel</button>
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

	<?= $this->include('\Modules\User\Views\js'); ?>

<?= $this->endSection();?>