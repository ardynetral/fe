<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2><?=$page_title;?></h2>
		<em><?=$page_subtitle;?></em>
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
				<h3><i class="fa fa-table"></i> <?=$page_title?></h3>
			</div>

			<div class="widget-content">
				<div class="row">
					<div class="col-md-12">
						<?php if(has_insert==true): ?>
						<a href="#" class="btn btn-primary" id=""><i class="fa fa-plus-square"></i>&nbsp;Add New</a>
						<?php endif;?>
					</div>
				</div><br>	

				<?= $this->include('\Modules\PraOut\Views\index_data');?>

			</div>		
		</div>		
		
	</div>
</div>

<?= $this->endSection();?>

<!-- JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\PraOut\Views\js'); ?>	
	
<?= $this->endSection();?>