<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Pra out</h2>
		<em>Pra-Out page</em>
	</div>

	<?php if(session()->getFlashdata('sukses')):?>
	<div class="alert alert-success alert-dismissable">
		<a href="" class="close">×</a>
		<strong><?=session()->getFlashdata('sukses');?></strong>
	</div>
	<?php endif; ?>

	<div class="main-content">

		<ul class="nav nav-tabs" role="tablist" id="tabList">
			<li class="nav-item active"><a href="#tab1" role="tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-table"></i> List Order</a></li>
			<li class="nav-item "><a href="#tab2" role="tab" data-toggle="tab" aria-expanded="false"><i class="fa fa-plus"></i> Input Order</a></li>
			<li class="nav-item" id="navItem3" disabled><a href="#tab3" role="tab" id="navLink3" data-toggle="" aria-expanded="false"><i class="fa fa-plus"></i> Input Container</a></li>
		</ul>

		<div class="tab-content">
			<div class="tab-pane fade active in" id="tab1">
				<div class="widget widget-table">
					<div class="widget-header">
						<h3><i class="fa fa-table"></i> List Order Pra</h3>
					</div>
					<div class="widget-content">
						<?= $this->include('\Modules\PraOut\Views\list_order_pra');?>
					</div>
				</div>				
			</div>
			<div class="tab-pane fade" id="tab2">
				<div class="widget widget-table">
					<div class="widget-header">
						<h3><i class="fa fa-table"></i> Form Order PraOut</h3>
					</div>
					<div class="widget-content">
						<?= $this->include('\Modules\PraOut\Views\form_header');?>
					</div>
				</div>				
			</div>
			<div class="tab-pane fade" id="tab3">
				<div class="row">
					<div class="col-sm-8">
						<div class="widget widget-table">
							<div class="widget-header">
								<h3><i class="fa fa-table"></i> List Order Pra Container</h3>
							</div>
							<div class="widget-content">
								<?= $this->include('\Modules\PraOut\Views\list_order_pracontainer');?>
							</div>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="widget widget-table">
							<div class="widget-header">
								<h3><i class="fa fa-table"></i> Form Input Container</h3>
							</div>
							<div class="widget-content">
								<?= $this->include('\Modules\PraOut\Views\form_detail_header');?>
							</div>
						</div>						
					</div>
				</div>				
			</div>
		</div>

	</div>
</div>

<?= $this->endSection();?>

<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\PraOut\Views\js'); ?>

<?= $this->endSection();?>