<?= $this->extend('smartdepo/auth/template') ?>

<?= $this->section('content') ?>

<div class="register-box center-block">
	<form>
		<p class="title">Create Your Account</p>
		<input type="email" placeholder="email" class="form-control">
		<input type="password" placeholder="password" class="form-control">
		<input type="password" placeholder="repeat password" class="form-control">
		<label class="fancy-checkbox">
			<input type="checkbox">
			<span>I accept the <a href="#">Terms &amp; Agreements</a></span>
		</label>
		<button class="btn btn-custom-primary btn-lg btn-block btn-auth"><i class="fa fa-check-circle"></i> Create Account</button>
	</form>
</div>

<?= $this->endSection(); ?>