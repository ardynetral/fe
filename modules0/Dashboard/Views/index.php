<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="main-header text-center">
	<h2>SMART<b>DEPO</b></h2>
	<small class="text-danger"><b>PT.CONTINDO RAYA</b></small>
</div>

<!-- FLASHDATA -->

<?php if(session()->getFlashdata('sukses')):?>
<div class="alert alert-success alert-dismissable">
	<a href="" class="close">×</a>
	<strong><?=session()->getFlashdata('sukses');?></strong>
</div>
<?php endif; ?>


<?= $this->endSection();?>