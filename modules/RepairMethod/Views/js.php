<script type="text/javascript">
$(document).ready(function() {
	// Error Message element
	$(".err-crno").hide(); //container number check
	// $("#update").hide();
	// $("#updateDetail").hide();
	$("#editOrderFrame").hide();
	// SELECT2
	$('.select-pr').select2();
	$('.select-port').select2();
	$('.select-depo').select2();
	$('.select-vessel').select2();
	$('.select-voyage').select2();
	$('.select-ccode').select2();
	// DATATABLE
	// $("#ctTable").DataTable({
	//     select: {
	//         style: 'single'
	//     }
	// });
	// datePicker
	$(".tanggal").datepicker({
		autoclose:true,
	});

	// $("#cpipratgl").datepicker("disable");

	$("#addOrder").on('click', function(e){
		e.preventDefault();
		$("#save").show();
		$("#update").hide();
		$("#form1")[0].reset();
		$("#formDetail")[0].reset();
		$("#detTable tbody").html("");
		window.location.href = "#OrderPra";
	});

	$("#save").click(function(e){
		e.preventDefault();													
		// window.scrollTo(xCoord, yCoord);
		var formData = "cpiorderno=" + $("#cpiorderno").val();
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
		// alert(formData);
		// console.log("data="+formData);

		$.ajax({
			url: "<?php echo site_url('prain/add'); ?>",
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
					window.location.href = "#formDetail";
					// $("#navItem3").removeClass("disabled");
					// $("#navLink3").attr("data-toggle","tab");
					// $("#navLink3").trigger("click");	
					$("#praid").val(json.praid);				
					$("#saveData").prop('disabled', true);
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

	$("#liftoffcharge").on("change", function(){
		var val = this.checked ? '1' : '0';
		return $(this).val(val);
	});

	$("#cpishold").on("change", function(){
		var val = this.checked ? '1' : '0';
		return $(this).val(val);
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
	$("#editOrder").on('click', function(e){
		enableFormOrder();
		$("#update").show();
		$("#cancel").show();
	});

	$("#update").click(function(e){
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
			url: "<?php echo site_url('prain/edit/'); ?>"+$("#praid").val(),
			type: "POST",
			data: formData,
			dataType: 'json',
			success: function(json) {
				console.log(json.message);
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.msgdata+'</div>'
					});							
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


// BTN View click
	$('#ctTable tbody').on('click', '.view-order', function(e){
		e.preventDefault();	
		var praid = $(this).data('praid');
		$("#detTable tbody").html("");
		$("#save").hide();
		$("#cancel").hide();
		$("#editOrderFrame").show();
		disableFormOrder();
		$.ajax({
			url: "<?php echo site_url('prain/ajax_view/'); ?>"+praid,
			type: "POST",
			dataType: 'json',
			// data:"praid="+praid,
			success: function(json) {
				if(json.status === "success") {
					// console.log("data: "+json.dt_header.cpidish);
// "praid":42,"cpiorderno":"PI000D100000024","cpopr":"CMA","cpcust":"MIF","cpidish":"BEN","cpidisdat":"2021-09-01","liftoffcharge":0,"cpdepo":"11A25","cpipratgl":"2021-09-13","cpirefin":"","cpijam":"","cpivoyid":123,"cpives":"ARMADA SEJATI","cpicargo":"","cpideliver":"","cpilunas":0,"voyages":{"voyid":123,"vesid":"SINAR BATAM","voyno":"V246"},"vessels":{"vesid":"ARMADA SEJATI","vesopr":"SPIL","cncode":"ID","vestitle":"ARMADA SEJATI"
					// $("#cpidish option[value="+json.dt_header.cpidish+"]").attr("selected","selected");
					$("#praid").val(json.dt_header.praid);
					$("#cpidish").select2().select2('val',json.dt_header.cpidish);
					$("#prcode").select2().select2('val',json.dt_header.cpopr);
					$("#cucode").val(json.dt_header.cpcust);
					$("#cpidisdat").val(json.dt_header.cpidisdat);
					if(json.dt_header.liftoffcharge==1) {
						$("#liftoffcharge").prop('checked',true);
						$("#liftoffcharge:checked").val(json.dt_header.liftoffcharge);
					}
					$("#cpdepo").select2().select2('val',json.dt_header.cpdepo);
					$("#cpiorderno").val(json.dt_header.cpiorderno);
					$("#cpipratgl").val(json.dt_header.cpipratgl);
					$("#cpirefin").val(json.dt_header.cpirefin);
					$("#cpijam").val(json.dt_header.cpijam);
					$("#cpives").select2().select2('val',json.dt_header.cpives);
					$("#cpivoyid").val(json.dt_header.cpivoyid);
					if(json.dt_header.vessels!=null) {
						$("#cpopr").val(json.dt_header.vessels.vesopr);
					}
					$("#cpicargo").val(json.dt_header.cpicargo);
					$("#cpideliver").val(json.dt_header.cpideliver);

					// Hitung HC_STD
					$("#hc20").val(json.hc20);
					$("#hc40").val(json.hc40);
					$("#hc45").val(json.hc45);
					$("#std20").val(json.std20);
					$("#std40").val(json.std40);

					// get data container
					var dt_container = json.dt_detail;
					if(dt_container.length > 0) {
						dt_container.forEach(list_container);
						$("#detTable tbody").html(dt_container);
					}

					window.location.href = "#OrderPra";

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

	$("#ctTable tbody").on("click",".approve", function(e){
		e.preventDefault();
		var praid = $(this).data('praid');
		var formData = "cpiorderno=" + $("#cpiorderno").val();
		formData += "&praid=" + praid;
		// console.log(formData);
		$.ajax({
			url: "<?php echo site_url('prain/approve_order/'); ?>"+praid,
			type: "POST",
			data: formData,
			dataType: 'json',
			success: function(json) {
				console.log(json.message);
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">Order Approved</div>'
					});	
					window.location.href = "<?php echo site_url('prain'); ?>";
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
		"<td>"+num+"</td>"+
		"<td>"+item.crno+"</td>"+
		"<td>"+item.cccode+"</td>"+
		"<td>"+item.ctcode+"</td>"+
		"<td>"+item.cclength+"</td>"+
		"<td>"+item.ccheight+"</td>"+
		"<td>"+cpife+"</td>"+
		"<td>"+cpishold+"</td>"+
		"<td>"+item.cpiremark+"</td>"+
		"<td></td>"+
		"<td><a href='#' id='editContainer' class='btn btn-xs btn-primary edit' data-crid='"+item.pracrnoid+"'>edit</a><a href='#' id='deleteContainer' class='btn btn-xs btn-danger'>delete</a></td>"+
		"</tr>";
	}

	$('#ctTable tbody').on('click', '.delete', function(e){
		e.preventDefault();	
		var code = $(this).data('kode');
		Swal.fire({
		  title: 'Are you sure?',
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
		  if (result.isConfirmed) {
		  	delete_data(code);
		  }
		});		
		
	});

	function delete_data(code) {
		$.ajax({
			url: "<?php echo site_url('city/delete/'); ?>"+code,
			type: "POST",
			dataType: 'json',
			success: function(json) {
				if(json.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});							
					window.location.href = "<?php echo site_url('city'); ?>";
				} else {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+json.message+'</div>'
					});						
				}
			}
		});		
	}

	// STEP 1 :
	$("#prcode").on("change", function(){
		var prcode = $(this).val();
		$.ajax({
			url:"<?=site_url('prain/ajax_prcode_listOne/');?>"+prcode,
			type:"POST",
			data: "prcode="+prcode,
			dataType:"JSON",
			success: function(json){
				if(json.status=="Failled") {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+json.message+'</div>'
					});		
				} else {
					$("#cucode").val(json.data.cucode);
				}
			}
		});
	}) ;

	// Vessel dropdown change
	$("#cpives").on("change", function(){
		var vesid = $(this).val();
		$.ajax({
			url:"<?=site_url('prain/ajax_vessel_listOne/');?>"+vesid,
			type:"POST",
			data: "vesid="+vesid,
			dataType:"JSON",
			success: function(json){
				if(json.status=="Failled") {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+json.message+'</div>'
					});		
				} else {
					$("#cpopr").val(json.data.vesopr);
				}
			}
		});		
	});


	// STEP 2 : 
	//Get Container Code detail
	$("#ccode").on("change", function(){
		var cccode  = $(this).val();
		$.ajax({
			url:"<?=site_url('prain/ajax_ccode_listOne/');?>"+cccode,
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


	// save detailContainer
	$("#saveDetail").on("click", function(e){
		e.preventDefault();
		var cpife = $("input:radio[name=cpife]:checked").val();
		var formData = "praid=" + $("#praid").val();
		formData += "&crno=" + $("#crno").val();
		formData += "&ccode=" + $("#ccode").val();
		formData += "&ctcode=" + $("#ctcode").val();
		formData += "&cclength=" + $("#cclength").val();
		formData += "&ccheight=" + $("#ccheight").val();
		formData += "&cpife=" + cpife;
		formData += "&cpishold=" + $("#cpishold").val();
		formData += "&cpiremark=" + $("#cpiremark").val();
		
		$.ajax({
			url: "<?php echo site_url('prain/addcontainer'); ?>",
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
					// window.location.href = "<?php echo site_url('prain/view/'); ?>"+json.praid;
					window.location.href = "<?php echo site_url('prain'); ?>";
					// getDetailOrder(json.praid);
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

	$('#detTable tbody').on('click', '.edit', function(e){
		e.preventDefault();
		var crid = $(this).data("crid");
	    var cpife = $('input:radio[name=cpife]');
		$.ajax({
			url:"<?=site_url('prain/get_one_container/');?>"+crid,
			type:"POST",
			data: "crid="+crid,
			dataType:"JSON",
			success: function(json){	
				if(json.message=="success") {
					$("#pracrnoid").val(json.cr.pracrnoid);
					$("#crno").val(json.cr.crno);
					$("#ccode").select2().select2('val',json.cr.cccode);
					$("#ctcode").val(json.cr.ctcode);
					$("#cclength").val(json.cr.cclength);
					$("#ccheight").val(json.cr.ccheight);

				    $("#saveDetail").hide();
				    $("#updateDetail").show();

				    if(json.cr.cpife=="1") {
				        cpife.filter('[value=1]').prop('checked', true);
				    }else if(json.cr.cpife=="0"){
				        cpife.filter('[value=0]').prop('checked', true);
					}

					if(json.cr.cpishold==1) {
						$("#cpishold").prop('checked',true);
						$("#cpishold").val(json.cr.cpishold);
					}					
					$("#cpiremark").val(json.cr.cpiremark);

				}
			}		
		})
	});

	$("#updateDetail").on("click", function(e){
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
			url: "<?php echo site_url('prain/edit_container'); ?>",
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

	$("#crno").on("keyup", function(){
		var crno = $("#crno").val();
		var status = "";
		$.ajax({
			url:"<?=site_url('prain/checkContainerNumber');?>",
			type:"POST",
			data: "ccode="+crno,
			dataType:"JSON",
			success: function(json){
				if(json.status=="Failled") {
					$(".err-crno").show();
					$(".err-crno").html(json.message);
					$("#crno").css("background", "#ffbfbf!important");
					$("#crno").css("border-color", "#ea2525");					
				} else {
					$(".err-crno").html("");
					$(".err-crno").hide();
					$("#crno").css("background", "#fff!important");
					$("#crno").css("border-color", "#ccc");
				}
			}
		});
	});

	// End Step 2
	$('#ctTable tbody').on("click",".print_order", function(e){
		e.preventDefault();
		var praid = $(this).data("praid");
        window.open("<?php echo site_url('prain/print_order/'); ?>" + praid, '_blank', 'height=600,width=900,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
	});

	$('[data-toggle="tab"]').on('click', function(){
	    var $this = $(this),
	        loadurl = $this.attr('href'),
	        targ = $this.attr('data-target');

	    $.get(loadurl, function(data) {
	        $(targ).html(data);
	    });

	    $this.tab('show');
	    return false;
	});

	runDataTables();
	var table = $('#ctTable').DataTable({
        "processing": true,
        "serverSide": true,
        "autoWidth": false,
        "fixedColumns": true,
        "ajax": $.fn.dataTable.pipeline( {
            url: '<?=site_url('repairmethod/list_data');?>',
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