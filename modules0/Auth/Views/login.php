<?= $this->extend('smartdepo/auth/template') ?>

<?= $this->section('content') ?>


<div class="login-box center-block">
	<p class="title text-center">Use your username & Password</p>
	<?php if(isset($validasi)):?>
		<div class="alert alert-danger">
			<?=$validasi; ?>
		</div>
	<?php endif; ?>

	<?php if(isset($message)): ?>
		<p class="alert alert-danger">
			<?php foreach($message as $msg) { echo $msg . "<br>"; } ?>
		</p>
	<?php endif;?>

	<form class="form-horizontal" method="POST" action="<?=site_url('set_login');?>">
		<?= csrf_field() ?>
		<div class="form-group">
			<label for="username" class="control-label sr-only">Username</label>
			<div class="col-sm-12">
				<div class="input-group">
					<input type="text" name="username" id="username" placeholder="username" class="form-control">
					<span class="input-group-addon"><i class="fa fa-user"></i></span>
				</div>
			</div>
		</div>
		<label for="password" class="control-label sr-only">Password</label>
		<div class="form-group">
			<div class="col-sm-12">
				<div class="input-group">
					<input type="password" name="password" id="password" placeholder="password" class="form-control">
					<span class="input-group-addon"><i class="fa fa-lock"></i></span>
				</div>
			</div>
		</div>
		<label class="fancy-checkbox">
			<input type="checkbox">
			<span>Remember me next time</span>
		</label>
		<button type="submit" id="setLogin" class="btn btn-custom-primary btn-lg btn-block btn-auth"><i class="fa fa-arrow-circle-o-right"></i> Login</button>
	</form>
	<div class="links">
		<p><a href="<?=site_url('forgot_password');?>">Forgot Username or Password?</a></p>
		<p><a href="<?=site_url('register');?>">Create New Account</a></p>
	</div>
</div>

<?=$this->endSection(); ?>