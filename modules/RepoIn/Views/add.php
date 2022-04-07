<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>


<div class="content">
	<div class="main-header">
		<h2>Repo Pra In</h2>
		<em>Order Repo Pra in</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table" id="OrderPra">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Form Order Repo</h3>
			</div>
			<div class="widget-content">
				<?= $this->include('\Modules\RepoIn\Views\form_header');?>	
			</div>
		</div>	

		<div class="row">
			<div class="col-sm-12">
				<div class="widget widget-table repo-detail-table" style="display:none;">
					<div class="widget-header">
						<h3><i class="fa fa-table"></i> Repo Container</h3>
					</div>
					<div class="widget-content">
					<?= $this->include('\Modules\RepoIn\Views\list_order_pracontainer');?>
					<?= $this->include('\Modules\RepoIn\Views\form_detail_header');?>						
					</div>
					<div class="widget-footer text-center">
						<a href="#" class="btn btn-danger" id="updateNewData"><i class="fa fa-save"></i> Save All</a>
					</div>
				</div>
			</div>
		</div>		

	</div>
</div>


<?= $this->endSection();?>

<!-- Load JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\RepoIn\Views\js'); ?>

<?= $this->endSection();?>