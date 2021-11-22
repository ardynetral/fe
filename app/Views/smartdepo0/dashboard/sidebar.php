<div id="left-sidebar" class="left-sidebar ">
<!-- main-nav -->

<?php $uri=service('uri');?>
<div class="sidebar-scroll">
	<nav class="main-nav">
		<ul class="main-menu">
			<li class="active"><a href="<?=site_url();?>"><i class="fa fa-dashboard fa-fw"></i><span class="text">Dashboard</span></a></li>
			<?php if(is_admin()):?>
			<li><a href="#" class="js-sub-menu-toggle"><i class="fa fa-gears fw"></i><span class="text">Setting</span>
				<i class="toggle-icon fa fa-angle-left"></i></a>
				<ul class="sub-menu ">
					<li><a href="<?=site_url('user');?>"><span class="text">User Management</span></a></li>
					<li><a href="<?=site_url('groups');?>"><span class="text">Group Management</span></a></li>
					<!-- <li><a href="#"><span class="text">Module Management</span></a></li> -->
				</ul>
			</li>

			<li><a href="#" class="js-sub-menu-toggle"><i class="fa fa-table fw"></i><span class="text">Master Data</span>
				<i class="toggle-icon fa fa-angle-left"></i></a>
				<ul class="sub-menu">
					<li><a href="<?=site_url('container');?>"><span class="text">Container</span></a></li>
					<li><a href="<?=site_url('ccode');?>"><span class="text">Container Code</span></a></li>
					<li><a href="<?=site_url('ctype');?>"><span class="text">Container Type</span></a></li>
					<li><a href="<?=site_url('damagetype');?>"><span class="text">Damage Type</span></a></li>
					<li><a href="<?=site_url('componen');?>"><span class="text">Componen</span></a></li>
					<li><a href="<?=site_url('city');?>"><span class="text">City</span></a></li>
					<li><a href="<?=site_url('country');?>"><span class="text">Country</span></a></li>
					<li><a href="<?=site_url('location');?>"><span class="text">Location</span></a></li>
					<li><a href="<?=site_url('material');?>"><span class="text">Material</span></a></li>
					<li><a href="<?=site_url('param');?>"><span class="text">Param</span></a></li>
					<li><a href="<?=site_url('port');?>"><span class="text">Port</span></a></li>
					<li><a href="<?=site_url('principal');?>"><span class="text">Principal</span></a></li>
					<li><a href="<?=site_url('vessel');?>"><span class="text">Vessel</span></a></li>
				</ul>
			</li>

			<?php endif; ?>

			<li class=""><a href="#" class="js-sub-menu-toggle"><i class="fa fa-user"></i><span class="text">User Account</span>
				<i class="toggle-icon fa fa-angle-left"></i></a>
				<ul class="sub-menu">
					<!-- <li><a href="<?=site_url('create_user');?>"><span class="text">Create User</span></a></li> -->
					<li><a href="<?=site_url('user_profile');?>"><i class="fa fa-circle-o"></i><span class="text">User Profile</span></a></li>
					<li><a href="<?=site_url('logout');?>"><i class="fa fa-circle-o"></i><span class="text">Logout</span></a></li>
				</ul>
			</li>

			<!-- <li><a href="<?=site_url('home/chart');?>"><i class="fa fa-bar-chart-o"></i><span class="text">Chart & Statistics</span></a></li> -->
		</ul>
	</nav>
	<!-- /main-nav -->
</div>
</div>

<?=$this->Section('script_js');?>

<?=$this->endSection();?>