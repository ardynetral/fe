<div class="top-bar navbar-fixed-top">
	<div class="container">
		<div class="clearfix">
			<a href="#" class="pull-left toggle-sidebar-collapse"><i class="fa fa-bars"></i></a>
			<!-- logo -->
			<div class="pull-left left logo">
				<!-- <a href="index.html"><img src="<?=base_url();?>/public/themes/smartdepo/img/kingadmin-logo-white.png" alt="KingAdmin - Admin Dashboard" /></a> -->
				<h4 style="color:#FFFFFF;margin-top:0;">SmartDepo</h4>
				<h1 class="sr-only">SmartDepo Dashboard</h1>
			</div>
			<!-- end logo -->
			<div class="pull-right right">
				<!-- top-bar-right -->
				<div class="top-bar-right">
					<!-- logged user and the menu -->
					<div class="logged-user">
						<div class="btn-group">
							<a href="#" class="btn btn-link dropdown-toggle" data-toggle="dropdown">
								<img src="<?php echo base_url();?>/public/themes/smartdepo/img/user-avatar.png" alt="User Avatar" />
								<span class="name"><?=session()->get('username')?></span> <span class="caret"></span>
							</a>
							<ul class="dropdown-menu" role="menu">
								<li>
									<a href="<?=site_url('user_profile');?>">
										<i class="fa fa-user"></i>
										<span class="text">Profile</span>
									</a>
								</li>
								<li>
									<a href="<?=site_url('logout');?>">
										<i class="fa fa-power-off"></i>
										<span class="text">Logout</span>
									</a>
								</li>
							</ul>
						</div>
					</div>
					<!-- end logged user and the menu -->
				</div>
				<!-- end top-bar-right -->
			</div>
		</div>
	</div>
	<!-- /container -->
</div>