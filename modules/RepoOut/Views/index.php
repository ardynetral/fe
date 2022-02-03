<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Repo Pra Out</h2>
		<em>Repo Pra-Out page</em>
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
				<h3><i class="fa fa-table"></i> Repo Pra Out</h3>
			</div>

			<div class="widget-content">
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('repoout/add')?>"class="btn btn-primary"><i class="fa fa-plus-square"></i>&nbsp;Add New</a>
					</div>
				</div><br>	

				<?= $this->include('\Modules\RepoOut\Views\list_order_pra');?>			

			</div>		
		</div>		

	</div>
</div>

<?= $this->endSection();?>

<!-- JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\RepoOut\Views\js'); ?>	
	
<?= $this->endSection();?>