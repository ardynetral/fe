<?= $this->extend('smartdepo/auth/template') ?>

<?= $this->section('content') ?>

<div class="login-box center-block">
	<form class="form-horizontal" role="form">
		<h5>Enter your registered e-mail. We will send you a verification code to reset your password.</h5>
		<div class="form-group">
			<label for="username" class="control-label sr-only">Email</label>
			<div class="col-sm-12">
				<div class="input-group">
					<input type="text" placeholder="Email Address" id="email" class="form-control">
					<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
				</div>
			</div>
		</div>
		<button class="btn btn-custom-primary btn-lg btn-block btn-auth" id="btnResetPassword"><i class="fa fa-arrow-circle-o-right"></i> Reset Password</button>
	</form>
</div>

<?= $this->endSection(); ?>