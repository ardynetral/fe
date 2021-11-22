<?= $this->extend('smartdepo/auth/template') ?>

<?= $this->section('content') ?>

<div class="login-box center-block">
		<h2 class="text-center">Congratulation</h2>
		<h4 class="text-center">Your Account is Activated</h4>
		<a href="<?=site_url('login');?>" class="btn btn-primary btn-lg btn-block btn-auth"><i class="fa fa-arrow-circle-o-right"></i> Go to Login Page</a>
		<br><p class="text-center"><i>OR</i><p>
		<a href="<?=site_url('change_password');?>" class="btn btn-custom-primary btn-lg btn-block btn-auth"><i class="fa fa-key"></i> Change Your Password</a>

</div>

<?= $this->endSection(); ?>