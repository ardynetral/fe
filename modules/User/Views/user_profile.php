<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Profile</h2>
		<em>user profile page</em>
	</div>
	<div class="main-content">
		<!-- NAV TABS -->
		<ul class="nav nav-tabs nav-tabs-custom-colored tabs-iconized">
			<li class="active"><a href="#profile-tab" data-toggle="tab"><i class="fa fa-user"></i> Profile</a></li>
			<li><a href="#activity-tab" data-toggle="tab"><i class="fa fa-rss"></i> Recent Activity</a></li>
			<li><a href="#settings-tab" data-toggle="tab"><i class="fa fa-gear"></i> Settings</a></li>
		</ul>
		<!-- END NAV TABS -->
		<div class="tab-content profile-page">
			<!-- PROFILE TAB CONTENT -->
			<div class="tab-pane profile active" id="profile-tab">
				<div class="row">
					<div class="col-md-3">
						<div class="user-info-left">
							<img src="<?php echo base_url();?>/themes/smartdepo/img/profile-avatar.png" alt="Profile Picture" />
							<h2><?=$user['username'];?> <i class="fa fa-circle green-font online-icon"></i><sup class="sr-only">active</sup></h2>
						</div>
					</div>
					<div class="col-md-9">
						<div class="user-info-right">
							<div class="basic-info">
								<h3><i class="fa fa-square"></i> Basic Information</h3>
								<p class="data-row">
									<span class="data-name">Full Name</span>
									<span class="data-value"><?=$user['username'];?></span>
								</p>
								<p class="data-row">
									<span class="data-name">Username</span>
									<span class="data-value"><?=$user['username'];?></span>
								</p>
<!-- 								<p class="data-row">
									<span class="data-name">Last Name</span>
									<span class="data-value"></span>
								</p> -->
								<p class="data-row">
									<span class="data-name">Last Login</span>
									<span class="data-value"></span>
								</p>
								<p class="data-row">
									<span class="data-name">Date Joined</span>
									<span class="data-value"></span>
								</p>
							</div>
							<div class="contact_info">
								<h3><i class="fa fa-square"></i> Contact Information</h3>
								<p class="data-row">
									<span class="data-name">Email</span>
									<span class="data-value"><?=$user['email'];?></span>
								</p>
								<p class="data-row">
									<span class="data-name">Phone</span>
									<span class="data-value"></span>
								</p>
								<p class="data-row">
									<span class="data-name">Address</span>
									<span class="data-value"></span>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END PROFILE TAB CONTENT -->
			<!-- ACTIVITY TAB CONTENT -->
			<div class="tab-pane activity" id="activity-tab">
				<ul class="list-unstyled activity-list">
					<li>
						<i class="fa fa-shopping-cart activity-icon pull-left"></i>
						<p>
							<a href="#">Jonathan</a> commented on <a href="#">Special Deal 2013</a> <span class="timestamp">12 minutes ago</span>
						</p>
					</li>
				</ul>
				<p class="text-center more"><a href="#" class="btn btn-custom-primary">View more <i class="fa fa-long-arrow-right"></i></a></p>
			</div>
			<!-- END ACTIVITY TAB CONTENT -->
			<!-- SETTINGS TAB CONTENT -->
			<div class="tab-pane settings" id="settings-tab">
				<form class="form-horizontal" role="form">
					<fieldset>
						<h3><i class="fa fa-square"></i> Change Password</h3>
						<div class="form-group">
							<label for="old-password" class="col-sm-3 control-label">Old Password</label>
							<div class="col-sm-4">
								<input type="password" id="old-password" name="old-password" class="form-control">
							</div>
						</div>
						<hr />
						<div class="form-group">
							<label for="password" class="col-sm-3 control-label">New Password</label>
							<div class="col-sm-4">
								<input type="password" id="password" name="password" class="form-control">
							</div>
						</div>
						<div class="form-group">
							<label for="password2" class="col-sm-3 control-label">Repeat Password</label>
							<div class="col-sm-4">
								<input type="password" id="password2" name="password2" class="form-control">
							</div>
						</div>
					</fieldset>
				</form>
				<p class="text-center"><a href="#" class="btn btn-custom-primary"><i class="fa fa-floppy-o"></i> Save Changes</a></p>
			</div>
			<!-- END SETTINGS TAB CONTENT -->
		</div>
	</div>
</div>

<?= $this->endSection();?>