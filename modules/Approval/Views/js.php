<script type="text/javascript">
$(document).ready(function() {
		$('.select-lccode').select2();
		$('.select-cmcode').select2();
		$('.select-dycode').select2();
		$('.select-rmcode').select2();
    
		// Error Message element
		$(".err-crno").hide(); //container number check

		// DATATABLE
		$("#tblList_add").DataTable({
			"paging": false,
			"info": false,
		});

		$(".tanggal").datepicker({
			autoclose: true,
		});

		$("form#fEstimasi").on("submit", function(e) {
			e.preventDefault();

			$.ajax({
				url: "<?php echo site_url('approval/add'); ?>",
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

		$("#nextEstimasi").on("click", function(e){
			e.preventDefault();
			var svid = $("#svid").val();
			var crno = $("#rpcrno").val();
			$.ajax({
				url: "<?=site_url('approval/next_estimasi/')?>" + svid,
				type: "POST",
				data: { "crno":crno },
				dataType: "json",
				success: function(json){
					if(json.status=="success") {
						Swal.fire({
							icon: 'success',
							title: "Success",
							html: '<div class="text-success">' + json.message + '</div>'
						});	
						$("#rpver").val(json.header.rpver);					
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

		$('#tblList_add tbody').on('click', '.delete', function(e) {
			e.preventDefault();
			var svid = $(this).data('svid');
			var rpid = $(this).data('rpid');
			var rdno = $(this).data('rdno');
			var crno = $(this).data('crno');
			Swal.fire({
				title: 'Are you sure?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
				if (result.isConfirmed) {
					delete_data(svid,rpid,rdno,crno);
				}
			});

		});

		$('#tblList_edit tbody').on('click', '.delete', function(e) {
			e.preventDefault();
			var svid = $(this).data('svid');
			var rpid = $(this).data('rpid');
			var rdno = $(this).data('rdno');
			var crno = $(this).data('crno');
			Swal.fire({
				title: 'Are you sure?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, delete it!'
			}).then((result) => {
				if (result.isConfirmed) {
					delete_data_edit(svid,rpid,rdno,crno);
				}
			});

		});

		$("#addDetail").on("click", function(e){
			var det_crno = $("#det_crno").val();
			var tblRow = parseInt($("#tblList_add tr").length);
			$("#saveDetail").prop('disabled', false);
			$("#formDetail").trigger("reset");
			$("#det_crno").val(det_crno);
			$("#rpid").val(tblRow);
			$("#lccode").select2().select2('val','');
			$("#cmcode").select2().select2('val','');
			$("#dycode").select2().select2('val','');
			$("#rmcode").select2().select2('val','');	
			$("#lccode").select2().select2('focus',true);

		});

		// save detailContainer
		$("#formDetail").on("submit", function(e) {
			e.preventDefault();
			$("#tblList_add tbody").html("");
			$.ajax({
				url: "<?php echo site_url('approval/save_detail'); ?>",
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
						$("#formDetail").trigger("reset");
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

	$("#addDetail").on("click", function(e){
		var det_crno = $("#rpcrno").val();
		var det_svid = $("#svid").val();
		var tblRow = parseInt($("#tblList_add tr").length);
		$("#saveDetail").show();
		$("#saveDetail").prop("disabled",false);
		$("#updateDetail").hide();
		$("#updateDetail").prop("disabled",true);
		$("#formDetail").trigger("reset");
		$("#det_crno").val(det_crno);
		$("#rpid").val(tblRow);
		$("#lccode").select2().select2('val','');
		$("#cmcode").select2().select2('val','');
		$("#dycode").select2().select2('val','');
		$("#rmcode").select2().select2('val','');			
	});

	$("#addDetailEdit").on("click", function(e){
		var det_crno = $("#rpcrno").val();
		var det_svid = $("#svid").val();
		var tblRow = parseInt($("#tblList_edit tr").length);
		$("#act").val("add");
		// $("#formUpdateDetail").attr("id","formInsertEditDetail");
		$("#saveDetail").show();
		$("#saveDetail").prop("disabled",false);
		$("#updateDetail").hide();
		$("#updateDetail").prop("disabled",true);
		$("#formDetail").trigger("reset");
		$("#det_crno").val(det_crno);
		$("#det_svid").val(det_svid);
		$("#rpid").val(tblRow);
		$("#lccode").select2().select2('val','');
		$("#cmcode").select2().select2('val','');
		$("#dycode").select2().select2('val','');
		$("#rmcode").select2().select2('val','');			
	});

		// save detailContainer
		$("#formUpdateDetail").on("submit", function(e) {
			e.preventDefault();
			var url="";
			if($("#act").val()=="add") { url = "<?php echo site_url('approval/save_detail'); ?>"; }
			else if($("#act").val()=="edit") { url = "<?php echo site_url('approval/update_detail'); ?>"; }
			$.ajax({
				url: url,
				type: "POST",
				data: new FormData(this),
	            processData: false,
	            contentType: false,
	            cache: false,		
				dataType: 'json',
				success: function(json) {
					if (json.status == "success") {
						$("#tblList_edit tbody").html("");
						Swal.fire({
							icon: 'success',
							title: "Success",
							html: '<div class="text-success">' + json.message + '</div>'
						});
						$("#tblList_edit tbody").html(json.data);
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

		// save detailContainer
		$("#formFinalEstimasi").on("submit", function(e) {
			e.preventDefault();
			$.ajax({
				url: "<?php echo site_url('approval/final_estimasi'); ?>",
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
						window.location.href= "<?=site_url('approval');?>";
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
				url: "<?= site_url('approval/getDataEstimasi'); ?>",
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
						$("#final_crno").val(crno);
						$("#svid").val(json.header.svid);
						$("#det_svid").val(json.header.svid);
						$("#final_svid").val(json.header.svid);
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
			$("#saveDetail").prop("disabled",false);
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
			var rdaccount = row.find(".rdaccount").text();
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
			$("input[name=rdaccount][value=" + rdaccount + "]").prop('checked', true);	
		});

		$("#tblList_edit tbody").on("click",".edit", function(e){
			e.preventDefault();
			$("#act").val("edit");
			$("#saveDetail").hide();
			$("#saveDetail").prop("disabled",true);
			$("#updateDetail").show();
			$("#updateDetail").prop("disabled",false);
			var row = $(this).closest("tr");
			var crno = row.find(".crno").text();
			var svid = row.find(".svid").text();
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
			var rdaccount = row.find(".rdaccount").text();
			var rdtotal = row.find(".rdtotal").text();
			var curr_code = "";
			if(tucode=="IDR") {	curr_code="001"; } 
			else if(tucode=="USD") { curr_code="002"; } 
			else if(tucode=="JPY") { curr_code="003"; }
			else if(tucode=="SGD") { curr_code="004"; }
			else if(tucode=="EUD") { curr_code="005"; }
			$("#det_crno").val(crno);
			$("#svid").val(svid);
			$("#det_svid").val(svid);			
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
			$("#rdtotal").val(rdtotal);
			$("input[name=rdcalmtd][value=" + rdcalmtd + "]").prop('checked', true);
			$("input[name=rdaccount][value=" + rdaccount + "]").prop('checked', true);
			  $("#fileList").html("");		
		});

	// check file size while upload
	$('#files').bind('change', function() {

	  //this.files[0].size gets the size of your file.
	  if(this.files[0].size > 524288) {
		Swal.fire({
		  icon: 'error',
		  title: "MAX 512 KB",
		  html: '<div class="text-danger">File terlalu besar!</div>'
		});
		this.value='';
	  }

	});

	$("#ctTable tbody").on("click", ".print", function(e) {
		e.preventDefault();
		var crno = $(this).data("crno");
		window.open("<?=site_url('approval/print/'); ?>" + crno, '_blank', 'height=600,width=900,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
	});

	$("#print").on("click", function(e) {
		e.preventDefault();
		var crno = $(this).data("crno");
		window.open("<?=site_url('approval/print/'); ?>" + crno, '_blank', 'height=600,width=900,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
	});
	runDataTables();
	var table = $('#ctTable').DataTable({
        "processing": true,
        "serverSide": true,
        "autoWidth": false,
        "fixedColumns": true,
        "ajax": $.fn.dataTable.pipeline( {
            url: '<?=site_url('approval/list_data');?>',
            pages: 5  
        } )
        ,
        sDom: 'T<"row view-filter"<"col-sm-12"<"pull-left"l><"pull-right"f><"clearfix">>>t<"row view-pager"<"col-sm-12"<"pull-right"ip>>>',
        PaginationType : "bootstrap", 
        oLanguage: { "sSearch": "",
            "sLengthMenu" : "_MENU_ &nbsp;"}
    });
	
	$('.dataTables_filter input').attr("placeholder", "Search");
    $('.DTTT_container').css('display','none');
    $('.DTTT').css('display','none');

});

function loadFileList(crno) {
	$.ajax({
		url: "<?= site_url('approval/getFileList/'); ?>" + crno,
		type: "POST",
		dataType: "JSON",
		success: function(json) {
			$("#fileList").html(json);
		}
	});			
}

function delete_data(svid,rpid,rdno,crno) {
	$.ajax({
		url: "<?php echo site_url('approval/delete_detail'); ?>",
		type: "POST",
		data: { "svid":svid, "rpid":rpid, "rdno":rdno, "crno":crno },
		dataType: 'json',
		success: function(json) {
			if (json.status == "success") {
				Swal.fire({
					icon: 'success',
					title: "Success",
					html: '<div class="text-success">' + json.message + '</div>'
				});
				$("#tblList_add tbody").html(json.data);
				$("#saveDetail").hide();
				$("#updateDetail").hide();
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
function delete_data_edit(svid,rpid,rdno,crno) {
	$.ajax({
		url: "<?php echo site_url('approval/delete_detail_edit'); ?>",
		type: "POST",
		data: { "svid":svid, "rpid":rpid, "rdno":rdno, "crno":crno },
		dataType: 'json',
		success: function(json) {
			if (json.status == "success") {
				Swal.fire({
					icon: 'success',
					title: "Success",
					html: '<div class="text-success">' + json.message + '</div>'
				});
				$("#tblList_edit tbody").html(json.data);
				$("#saveDetail").hide();
				$("#updateDetail").hide();				
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
function runDataTables() {		
    $.fn.dataTable.pipeline = function ( opts ) { 
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

        return function (request, drawCallback, settings) {
            
			var ajax = true;
            var requestStart = request.start;
            var drawStart = request.start;
            var requestLength = request.length;
            var requestEnd = requestStart + requestLength;

            if (settings.clearCache) { 
                ajax = true;
                settings.clearCache = false;
            }
            else if (cacheLower < 0 || requestStart < cacheLower || requestEnd > cacheUpper) { 
                ajax = true;
            }
            else if (JSON.stringify(request.order) !== JSON.stringify(cacheLastRequest.order) ||
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
                }
                else if ($.isPlainObject(conf.data)) { 
                    $.extend(request, conf.data);
                }

                settings.jqXHR = $.ajax({
                    "type": conf.method,
                    "url": conf.url,
                    "data": request,
                    "dataType": "json",
                    "cache": false,
					"beforeSend": function(){
						$("#spinner").show();
						$("#SearchSC").attr("disabled","disabled");
						$("#SearchSC").append('<i class="fa fa-gear fa-1x fa-spin"></i>');
					},
                    "success": function (json) {
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
            }
            else {
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