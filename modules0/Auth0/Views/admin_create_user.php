<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>User</h2>
		<em>create new user</em>
	</div>
	<div class="main-content">

		<div class="widget">
			<div class="widget-header">
				<h3><i class="fa fa-edit"></i> Create New User</h3>
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


				<!-- <form method="post" action="<?=site_url('/set_user')?>" class="form-horizontal" role="form"> -->
				<form id="#formUser" class="form-horizontal" role="form">
					<?= csrf_field() ?>
					<fieldset>
						<legend>General Information</legend>
						<div class="form-group">
							<label for="first-name" class="col-sm-3 control-label">First Name</label>
							<div class="col-sm-9">
								<input type="text" name="first_name" class="form-control" id="first_name" placeholder="First Name">
							</div>
						</div>	
						<div class="form-group">
							<label for="ticket-name" class="col-sm-3 control-label">Last Name</label>
							<div class="col-sm-9">
								<input type="text"  name="last_name"class="form-control" id="last_name" placeholder="Last Name">
							</div>
						</div>
						<legend>Account Information</legend>

						<input type="hidden" name="group_id" id="group_id" class="form-control" value="2">

						<div class="form-group" value="2">
							<label for="ticket-name" class="col-sm-3 control-label">Username</label>
							<div class="col-sm-9">
								<input type="text" name="username" class="form-control" id="username" placeholder="Username">
							</div>
						</div>
						<div class="form-group">
							<label for="ticket-name" class="col-sm-3 control-label">Password</label>
							<div class="col-sm-9">
								<input type="text" name="password" class="form-control" id="password" placeholder="Passwrd">
							</div>
						</div>						
						<div class="form-group">
							<label for="ticket-email" class="col-sm-3 control-label">Email</label>
							<div class="col-sm-9">
								<input type="email" name="email" class="form-control" id="email" placeholder="Email">
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9">
								<button type="button" id="saveUser" class="btn btn-primary"><i class="fa fa-check-circle"></i> Save</button>
							</div>
						</div>						
					</fieldset>
				</form>
			</div>
		</div>		

	</div>
</div>


<?= $this->endSection();?>


<?= $this->Section('script_js');?>

<script type="text/javascript">
$(document).ready(function(){
	$("#saveUser").click(function(e){
		e.preventDefault();
		var formData = "first_name=" + $("#first_name").val();
		formData += "&username=" + $("#username").val();
		formData += "&password=" + $("#password").val();
		formData += "&email=" + $("#email").val();
		formData += "&group_id=" + $("#group_id").val();
		console.log(formData);
		$.ajax({
			url: "<?php echo site_url('set_user'); ?>",
			// headers: {'X-Requested-With': 'XMLHttpRequest'},
			type: "POST",
			data: formData,
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					window.location.href = "<?php echo site_url('dashboard'); ?>";
				} else {
					// $("#alert").html(
						'<div class="alert alert-danger">'+
						json.message +
						'</div>'
					// );
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+json.message+'</div>'
					});						
				}
			}
		});
	});
});
</script>
<?= $this->endSection();?>