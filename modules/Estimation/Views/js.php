<script type="text/javascript">
	$(document).ready(function() {
		$('.select-lccode').select2();
		$('.select-cmcode').select2();
		$('.select-dycode').select2();
		$('.select-rmcode').select2();
    
		// Error Message element
		$(".err-crno").hide(); //container number check
		// $("#update").hide();
		// $("#updateDetail").hide();
		// $("#editOrderFrame").hide();
		// SELECT2
		// $('.select-pr').select2();
		// $('.select-port').select2();
		// $('.select-depo').select2();
		// $('.select-vessel').select2();
		// $('.select-voyage').select2();
		// $('.select-ccode').select2();
		// $('.select-lccode').select2();
		// $('.select-cmcode').select2();
		// $('.select-dycode').select2();
		// $('.select-rmcode').select2();
		// DATATABLE
		$("#tblList_add").DataTable({
			"paging": false,
			"info": false,
		});
		// datePicker
		$(".tanggal").datepicker({
			autoclose: true,
		});

		$("form#fEstimasi").on("submit", function(e) {
			e.preventDefault();

			$.ajax({
				url: "<?php echo site_url('estimation/add'); ?>",
				type: "POST",
				data: new FormData(this),
	            processData: false,
	            contentType: false,
	            cache: false,					
				dataType: 'json',
				success: function(json) {
					if (json.status == "success") {
						Swal.fire({
							icon: 'success',
							title: "Success",
							html: '<div class="text-success">' + json.message + '</div>'
						});
						$("#saveData").prop('disabled', true);
						$("#saveDetail").prop('disabled', false);
					} else {
						Swal.fire({
							icon: 'warning',
							title: "Alert",
							html: '<div class="text-danger">' + json.message + '</div>'
						});
					}
				}
			});
		});




		// EDIT DATA ORDER
		function enableFormOrder() {
			$("#prcode").select2('enable');
			$("#cpidish").select2('enable');
			$("#cpidisdat").attr('readonly', false);
			$("#liftoffcharge").attr('readonly', false);
			$("#cpdepo").select2('enable');
			// $("#cpipratgl").attr('readonly', false);
			$("#cpirefin").attr('readonly', false);
			// $("#cpijam").attr('readonly', false);
			$("#cpives").select2('enable');
			$("#cpivoyid").attr('readonly', false);
			$("#cpicargo").attr('readonly', false);
			$("#cpideliver").attr('readonly', false);
		}

		function disableFormOrder() {
			$("#save")
			$("#prcode").select2('disable');
			$("#cpidish").select2('disable');
			$("#cpidisdat").attr('readonly', true);
			$("#liftoffcharge").attr('readonly', true);
			$("#cpdepo").select2('disable');
			// $("#cpipratgl").attr('readonly', true);
			$("#cpirefin").attr('readonly', true);
			$("#cpijam").attr('readonly', true);
			$("#cpives").select2('disable');
			$("#cpivoyid").attr('readonly', true);
			$("#cpicargo").attr('readonly', true);
			$("#cpideliver").attr('readonly', true);
		}
		$("#editOrder").on('click', function(e) {
			enableFormOrder();
			$("#update").show();
			$("#cancel").show();
		});

		$("#update").click(function(e) {
			e.preventDefault();
			var formData = "cpiorderno=" + $("#cpiorderno").val();
			formData += "&praid=" + $("#praid").val();
			formData += "&cpiopr=" + $("#prcode").val();
			formData += "&cpicust=" + $("#cucode").val();
			formData += "&cpidish=" + $("#cpidish").val();
			formData += "&cpidisdat=" + $("#cpidisdat").val();
			formData += "&liftoffcharge=" + $("#liftoffcharge").val();
			formData += "&cpdepo=" + $("#cpdepo").val();
			formData += "&cpipratgl=" + $("#cpipratgl").val();
			formData += "&cpirefin=" + $("#cpirefin").val();
			formData += "&cpijam=" + $("#cpijam").val();
			formData += "&cpives=" + $("#cpives").val();
			formData += "&cpivoyid=" + $("#cpivoyid").val();
			formData += "&cpicargo=" + $("#cpicargo").val();
			formData += "&cpideliver=" + $("#cpideliver").val();
			// console.log(formData);
			$.ajax({
				url: "<?php echo site_url('estimation/edit/'); ?>" + $("#praid").val(),
				type: "POST",
				data: formData,
				dataType: 'json',
				success: function(json) {
					console.log(json.message);
					if (json.message == "success") {
						Swal.fire({
							icon: 'success',
							title: "Success",
							html: '<div class="text-success">' + json.msgdata + '</div>'
						});
					} else {
						Swal.fire({
							icon: 'error',
							title: "Error",
							html: '<div class="text-danger">' + json.message + '</div>'
						});
					}
				}
			});
		});

		function list_container(item, index, arr) {
			var num = index + 1;
			var cpishold = "";
			var cpife = "";
			if (item.cpife == 1) {
				cpife = "Full";
			} else {
				cpife = "Empty";
			}

			if (item.cpishold == 1) {
				cpishold = "Hold";
			} else {
				cpishold = "Release";
			}

			arr[index] = "<tr>" +
				"<td>" + num + "</td>" +
				"<td>" + item.crno + "</td>" +
				"<td>" + item.cccode + "</td>" +
				"<td>" + item.ctcode + "</td>" +
				"<td>" + item.cclength + "</td>" +
				"<td>" + item.ccheight + "</td>" +
				"<td>" + cpife + "</td>" +
				"<td>" + cpishold + "</td>" +
				"<td>" + item.cpiremark + "</td>" +
				"<td></td>" +
				"<td><a href='#' id='editContainer' class='btn btn-xs btn-primary edit' data-crid='" + item.pracrnoid + "'>edit</a><a href='#' id='deleteContainer' class='btn btn-xs btn-danger'>delete</a></td>" +
				"</tr>";
		}

		$('#tblList_add tbody').on('click', '.delete', function(e) {
			e.preventDefault();
			var svid = $(this).data('svid');
			var rpid = $(this).data('rpid');
			var rpver = $(this).data('rpver');
			Swal.fire({
				title: 'Are you sure?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
				if (result.isConfirmed) {
					delete_data(svid,rpid,rpver);
				}
			});

		});

		function delete_data(svid,rpid,rpver) {
			$.ajax({
				url: "<?php echo site_url('estimation/delete_detail'); ?>",
				type: "POST",
				data: { "svid":svid, "rpid":rpid, "rpver":rpver },
				dataType: 'json',
				success: function(json) {
					if (json.message == "success") {
						Swal.fire({
							icon: 'success',
							title: "Success",
							html: '<div class="text-success">' + json.message + '</div>'
						});
						window.location.href = "<?php echo site_url('city'); ?>";
					} else {
						Swal.fire({
							icon: 'error',
							title: "Error",
							html: '<div class="text-danger">' + json.message + '</div>'
						});
					}
				}
			});
		}

		$("#addDetail").on("click", function(e){
			var det_crno = $("#det_crno").val();
			$("#saveDetail").prop('disabled', false);
			$("#formDetail").trigger("reset");
			$("#det_crno").val(crno);
			$("#lccode").select2().select2('val','');
			$("#cmcode").select2().select2('val','');
			$("#dycode").select2().select2('val','');
			$("#rmcode").select2().select2('val','');			
		});

		// save detailContainer
		$("#formDetail").on("submit", function(e) {
			e.preventDefault();
			$("#tblList_add tbody").html("");
			$.ajax({
				url: "<?php echo site_url('estimation/save_detail'); ?>",
				type: "POST",
				data: new FormData(this),
	            processData: false,
	            contentType: false,
	            cache: false,		
				dataType: 'json',
				success: function(json) {
					if (json.status == "success") {
						Swal.fire({
							icon: 'success',
							title: "Success",
							html: '<div class="text-success">' + json.message + '</div>'
						});
						$("#tblList_add tbody").html(json.data);
					} else {
						Swal.fire({
							icon: 'error',
							title: "Error",
							html: '<div class="text-danger">' + json.message + '</div>'
						});
					}
				}
			});

		});

		$('#detTable tbody').on('click', '.edit', function(e) {
			e.preventDefault();
			var crid = $(this).data("crid");
			var cpife = $('input:radio[name=cpife]');
			$.ajax({
				url: "<?= site_url('estimation/get_one_container/'); ?>" + crid,
				type: "POST",
				data: "crid=" + crid,
				dataType: "JSON",
				success: function(json) {
					if (json.message == "success") {
						$("#pracrnoid").val(json.cr.pracrnoid);
						$("#crno").val(json.cr.crno);
						$("#ccode").select2().select2('val', json.cr.cccode);
						$("#ctcode").val(json.cr.ctcode);
						$("#cclength").val(json.cr.cclength);
						$("#ccheight").val(json.cr.ccheight);

						$("#saveDetail").hide();
						$("#updateDetail").show();

						if (json.cr.cpife == "1") {
							cpife.filter('[value=1]').prop('checked', true);
						} else if (json.cr.cpife == "0") {
							cpife.filter('[value=0]').prop('checked', true);
						}

						if (json.cr.cpishold == 1) {
							$("#cpishold").prop('checked', true);
							$("#cpishold").val(json.cr.cpishold);
						}
						$("#cpiremark").val(json.cr.cpiremark);

					}
				}
			})
		});

		$("#updateDetail").on("click", function(e) {
			e.preventDefault();
			var cpife = $("input:radio[name=cpife]:checked").val();
			var formData = "praid=" + $("#praid").val();
			formData += "&pracrnoid=" + $("#pracrnoid").val();
			formData += "&crno=" + $("#crno").val();
			formData += "&ccode=" + $("#ccode").val();
			formData += "&ctcode=" + $("#ctcode").val();
			formData += "&cclength=" + $("#cclength").val();
			formData += "&ccheight=" + $("#ccheight").val();
			formData += "&cpife=" + cpife;
			formData += "&cpishold=" + $("#cpishold").val();
			formData += "&cpiremark=" + $("#cpiremark").val();

			$.ajax({
				url: "<?php echo site_url('estimation/edit_container'); ?>",
				type: "POST",
				data: formData,
				dataType: 'json',
				success: function(json) {
					if (json.message == "success") {
						Swal.fire({
							icon: 'success',
							title: "Success",
							html: '<div class="text-success">' + json.message + '</div>'
						});
					} else {
						Swal.fire({
							icon: 'error',
							title: "Error",
							html: '<div class="text-danger">' + json.message + '</div>'
						});
					}
				}
			});

		});

		$("#rpcrno").on("keyup", function() {
			var crno = $("#rpcrno").val();
			var status = "";
			$("#fEstimasi").trigger("reset");
			$("#formDetail").trigger("reset");
			$("#tblList_add tbody").html("");
			$("#rpcrno").val(crno);				
			$(this).val($(this).val().toUpperCase());
			$.ajax({
				url: "<?= site_url('estimation/getDataEstimasi'); ?>",
				type: "POST",
				data: { "crno": crno },
				dataType: "JSON",
				success: function(json) {
					if (json.status == "Failled") {
						$(".err-crno").show();
						$(".err-crno").html(json.message);
						$("#crno").css("background", "#ffbfbf!important");
						$("#crno").css("border-color", "#ea2525");
					} else {
						$(".err-crno").html("");
						$(".err-crno").hide();
						$("#rpcrno").css("background", "#fff!important");
						$("#rpcrno").css("border-color", "#ccc");
						// fill data Header
						var rptglest = $.format.date(json.header.rptglest,"dd-MM-yyyy")
						var svsurdat = $.format.date(json.header.svsurdat,"dd-MM-yyyy")
						$("#det_crno").val(crno);
						$("#svid").val(json.header.svid);
						$("#det_svid").val(json.header.svid);
						$("#syid").val(json.header.syid);
						$("#rpcrton").val(json.header.svcrton);
						$("#rpcrtby").val(json.header.svcrtby);
						$("#rpnoest").val(json.header.rpnoest);
						$("#rptglest").val(rptglest);
						$("#cccode").val(json.header.cccode);
						$("#ctcode").val(json.header.ctcode);
						$("#cclength").val(json.header.cclength);
						$("#ccheight").val(json.header.ccheight);
						$("#coexpdate").val(json.header.coexpdate);
						$("#cono").val(json.header.cono);
						$("#svsurdat").val(svsurdat);
						$("#svcond").val(json.header.svcond);
						$("#svcond").val(json.header.svcond);
						$("#rpver").val(json.header.rpver);
						$("#tblList_add tbody").html(json.detail);
						console.log(json.header);
					}
				}
			});
		});

		$("#tblList_add tbody").on("click",".view", function(e){
			e.preventDefault();
			$("#saveDetail").prop("disabled",true);
			var row = $(this).closest("tr");
			var rpid = row.find(".no").text();
			var lccode = row.find(".lccode").text();
			var cmcode = row.find(".cmcode").text();
			var dycode = row.find(".dycode").text();
			var rmcode = row.find(".rmcode").text();
			var rdcalmtd = row.find(".rdcalmtd").text();
			var rdsize = row.find(".rdsize").text();
			var muname = row.find(".muname").text();
			var rdqtyact = row.find(".rdqty").text();
			var rdmhr = row.find(".rdmhr").text();
			var tucode = row.find(".curr_symbol").text();
			var rdmat = row.find(".rdmat span").text();
			var curr_code = "";
			if(tucode=="IDR") {	curr_code="001"; } 
			else if(tucode=="USD") { curr_code="002"; } 
			else if(tucode=="JPY") { curr_code="003"; }
			else if(tucode=="SGD") { curr_code="004"; }
			else if(tucode=="EUD") { curr_code="005"; }
			$("#rpid").val(rpid);
			$("#lccode").select2().select2('val',lccode);
			$("#cmcode").select2().select2('val',cmcode);
			$("#dycode").select2().select2('val',dycode);
			$("#rmcode").select2().select2('val',rmcode);
			$("#rdsize").val(rdsize);
			$("#muname").val(muname);
			$("#rdqtyact").val(rdqtyact);
			$("#rdmhr").val(rdmhr);
			$("#tucode").val(curr_code);
			$("#rdmat").val(rdmat);
			$("input[name=rdcalmtd][value=" + rdcalmtd + "]").prop('checked', true);
			// var $set_rdcalmtd = $('input:radio[name=rdcalmtd]');
			// $("input[name=rdcalmtd][value=" + cmcode + "]").prop('checked', true);
	  //       if($set_rdcalmtd.is(':checked') === false) {
	  //           $set_rdcalmtd.filter('[value=Male]').prop('checked', true);
	  //       }				
		});

		// End Step 2
		$('#ctTable tbody').on("click", ".print_order", function(e) {
			e.preventDefault();
			var praid = $(this).data("praid");
			window.open("<?php echo site_url('estimation/print_order/'); ?>" + praid, '_blank', 'height=600,width=900,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
		});

		runDataTables();
		var table = $('#ctTable').DataTable({
			"processing": true,
			"serverSide": true,
			"autoWidth": false,
			"fixedColumns": true,
			"ajax": $.fn.dataTable.pipeline({
				url: '<?= site_url('estimation/list_data'); ?>',
				pages: 5
			}),
			sDom: 'T<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"pull-right"ip>>>',
			PaginationType: "bootstrap",
			oLanguage: {
				"sSearch": "",
				"sLengthMenu": "_MENU_ &nbsp;"
			}
		});

		$('.dataTables_filter input').attr("placeholder", "Search");
		$('.DTTT_container').css('display', 'none');
		$('.DTTT').css('display', 'none');

	});

	function runDataTables() {
		$.fn.dataTable.pipeline = function(opts) {
			var conf = $.extend({
				pages: 5,
				url: '',
				data: null,
				method: 'POST'
			}, opts);

			var cacheLower = -1;
			var cacheUpper = null;
			var cacheLastRequest = null;
			var cacheLastJson = null;

			return function(request, drawCallback, settings) {

				var ajax = true;
				var requestStart = request.start;
				var drawStart = request.start;
				var requestLength = request.length;
				var requestEnd = requestStart + requestLength;

				if (settings.clearCache) {
					ajax = true;
					settings.clearCache = false;
				} else if (cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper) {
					ajax = true;
				} else if (JSON.stringify(request.order) !== JSON.stringify(cacheLastRequest.order) ||
					JSON.stringify(request.columns) !== JSON.stringify(cacheLastRequest.columns) ||
					JSON.stringify(request.search) !== JSON.stringify(cacheLastRequest.search)
				) {
					ajax = true;
				}

				cacheLastRequest = $.extend(true, {}, request);

				if (ajax) {

					cacheLower = requestStart;
					cacheUpper = requestStart + (requestLength * conf.pages);

					request.start = requestStart;
					request.length = requestLength * conf.pages;
					request.startdate = $("#startdate").val();
					request.enddate = $("#enddate").val();
					request.rows = requestLength;

					if ($.isFunction(conf.data)) {

						var d = conf.data(request);
						if (d) {
							$.extend(request, d);
						}
					} else if ($.isPlainObject(conf.data)) {
						$.extend(request, conf.data);
					}

					settings.jqXHR = $.ajax({
						"type": conf.method,
						"url": conf.url,
						"data": request,
						"dataType": "json",
						"cache": false,
						"beforeSend": function() {
							$('#ctTable > tbody').html(
								'<tr class="odd">' +
								'<td valign="top" colspan="6" class="dataTables_empty">Loading&hellip; <i class="fa fa-gear fa-1x fa-spin"></i></td>' +
								'</tr>'
							);
						},
						"success": function(json) {
							$("#spinner").hide();
							$(".fa-spin").remove();
							$("#SearchSC").removeAttr("disabled");
							cacheLastJson = $.extend(true, {}, json);

							if (cacheLower != drawStart) {
								json.data.splice(0, drawStart - cacheLower);
							}
							json.data.splice(requestLength, json.data.length);

							drawCallback(json);
						}
					});
				} else {
					json = $.extend(true, {}, cacheLastJson);
					json.draw = request.draw;
					json.data.splice(0, requestStart - cacheLower);
					json.data.splice(requestLength, json.data.length);

					drawCallback(json);
				}
			}
		}
	}
</script>