<script type="text/javascript">
$(document).ready(function() {
	// Error Message element
	$(".err-crno").hide(); //container number check
	$("#editOrderFrame").hide();
	// SELECT2
	$('.select-pr').select2();
	$('.select-port').select2();
	$('.select-depo').select2();
	$('.select-vessel').select2();
	$('.select-voyage').select2();
	$('.select-ccode').select2();
	// DATATABLE
	runDataTables();
	var table = $('#ctTable').DataTable({
        "processing": true,
        "serverSide": true,
        "autoWidth": false,
        "fixedColumns": true,
        "ajax": $.fn.dataTable.pipeline( {
            url: '<?=site_url('changecontainer/list_data');?>',
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
    // END DATATABLE
	// datePicker
	$(".tanggal").datepicker({
		autoclose:true,
		format:'dd-mm-yyyy',
		startDate: '-5y'
	});
	$(".tanggal").datepicker('setDate',new Date());
	// $("#cpipratgl").datepicker("disable");
	if($("#liftoffcharge").val("1")) {
		$("#liftoffcharge").prop('checked',true);
	}

	// set default CPIFE=empty
	$('input:radio[name=cpife]').filter('[value=0]').prop('checked', true);

	// EDIT DATA ORDER
	function enableFormOrder() {
		// $("#prcode").select2('enable');
		$("#cpidish").select2('enable');
		$("#cpidisdat").attr('readonly', false);
		$("#liftoffcharge").attr('readonly', false);
		$("#cpdepo").select2('enable');
		// $("#cpipratgl").attr('readonly', false);
		$("#cpirefin").attr('readonly', false);
		// $("#cpijam").attr('readonly', false);
		$("#cpives").select2('enable');
		$("#cpivoyid").attr('readonly', false);
		// $("#cpicargo").attr('readonly', false);
		$("#cpideliver").attr('readonly', false);		
	}


// BTN View click
	function list_container(item, index, arr) {
		var num = index+1;
		var cpishold = "";
		var cpife = "";
		if(item.cpife==1) {
			cpife="Full";
		}else {
			cpife="Empty";
		}

		if(item.cpishold==1) {
			cpishold = "Hold";
		} else {
			cpishold = "Release";
		}

		arr[index] = "<tr>"+
		"<td><a href='#' id='editContainer' class='btn btn-xs btn-primary edit' data-crid='"+item.pracrnoid+"'>edit</a><a href='#' id='deleteContainer' class='btn btn-xs btn-danger'>delete</a></td>"+
		"<td>"+num+"</td>"+
		"<td>"+item.crno+"</td>"+
		"<td>"+item.cccode+"</td>"+
		"<td>"+item.ctcode+"</td>"+
		"<td>"+item.cclength+"</td>"+
		"<td>"+item.ccheight+"</td>"+
		"<td>"+cpife+"</td>"+
		"<td>"+cpishold+"</td>"+
		"<td>"+item.cpiremark+"</td>"+
		"<td>"+item.sealno+"</td>"+
		"<td>"+item.cpigatedate+"</td>"+
		"</tr>";
	}

	// STEP 2 : 
	//Get Container Code detail
	$("#ccode").on("change", function(){
		$("#ctcode").val("");
		$("#cclength").val("");
		$("#ccheight").val("");		
		var cccode  = $(this).val();
		$.ajax({
			url:"<?=site_url('changecontainer/ajax_ccode_listOne/');?>"+cccode,
			type:"POST",
			data: "ccode="+ccode,
			dataType:"JSON",
			success: function(json){
				if(json.status=="Failled") {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+json.message+'</div>'
					});		
				} else {
					$("#ctcode").val(json.data.ctcode);
					$("#cclength").val(json.data.cclength);
					$("#ccheight").val(json.data.ccheight);
				}

			}
		});
	});

	$('#detTable tbody').on('click', '.cchange', function(e){
		e.preventDefault();
		$("#formChangeContainer").trigger("reset");
		$('#myModal').modal('toggle');
		var crid = $(this).data("crid");
	    var cpife = $('input:radio[name=cpife]');
	    var vesprcode = $("#vesprcode").val();
		$("#prcode").select2().select2('val','');
		$("#cucode").val("");
		$("#saveDetail").hide();
		$("#updateDetail").show();
		$.ajax({
			url:"<?=site_url('changecontainer/get_one_container/');?>"+crid,
			type:"POST",
			data: {"crid":crid,"vesprcode":vesprcode},
			dataType:"JSON",
			success: function(json){	
				if(json.message=="success") {
					$('input:radio[name=cpife]').filter('[value=0]').prop('checked', true);
					$("#biaya_lolo").val(json.biaya_lolo);
					$(".select-pr").select2('enable',false);
					$("#prcode").select2().select2('val',json.cr.cpopr);
					$("#cucode").val(json.cr.cpcust);
					$("#pracrnoid").val(json.cr.pracrnoid);
					$("#crno1").val(json.cr.crno);

					if(json.cr.cpopr=="ONES") {
						$("#deposit").prop("checked",true);
					}					

					$("#biaya_clean").val(json.cr.biaya_clean);
				}
			}		
		})
	});


	$("#updateDetail").on("click", function(e){
		e.preventDefault();
		// var formData = "praid=" + $("#pra_id").val();
		var formData = "&crno1=" + $("#crno1").val();
		formData += "&crno2=" + $("#crno2").val();
		formData += "&orderno=" + $("#order_no").val();
		
		$.ajax({
			url: "<?php echo site_url('changecontainer/change_container'); ?>",
			type: "POST",
			data: formData,
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});
					editLoadTableContainer($("#praid").val());
				} else {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+json.message+'</div>'
					});						
				}
			}
		});	

	});

	$("#crno2").on("keyup", function(){
		$(this).val($(this).val().toUpperCase());
		var crno = $("#crno2").val();
		var praid = $("#pra_id").val();
		var status = "";
		$(".err-crno").hide();
		$.ajax({
			url:"<?=site_url('changecontainer/checkContainerNumber');?>",
			type:"POST",
			data: {"crno":crno,"praid":praid},
			dataType:"JSON",
			success: function(json){
				if(json.status=="success") {
					$(".err-crno").show();
					$(".err-crno").html(json.message);
					$(".err-crno").addClass("text-success");
					$(".err-crno").removeClass("text-danger");
					$("#crno").css("background", "#fff!important");
					$("#crno").css("border-color", "#ccc");
					$("#updateDetail").removeAttr("disabled");
				} else {
					$(".err-crno").show();
					$(".err-crno").removeClass("text-success");
					$(".err-crno").addClass("text-danger");
					$(".err-crno").html(json.message);
					$("#crno").css("background", "#ffbfbf!important");
					$("#crno").css("border-color", "#ea2525");
					$("#updateDetail").prop("disabled",true);
				}
			}
		});
	});

	$("button.cancel").on("click", function(e){
		e.preventDefault();
		window.location.href = "<?php echo site_url('changecontainer'); ?>";
	});

});

function loadTableContainer(praid) {
	$('#detTable tbody').html("");
	$.ajax({
		url: "<?=site_url('changecontainer/get_container_by_praid/')?>"+praid,
		dataType: "json",
		success: function(json) {
			$('#detTable tbody').html(json);
		}
	});
}
function loadTableContainer2(praid) {
	$('#detTable tbody').html("");
	$.ajax({
		url: "<?=site_url('changecontainer/get_container_by_praid2/')?>"+praid,
		dataType: "json",
		success: function(json) {
			$('#detTable tbody').html(json);
		}
	});
}

function editLoadTableContainer(praid) {
	$('#detTable tbody').html("");
	$.ajax({
		url: "<?=site_url('changecontainer/edit_get_container/')?>"+praid,
		dataType: "json",
		success: function(json) {
			$('#detTable tbody').html(json);
		}
	});
}
function loadTableContainerAppv1(praid) {
	$('#detTable tbody').html("");
	$.ajax({
		url: "<?=site_url('changecontainer/appv1_containers/')?>"+praid,
		dataType: "json",
		success: function(json) {
			$('#detTable tbody').html(json);
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
						$('#ctTable > tbody').html(
				            '<tr class="odd">' +
				              '<td valign="top" colspan="6" class="dataTables_empty">Loading&hellip; <i class="fa fa-gear fa-1x fa-spin"></i></td>' +
				            '</tr>'
				          );
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