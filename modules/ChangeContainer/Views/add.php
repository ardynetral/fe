<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Pra Out</h2>
		<em>Order Pra-Out page</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table" id="OrderPra">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Form Order Pra Out</h3>
			</div>
			<div class="widget-content">
				<?= $this->include('\Modules\PraOut\Views\form_header');?>	
			</div>
		</div>	

		<div class="row">
			<div class="col-sm-12">
				<div class="widget widget-table pra-container" style="display:none;">
					<div class="widget-header">
						<h3><i class="fa fa-table"></i> List Order Pra Container</h3>
					</div>
					<div class="widget-content">
						<?= $this->include('\Modules\PraOut\Views\list_order_pracontainer');?>
						<?= $this->include('\Modules\PraOut\Views\form_detail_header');?>
					</div>
				</div>
			</div>
		</div>			
		
	</div>
</div>

<?= $this->endSection();?>

<!-- JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\PraOut\Views\js'); ?>	
	
<?= $this->endSection();?>