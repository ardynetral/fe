<?= $this->extend('smartdepo/landing/template') ?>

<?= $this->section('content') ?>

	<!-- HERO SECTION -->
	<section id="home" class="hero-unit">
		<div class="container">
			<div class="hero-content">
				<h1 class="hero-heading">PT. Contindo Raya</h1>
				<img src="<?php echo base_url();?>/themes/landing/img/forklift.png" class="img-responsive img-hero" alt="KingAdmin">
			</div>
		</div>
	</section>
	<!-- END HERO SECTION -->

	<!-- FEATURES -->
	<section id="features">
		<div class="container">
			<div class="section-heading">
				<span class="preheading">What you need to know</span>
				<h2 class="heading">Our Services</h2>
			</div>
			<div class="container">
				<div class="row feature-item">
					<div class="col-sm-7">
						<h3 class="feature-heading"><i class="icon icon-software-layout-header-sideleft"></i> Service One</h3>
						<p class="lead">Choose your layout and navigation style. KingAdmin provides options to optimize your dashboard content and accessibility.</p>
						<p>Efficiently pursue efficient outsourcing rather than focused internal or "organic" sources. Authoritatively streamline B2C interfaces vis-a-vis one-to-one data. Holisticly simplify 24/365 materials through premium paradigms. Quickly procrastinate quality imperatives for low-risk high-yield e-markets. Collaboratively negotiate user-centric products and clicks-and-mortar.</p>
					</div>
					<div class="col-sm-5">
						<div class="img-box">
							<img src="<?php echo base_url();?>/themes/landing/img/cargo.jpg" class="img-responsive" alt="Feature">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="more-features">
			<div class="container">
				<div class="row">
					<div class="col-sm-4">
						<div class="icon-info">
							<i class="icon icon-basic-webpage-multiple"></i>
							<span>Page Templates</span>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="icon-info">
							<i class="icon icon-basic-smartphone"></i>
							<span>Responsive</span>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="icon-info">
							<i class="icon icon-software-eyedropper"></i>
							<span>Color Skins</span>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="icon-info">
							<i class="icon icon-basic-magnifier"></i>
							<span>Live Widget Search</span>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="icon-info">
							<i class="icon icon-basic-headset"></i>
							<span>Free Support</span>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="icon-info">
							<i class="icon icon-software-layout-header-4boxes"></i>
							<span>Static &amp; DynamicTables</span>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- END FEATURES -->

	<!-- CONTACT -->
	<section id="contact">
		<div class="container">
			<div class="section-heading">
				<span class="preheading">Keep in Touch</span>
				<h2 class="heading">CONTACT</h2>
			</div>
			<div class="row">
				<div class="col-md-4">
					<p>
						<strong><i class="icon icon_pin_alt"></i> ADDRESS</strong>
						<br>
						<span>12345 North Main Street <br> New York 123456</span>
					</p>
					<br>
					<p>
						<strong><i class="icon icon_phone"></i> PHONE</strong>
						<br>
						<span>Phone 1: 1-(558) 968-0400 (Quotation)</span>
						<br>
						<span>Phone 2: 1-(558) 968-1234 (General Inquiries)</span>
					</p>
					<br>
					<p>
						<strong><i class="icon icon_mail"></i> EMAIL</strong>
						<br>
						<span>Email  : <a href="mailto:hello@yourdomain.com">hello@yourdomain.com</a></span>
					</p>
				</div>
				<div class="col-md-8">
					<form id="contact-form" class="form-horizontal form-minimal">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="contact-name" class="control-label sr-only">Name</label>
									<input type="text" class="form-control" id="contact-name" placeholder="Name (required)" required>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="contact-email" class="control-label sr-only">Email</label>
									<input type="email" class="form-control" id="contact-email" placeholder="Email (required)" required>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label for="contact-subject" class="control-label sr-only">Subject</label>
							<div class="col-sm-12">
								<input type="text" class="form-control" id="contact-subject" placeholder="Subject (optional)">
							</div>
						</div>
						<div class="form-group">
							<label for="contact-message" class="control-label sr-only">Message</label>
							<div class="col-sm-12">
								<textarea class="form-control" id="contact-message" name="contact-message" rows="5" cols="30" placeholder="Message (required)" required></textarea>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-12">
								<button type="submit" class="btn btn-primary">Submit Message</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>
	<!-- END CONTACT -->

<?= $this->endSection();?>