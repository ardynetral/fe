<nav id="main-navbar" class="navbar navbar-default navbar-fixed-top">
	<div class="container">
		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav-collapse">
			<span class="sr-only">Toggle Navigation</span>
			<span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>
		</button>
		<a href="index.php" class="navbar-brand">
			<span class="brand-text">PT.CONTINDO RAYA</span>
		</a>
		<div id="main-nav-collapse" class="collapse navbar-collapse">
			<ul class="nav navbar-nav nav-onepage">
				<li class="active"><a href="<?=site_url();?>">HOME</a></li>
				<?php if(!empty(session()->get('username'))):?>
				<li class=""><a href="<?=site_url('dashboard');?>">Dashboard</a></li>
				<li class=""><a href="<?=site_url('logout');?>">Logout</a></li>
				<?php else:?>
				<li><a href="<?=site_url('login');?>">LOGIN</a></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</nav>