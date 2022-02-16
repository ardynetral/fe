<?= $this->extend('smartdepo/dashboard/template') ?>

<?= $this->section('content') ?>

<div class="content">
	<div class="main-header">
		<h2><?= $page_title; ?></h2>
		<em><?= $page_subtitle; ?></em>
	</div>

	<?php if (session()->getFlashdata('sukses')) : ?>
		<div class="alert alert-success alert-dismissable">
			<a href="" class="close">×</a>
			<strong><?= session()->getFlashdata('sukses'); ?></strong>
		</div>
	<?php endif; ?>

	<div class="main-content">

		<div class="widget widget-table">
			<div class="widget-header">
				<h3><i class="fa fa-table"></i> <?= $page_title ?></h3>
			</div>

			<div class="widget-content">
				<?php if (isset($validasi)) : ?>
					<div class="alert alert-danger">
						<?= $validasi; ?>
					</div>
				<?php endif; ?>

				<?php if (isset($message)) : ?>
					<p class="alert alert-danger">
						<?php echo $message; ?>
					</p>
				<?php endif; ?>
				<div id="alert">

				</div>
				<form id="fsearch" class="form-horizontal" role="form">
					<?= csrf_field() ?>
					<?php $readonly = 'readonly'; ?>
					<fieldset>
						<!-- Content here -->
						<div class="rows">
							<label for="crno" class="col-sm-2 control-label text-left">Container No :</label>
							<div class="col-sm-2">
								<input type="text" name="crno" id="crno" class="form-control">
								<i class="err-crno text-danger"></i>
							</div>
							<button type="button" id="printPdf" class="btn btn-primary"><i class="fa fa-check-circle"></i> Print to PDF </button>
							<button type="button" id="history" class="btn btn-primary"><i class="fa fa-check-circle"></i> History</button>
						</div>
					</fieldset>
				</form>
				<!--  CONTAINER DATA -->
				<div class="widget widget-table">
					<div class="widget-header">
						<h3><i class="fa fa-table"></i> CONTAINER DATA</h3>
					</div>
					<div class="widget-content">
						<!-- baris 1 -->
						<div class="form-group row">
							<label for="crno" class="col-sm-1 control-label text-left">Container No </label>
							<div class="col-sm-2">
								<input type="text" readonly name="crno1" class="form-control" id="crno1" value="<?= @$crno1 ?>">
							</div>
							<label for="days" class="col-sm-1 control-label text-left">Material</label>
							<div class="col-sm-2">
								<input type="text" readonly name="mtdesc" class="form-control" id="mtdesc" value="<?= @$mtdesc ?>">
							</div>
							<label for="days" class="col-sm-1 control-label text-left">Condition</label>
							<div class="col-sm-2">
								<input type="text" readonly name="crlastcond" class="form-control" id="crlastcond" value="<?= @$crlastcond ?>">
							</div>
							<label for="days" class="col-sm-1 control-label text-left">Depo</label>
							<div class="col-sm-2">
								<input type="text" readonly name="dpname" class="form-control" id="dpname" value="<?= @$dpname ?>">
							</div>

						</div>
						<!-- baris 2 -->
						<div class="form-group row">
							<label for="cncode" class="col-sm-1 control-label text-left">Type </label>
							<div class="col-sm-2">
								<input type="text" readonly name="ctdesc" class="form-control" id="ctdesc" value="<?= @$ctdesc ?>">
							</div>
							<label for="days" class="col-sm-1 control-label text-left">Manuf.Date</label>
							<div class="col-sm-2">
								<input type="text" readonly name="crmandat" class="form-control" id="crmandat" value="<?= @$crmandat ?>">
							</div>
							<label for="days" class="col-sm-1 control-label text-left">Activity</label>
							<div class="col-sm-2">
								<input type="text" readonly name="crlastact" class="form-control" id="crlastact" value="<?= @$crlastact ?>">
							</div>
							<label for="days" class="col-sm-1 control-label text-left">Sub Depo</label>
							<div class="col-sm-2">
								<input type="text" readonly name="sdname" class="form-control" id="sdname" value="<?= @$sdname ?>">
							</div>
						</div>

						<!-- baris 3 -->
						<div class="form-group row">
							<label for="cncode" class="col-sm-1 control-label text-left">Length </label>
							<div class="col-sm-2">
								<input type="text" readonly name="cclength" class="form-control" id="cclength" value="<?= @$cclength ?>">
							</div>
							<label for="days" class="col-sm-1 control-label text-left">Height</label>
							<div class="col-sm-2">
								<input type="text" readonly name="ccheight" class="form-control" id="ccheight" value="<?= @$ccheight ?>">
							</div>
							<label for="days" class="col-sm-1 control-label text-left">&nbsp;</label>
							<div class="col-sm-2">&nbsp;
								&nbsp;
							</div>
							<label for="days" class="col-sm-1 control-label text-left">&nbsp;</label>
							<div class="col-sm-2">&nbsp;

							</div>
						</div>


					</div>

				</div>
				<!--   History CONTAINER-->
				<div class="widget widget-table">
					<div class="widget-header">
						<h3><i class="fa fa-table"></i> History CONTAINER</h3>
					</div>
					<div class="widget-content">
						<fieldset>
							<!-- baris 1 -->
							<div class="form-group row">
								<label for="cncode" class="col-sm-1 control-label text-left">Operator </label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpopr" class="form-control" id="cpopr" value="<?= @$cpopr ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Customer</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpcust" class="form-control" id="cpcust" value="<?= @$cpcust ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Operator</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpopr1" class="form-control" id="cpopr1" value="<?= @$cpopr1 ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Customer</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpcust1" class="form-control" id="cpcust1" value="<?= @$cpcust1 ?>">
								</div>
							</div>
							<!-- baris 2 -->
							<div class="form-group row">
								<label for="cncode" class="col-sm-1 control-label text-left">Vessel</label>
								<div class="col-sm-2">
									<input type="text" readonly name="vesin" class="form-control" id="vesin" value="<?= @$vesin ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Voyage</label>
								<div class="col-sm-2">
									<input type="text" readonly name="voyin" class="form-control" id="voyin" value="<?= @$voyin ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Vessel</label>
								<div class="col-sm-2">
									<input type="text" readonly name="vesout" class="form-control" id="vesout" value="<?= @$vesout ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Voyage</label>
								<div class="col-sm-2">
									<input type="text" readonly name="voyout" class="form-control" id="voyout" value="<?= @$voyout ?>">
								</div>
							</div>
							<!-- baris 3 -->
							<div class="form-group row">
								<label for="cncode" class="col-sm-1 control-label text-left">Discharge</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpidisdat" class="form-control" id="cpidisdat" value="<?= @$cpidisdat ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Full/Empty</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpife" class="form-control" id="cpife" value="<?= @$cpife ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Loading</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpoloaddat" class="form-control" id="cpoloaddat" value="<?= @$cpoloaddat ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Full/Empty</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpofe" class="form-control" id="cpofe" value="<?= @$cpofe ?>">
								</div>
							</div>
							<!-- baris 4 -->
							<div class="form-group row">
								<label for="cncode" class="col-sm-1 control-label text-left">From</label>
								<div class="col-sm-2">
									<input type="text" readonly name="retfrom" class="form-control" id="retfrom" value="<?= @$retfrom ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Term</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpiterm" class="form-control" id="cpiterm" value="<?= @$cpiterm ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Destination</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpoload" class="form-control" id="cpoload" value="<?= @$cpoload ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Term</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpoterm" class="form-control" id="cpoterm" value="<?= @$cpoterm ?>">
								</div>
							</div>
							<!-- baris 5 -->
							<div class="form-group row">
								<label for="cncode" class="col-sm-1 control-label text-left">Date In</label>
								<div class="col-sm-1">
									<input type="text" readonly name="cpitgl" class="form-control" id="cpitgl" value="<?= @$cpitgl ?>">
								</div>
								<div class="col-sm-1">
									<input type="text" readonly name="cpijam" class="form-control" id="cpijam" value="<?= @$cpitgl ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Condition</label>
								<div class="col-sm-2">
									<input type="text" readonly name="scond_in" class="form-control" id="scond_in" value="<?= @$scond_in ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Date Out</label>
								<div class="col-sm-1">

									<input type="text" readonly name="cpotgl" class="form-control" id="cpotgl" value="<?= @$cpotgl ?>">
								</div>
								<div class="col-sm-1">
									<input type="text" readonly name="cpijam" class="form-control" id="cpojam" value="<?= @$cpojam ?>">

								</div>
								<label for="days" class="col-sm-1 control-label text-left">Condition</label>
								<div class="col-sm-2">
									<input type="text" readonly name="scond_out" class="form-control" id="scond_out" value="<?= @$scond_out ?>">

								</div>
							</div>
							<!-- baris 6 -->
							<div class="form-group row">
								<label for="cncode" class="col-sm-1 control-label text-left">EIR In</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpieir" class="form-control" id="cpieir" value="<?= @$cpieir ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Receipt No</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpireceptno" class="form-control" id="cpireceptno" value="<?= @$cpireceptno ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">EIR Out</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpoeir" class="form-control" id="cpoeir" value="<?= @$cpoeir ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Receipt No</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cporeceptno" class="form-control" id="cporeceptno" value="<?= @$cporeceptno ?>">
								</div>
							</div>
							<!-- baris 7 -->
							<div class="form-group row">
								<label for="cncode" class="col-sm-1 control-label text-left">Inspector</label>
								<div class="col-sm-2">
									<input type="text" readonly name="s_in" class="form-control" id="s_in" value="<?= @$s_in ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Date</label>
								<div class="col-sm-2">
									<input type="text" readonly name="sdate_in" class="form-control" id="sdate_in" value="<?= @$sdate_in ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Inspector</label>
								<div class="col-sm-2">
									<input type="text" readonly name="s_out" class="form-control" id="s_out" value="<?= @$s_out ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Date</label>
								<div class="col-sm-2">
									<input type="text" readonly name="sdate_out" class="form-control" id="sdate_out" value="<?= @$sdate_out ?>">
								</div>
							</div>
							<!-- baris 8 -->
							<div class="form-group row">
								<label for="cncode" class="col-sm-1 control-label text-left">Deliverer</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpideliver" class="form-control" id="cpideliver" value="<?= @$cpideliver ?>">
								</div>
								<label for="cncode" class="col-sm-1 control-label text-left">Order In No</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpiorderno" class="form-control" id="cpiorderno" value="<?= @$cpiorderno ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Receiver</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cporeceiv" class="form-control" id="cporeceiv" value="<?= @$cporeceiv ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Order Out No</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpoorderno" class="form-control" id="cpoorderno" value="<?= @$cpoorderno ?>">
								</div>
							</div>
							<!-- baris 9 -->
							<div class="form-group row">
								<label for="cncode" class="col-sm-1 control-label text-left">Trucker</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpitruck" class="form-control" id="cpitruck" value="<?= @$cpitruck ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Vehicle Id</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpinopol" class="form-control" id="cpinopol" value="<?= @$cpinopol ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Trucker</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpotruck" class="form-control" id="cpotruck" value="<?= @$cpotruck ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Vehicle Id</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cponopol" class="form-control" id="cponopol" value="<?= @$cponopol ?>">
								</div>
							</div>
							<!-- baris 10 -->
							<div class="form-group row">

								<label for="days" class="col-sm-1 control-label text-left">Vehicle Id</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpinopol" class="form-control" id="cpinopol" value="<?= @$cpinopol ?>">
								</div>
								<label for="cncode" class="col-sm-1 control-label text-left">Driver</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpidriver" class="form-control" id="cpidriver" value="<?= @$cpidriver ?>">
								</div>

								<label for="days" class="col-sm-1 control-label text-left">Vehicle Id</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cponopol" class="form-control" id="cponopol" value="<?= @$cponopol ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">DriverDriver</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpodriver" class="form-control" id="cpodriver" value="<?= @$cpodriver ?>">
								</div>
							</div>
							<!-- baris 11 -->
							<div class="form-group row">
								<label for="cncode" class="col-sm-1 control-label text-left">Remarks</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpiremark" class="form-control" id="cpiremark" value="<?= @$cpiremark ?>">
								</div>
								<label for="cncode" class="col-sm-1 control-label text-left">Ref In</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpirefin" class="form-control" id="cpirefin" value="<?= @$cpirefin ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Remarks</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cporemark" class="form-control" id="cporemark" value="<?= @$cporemark ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Ref Out</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cporefout" class="form-control" id="cporefout" value="<?= @$cporefout ?>">
								</div>
							</div>
							<!-- baris 12 -->
							<div class="form-group row">
								<label for="cncode" class="col-sm-1 control-label text-left">Cargo</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpicargo" class="form-control" id="cpicargo" value="<?= @$cpicargo ?>">
								</div>
								<label for="cncode" class="col-sm-1 control-label text-left">Seal No</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpiseal" class="form-control" id="cpiseal" value="<?= @$cpiseal ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Cargo</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cpocargo" class="form-control" id="cpocargo" value="<?= @$cpocargo ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Seal No</label>
								<div class="col-sm-2">
									<input type="text" readonly name="cposeal" class="form-control" id="cposeal" value="<?= @$cposeal ?>">
								</div>
							</div>
						</fieldset>
					</div>

				</div>
				<!--   REPAIR CONTAINER-->
				<div class="widget widget-table">
					<div class="widget-header">
						<h3><i class="fa fa-table"></i> REPAIR</h3>
					</div>
					<div class="widget-content">
						<fieldset>
							<!-- baris 1 -->
							<div class="form-group row">
								<label for="cncode" class="col-sm-1 control-label text-left">Estimate Date</label>
								<div class="col-sm-2">
									<input type="text" readonly name="rptglest" class="form-control" id="rptglest" value="<?= @$rptglest ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Approved</label>
								<div class="col-sm-2">
									<input type="text" readonly name="rptglappvpr" class="form-control" id="rptglappvpr" value="<?= @$rptglappvpr ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">WO Date</label>
								<div class="col-sm-2">
									<input type="text" readonly name="rpworkdat" class="form-control" id="rpworkdat" value="<?= @$rpworkdat ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">&nbsp;</label>
								<div class="col-sm-2">&nbsp;
								</div>
							</div>
							<!-- baris 2 -->
							<div class="form-group row">
								<label for="cncode" class="col-sm-1 control-label text-left">In Workshop</label>
								<div class="col-sm-2">
									<input type="text" readonly name="rpmridat" class="form-control" id="rpmridat" value="<?= @$rpmridat ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Repair</label>
								<div class="col-sm-2">
									<input type="text" readonly name="rpdrepair" class="form-control" id="rpdrepair" value="<?= @$rpdrepair ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">Complete Repair</label>
								<div class="col-sm-2">
									<input type="text" readonly name="rptglwroke" class="form-control" id="rptglwroke" value="<?= @$rptglwroke ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">&nbsp;</label>
								<div class="col-sm-2">&nbsp;
								</div>
							</div>
							<!-- baris 2 -->
							<div class="form-group row">
								<label for="cncode" class="col-sm-1 control-label text-left">Out Workshop</label>
								<div class="col-sm-2">
									<input type="text" readonly name="rpmrodat" class="form-control" id="rpmrodat" value="<?= @$rpmrodat ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">&nbsp;</label>
								<div class="col-sm-2">&nbsp;</div>
								<label for="days" class="col-sm-1 control-label text-left">Inspector</label>
								<div class="col-sm-2">
									<input type="text" readonly name="rpinspoke" class="form-control" id="rpinspoke" value="<?= @$rpinspoke ?>">
								</div>
								<label for="days" class="col-sm-1 control-label text-left">&nbsp;</label>
								<div class="col-sm-2">&nbsp;
								</div>
							</div>

						</fieldset>
					</div>

				</div>

			</div>
		</div>

	</div>
</div>

<?= $this->endSection(); ?>

<!-- JS -->
<?= $this->Section('script_js'); ?>

<?= $this->include('\Modules\ContainerSearch\Views\js'); ?>

<?= $this->endSection(); ?>