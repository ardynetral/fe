<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2>Daily Movement In</h2>
		<em>Daily Movement In page</em>
	</div>

	<?php if (session()->getFlashdata('sukses')) : ?>
		<div class="alert alert-success alert-dismissable">
			<a href="" class="close">×</a>
			<strong><?= session()->getFlashdata('sukses'); ?></strong>
		</div>
	<?php endif; ?>

</div>

<?= $this->endSection(); ?>

<!-- JS -->
<?= $this->Section('script_js'); ?>

<?= $this->include('\Modules\Edi1\Views\js'); ?>

<?= $this->endSection(); ?>