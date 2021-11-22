<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Container </h2>
		<em>container page</em>
	</div>
	<div class="main-content">

		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> Container Detail</h3></div>
			<div class="widget-content">	

				<?php if($container ==''): ?>
					<p class="alert alert-warning"> Data not found.</p>
				<?php else : ?>

				<div class="row">
					<div class="col-md-12">
						<table class="table">
							<tbody>						
								<tr><th width="200" class="text-right">Container No</th><td width="2">:</td><td><?=$container['crno'];?></td></tr>
								<tr><th width="200" class="text-right">ID Code</th><td width="2">:</td><td><?=$container['cccode'];?></td></tr>
								<tr><th width="200" class="text-right">Owner</th><td width="2">:</td><td><?=$container['crowner'];?></td></tr>
								<tr><th width="200" class="text-right">CDP</th><td width="2">:</td><td><?=$container['crcdp'];?></td></tr>
								<tr><th width="200" class="text-right">CSC</th><td width="2">:</td><td><?=$container['crcsc'];?></td></tr>
								<tr><th width="200" class="text-right">ACEP</th><td width="2">:</td><td><?=$container['cracep'];?></td></tr>
								<tr><th width="200" class="text-right">MMYY</th><td width="2">:</td><td><?=$container['crmmyy'];?></td></tr>
								<tr><th width="200" class="text-right">Length/Height (feet)</th><td width="2">:</td>
									<td><?=$container['container_code']['cclength'].'/'.$container['container_code']['ccheight'];?></td></tr>
								<tr><th width="200" class="text-right">Gross Weight</th><td width="2">:</td>
									<td><?=$container['crweightk'] . 'Kgs / ' .  $container['crweightl'] . 'Lbs';?></td></tr>
								<tr><th width="200" class="text-right">Tarre</th><td width="2">:</td>
									<td><?=$container['crtarak'] . 'Kgs / ' .  $container['crtaral'] . 'Lbs';?></td></tr>
								<tr><th width="200" class="text-right">Netto</th><td width="2">:</td>
									<td><?=$container['crnetk'] . 'Kgs / ' .  $container['crnetl'] . 'Lbs';?></td></tr>
								<tr><th width="200" class="text-right">Volume</th><td width="2">:</td><td><?=$container['crvol'] . 'Cbm';?></td></tr>
								<tr><th width="200" class="text-right">Material Code</th><td width="2">:</td><td><?=$container['crno'];?></td></tr>
								<tr><th width="200" class="text-right">Manufacture</th><td width="2">:</td><td><?=$container['crmanuf'];?></td></tr>
								<tr><th width="200" class="text-right">Manufacture Date</th><td width="2">:</td><td><?=$container['crmandat'];?></td></tr>
							</tbody>
						</table>

					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<a href="<?=site_url('container');?>" class="btn btn-default">Back</a>
						<a href="<?=site_url('container/edit/'.$container['crno']);?>" class="btn btn-success">Edit</a>
					</div>
				</div>

				<?php endif; ?>

			</div>		
		</div>		
		
	</div>
</div>

<?= $this->endSection();?>

<!-- JS -->
<?= $this->Section('script_js');?>

	<?= $this->include('\Modules\Container\Views\js'); ?>	

<?= $this->endSection();?>