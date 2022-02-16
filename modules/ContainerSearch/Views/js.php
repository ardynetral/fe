<script type="text/javascript">
	var selectedDate;
	$(document).ready(function() {


		$("#printPdf").on("click", function(e) {
			var crno1 = $("#crno").val();
			//alert(crno);
			$.ajax({
				url: "<?= site_url('containersearch/reportPdf'); ?>",
				type: "POST",
				data: {
					"crno": crno1
				},
				dataType: "JSON",
				success: function(json) {
					console.log(json);
				}
			});
		});

		$("#history").on("click", function(e) {
			window.open("<?php echo site_url('containersearch/history'); ?>");
			e.preventDefault();
		});

		$("#crno").on("keyup", function() {
			var crno1 = $("#crno").val();
			//alert(crno);
			$.ajax({
				url: "<?= site_url('containersearch/getContainer'); ?>",
				type: "POST",
				data: {
					"crno": crno1
				},
				dataType: "JSON",
				success: function(json) {
					console.log(json);
					if (json.status == "Failled") {
						$(".err-crno").show();
						$(".err-crno").html(json.message);
						$("#crno").css("background", "#ffbfbf!important");
						$("#crno").css("border-color", "#ea2525");
					} else {
						$(".err-crno").html("");
						$(".err-crno").hide();

						$("#mtdesc").val(json.mtdesc);
						$("#crlastcond").val(json.crlastcond);
						$("#crno1").val(json.crno1);
						$("#dpname").val(json.dpname);
						$("#ctdesc").val(json.ctdesc);
						$("#crmandat").val(json.crmandat);
						$("#crlastact").val(json.crlastact);
						$("#sdname").val(json.sdname);
						$("#cclength").val(json.cclength);
						$("#ccheight").val(json.ccheight);
						$("#crno1").val(json.crno);
						$("#cpopr").val(json.cpopr);
						$("#cpcust").val(json.cpcust);

						$("#cpcust1").val(json.cpcust1);
						$("#vesin").val(json.vesin);
						$("#voyin").val(json.voyin);
						$("#vesout").val(json.vesout);
						$("#voyout").val(json.voyout);
						$("#cpidisdat").val(json.cpidisdat);
						$("#cpife").val(json.cpife);
						$("#cpoloaddat").val(json.cpoloaddat);
						$("#cpofe").val(json.cpofe);
						$("#retfrom").val(json.retfrom);
						$("#cpiterm").val(json.cpiterm);
						$("#cpoload").val(json.cpoload);
						$("#cpoterm").val(json.cpoterm);
						$("#cpitgl").val(json.cpitgl);
						$("#cpijam").val(json.cpijam);
						$("#cpotgl").val(json.cpotgl);
						$("#cpojam").val(json.cpojam);
						$("#cpieir").val(json.cpieir);
						$("#cpireceptno").val(json.cpireceptno);
						$("#cpoeir").val(json.cpoeir);
						$("#cporeceptno").val(json.cporeceptno);
						$("#cpideliver").val(json.cpideliver);
						$("#cporeceiv").val(json.cporeceiv);
						$("#cpitruck").val(json.cpitruck);
						$("#cpinopol").val(json.cpinopol);
						$("#cpotruck").val(json.cpotruck);
						$("#cponopol").val(json.cponopol);
						$("#cpiremark").val(json.cpiremark);
						$("#cpidriver").val(json.cpidriver);
						$("#cporemark").val(json.cporemark);
						$("#cpodriver").val(json.cpodriver);
						$("#cpirefin").val(json.cpirefin);
						$("#cporefout").val(json.cporefout);
						$("#cpicargo").val(json.cpicargo);
						$("#cpocargo").val(json.cpocargo);
						$("#cpiseal").val(json.cpiseal);
						$("#cposeal").val(json.cposeal);
						$("#cpiorderno").val(json.cpiorderno);
						$("#cpoorderno").val(json.cpoorderno);
						$("#rptglest").val(json.rptglest);
						$("#rptglappvpr").val(json.rptglappvpr);
						$("#rpworkdat").val(json.rpworkdat);
						$("#rpmridat").val(json.rpmridat);
						$("#rpdrepair").val(json.rpdrepair);
						$("#rptglwroke").val(json.rptglwroke);
						$("#rpmrodat").val(json.rpmrodat);
						$("#rpinspoke").val(json.rpinspoke);
						//$("#scond_in").val(json.scond_in);
						//$("#scond_out").val(json.scond_out);
						//$("#s_in").val(json.s_in);
						//$("#sdate_in").val(json.sdate_in);
						//$("#s_out").val(json.s_out);
						//$("#sdate_out").val(json.sdate_out);
					}

				}
			});

		});
	});
</script>