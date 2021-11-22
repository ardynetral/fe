<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Repo Pra In</h2>
		<em>Repo Pra-In page</em>
	</div>

	<?php if(session()->getFlashdata('sukses')):?>
	<div class="alert alert-success alert-dismissable">
		<a href="" class="close">×</a>
		<strong><?=session()->getFlashdata('sukses');?></strong>
	</div>
	<?php endif; ?>

	<div class="main-content">

		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Repo Pra In</h3>
			</div>

			<div class="widget-content">
				<div class="row">
					<div class="col-md-12">
						<a href="#OrderPra"class="btn btn-primary"><i class="fa fa-plus-square"></i>&nbsp;Add New</a>
					</div>
				</div><br>	

				<?= $this->include('\Modules\RepoIn\Views\list_order_pra');?>			

			</div>		
		</div>		
		
		<div class="widget widget-table" id="OrderPra">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Form Order Repo Pra In</h3>
			</div>
			<div class="widget-content">
				<?= $this->include('\Modules\RepoIn\Views\form_header');?>	
			</div>
		</div>	

		<div class="row">
			<div class="col-sm-4">
				<div class="widget widget-table">
					<div class="widget-header">
						<h3><i class="fa fa-table"></i> Form Input Container</h3>
					</div>
					<div class="widget-content">
						<?= $this->include('\Modules\RepoIn\Views\form_detail_header');?>
					</div>
				</div>						
			</div>
			<div class="col-sm-8">
				<div class="widget widget-table">
					<div class="widget-header">
						<h3><i class="fa fa-table"></i> List Order Repo Container</h3>
					</div>
					<div class="widget-content">
						<?= $this->include('\Modules\RepoIn\Views\list_order_pracontainer');?>
					</div>
				</div>
			</div>
		</div>		

	</div>
</div>

<?= $this->endSection();?>

<!-- JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\RepoIn\Views\js'); ?>	
	
<?= $this->endSection();?>