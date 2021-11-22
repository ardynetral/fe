<?= $this->extend('smartdepo/auth/template') ?>

<?= $this->section('content') ?>

<div class="login-box center-block">
	<form class="form-horizontal" role="form">
		<h4 class="text-center">Change your password.</h4>
		<br>
		<div class="form-group">
			<label for="username" class="control-label sr-only">Username</label>
			<div class="col-sm-12">
				<div class="input-group">
					<input type="text" placeholder="Username" name="username" id="username" class="form-control">
					<span class="input-group-addon"><i class="fa fa-key"></i></span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="email" class="control-label sr-only">Email</label>
			<div class="col-sm-12">
				<div class="input-group">
					<input type="text" placeholder="Email" name="email" id="email" class="form-control">
					<span class="input-group-addon"><i class="fa fa-key"></i></span>
				</div>
			</div>
		</div>
		<div class="form-group">
			<label for="newpassword" class="control-label sr-only">New Password</label>
			<div class="col-sm-12">
				<div class="input-group">
					<input type="text" placeholder="New Password" name="newpassword" id="newpassword" class="form-control">
					<span class="input-group-addon"><i class="fa fa-key"></i></span>
				</div>		
			</div>
		</div>

		<button class="btn btn-custom-primary btn-lg btn-block btn-auth"><i class="fa fa-arrow-circle-o-right"></i> Save New Password</button>
	</form>
</div>

<?= $this->endSection(); ?>