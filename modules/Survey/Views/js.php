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
 //        "paging": false,
 //        "info": false,		
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

	var alReadyClicked = false;
	$("#saveData").click(function(){
		if (alReadyClicked == false) {
			$('#saveData').prop('disabled','disabled');
			formData = $('#form_input').serializeArray();
			$.ajax({
				url: "<?php echo site_url('survey/save'); ?>",
				type: "POST",
				data: formData,
				dataType: 'json',
				beforeSend: function(){
					$('#saveData').prop('disabled',true);
					$('#cancel').prop('disabled',true);
				},
				success: function(res) {
					console.log(res)
					$('#saveData').prop('disabled',false);
					$('#cancel').prop('disabled',false);
					if(res.message == "success") {
						Swal.fire({
						  icon: 'success',
						  title: "Success",
						  html: '<div class="text-success">'+res.message+'</div>'
						});							
						window.location.href = "<?php echo site_url('survey'); ?>";

					} else {
						Swal.fire({
						  icon: 'error',
						  title: "Error",
						  html: '<div class="text-danger">'+res.message+'</div>'
						});						
					}
				}
			});

		} else {
			return false;
		}
		var alReadyClicked = true;
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

	$('#ctTable').on('click', 'tbody .delete_btn', function(){
		let crno = $(this).attr('crno');
		let cpiorderno = $(this).attr('cpiorderno');
		console.log(crno)
		Swal.fire({
		  title: 'Are you sure?',
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
		  if (result.isConfirmed) {
		  	delete_data(crno,cpiorderno);
		  }
		});		
		
	});

	function delete_data(crno,cpiorderno) {
		$.ajax({
			url: "<?php echo site_url('city/delete/'); ?>"+crno+"/"+cpiorderno,
			type: "POST",
			dataType: 'json',
			success: function(res) {
				if(res.message == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+res.message+'</div>'
					});							
					window.location.href = "<?php echo site_url('city'); ?>";
				} else {
					Swal.fire({
					  icon: 'error',
					  title: "Error",
					  html: '<div class="text-danger">'+res.message+'</div>'
					});						
				}
			}
		});		
	}

	// save detailContainer
	// $("#saveData").on("click", function(e){
	// 	formData = $("#form_input").serialize();
	// 	console.log(formData);
	// 	$.ajax({
	// 		url: "<?php echo site_url('survey/save'); ?>",
	// 		type: "POST",
	// 		data: formData,
	// 		dataType: 'json',
	// 		beforeSend:function(){
	// 			$('#saveData').prop('disabled',true);
	// 			$('#cancel').prop('disabled',true);
	// 		},
	// 		success: function(res) {
	// 			console.log(res.api);
	// 			$('#saveData').prop('disabled',false);
	// 			$('#cancel').prop('disabled',false);
	// 			if(res.err == false) {
	// 				Swal.fire({
	// 				  icon: 'success',
	// 				  title: "Success",
	// 				  html: '<div class="text-success">'+res.message+'</div>'
	// 				});							
	// 				// window.location.href = "<?php echo site_url('prain/view/'); ?>"+json.praid;
	// 				window.location.href = "<?php echo site_url('survey'); ?>";
	// 				// getDetailOrder(json.praid);
	// 			} else {
	// 				Swal.fire({
	// 				  icon: 'error',
	// 				  title: "Error",
	// 				  html: '<div class="text-danger">'+res.message+'</div>'
	// 				});						
	// 			}
	// 		}
	// 	});	

	// });

	$('#detTable tbody').on('click', '.edit', function(e){
		e.preventDefault();
		var crid = $(this).data("crid");
	    var cpife = $('input:radio[name=cpife]');
		$.ajax({
			url:"<?=site_url('survery/get_one/');?>"+crid,
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
        "language": {
		     "processing": '<Processing...'
		},
        "ajax": $.fn.dataTable.pipeline( {
            url: '<?=site_url('survey/list_data');?>',
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
						// Here, manually add the loading message.
				          $('#ctTable > tbody').html(
				            '<tr class="odd">' +
				              '<td valign="top" colspan="6" class="dataTables_empty">Loading&hellip; <i class="fa fa-gear fa-1x fa-spin"></i></td>' +
				            '</tr>'
				          );
						// $("#spinner").show();
						// $("#SearchSC").attr("disabled","disabled");
						// $("#SearchSC").append('<i class="fa fa-gear fa-1x fa-spin"></i>');
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


    $('#CRNO').keyup(function() {
    	crno = $(this).val();
    	if (crno.length == 11) {
	    	$.ajax({
				url:"<?=site_url('survey/checkValid');?>",
				type:"POST",
				data: "CRNO="+crno,
				dataType:"JSON",
				success: function(res){
					if (res.err == false) {
						for (const [key, value] of Object.entries(res.data)) {
							if (key == 'cpife' || key == 'cpiterm' ) {
								$("input[name="+key.toUpperCase()+"][value='"+value+"']").prop("checked",true);
							}else if( key=='cpichrgbb' || key=='cpipaidbb' || key=='crcdp' || key=='cracep' || key=='crcsc'){
								const checked = (value == 1? true:false);
								$(`#${key.toUpperCase()}`).prop("checked", checked);
							} else {
								$(`#${key.toUpperCase()}`).val(value)
							}
						}
						$('#LECONTRACTNO').val(res.data.prcode)
						$('#CPIPRANO').val(res.data.cpiorderno)
					} else {
						$('#form_input')[0].reset();
						Swal.fire({
						  icon: 'error',
						  title: "Error",
						  html: '<div class="text-danger"> Container Number is '+res.status.toUpperCase()+'</div>'
						});	
					}
				}
			});
    	} else if(crno.length == 0){
			$('#form_input')[0].reset();
    	}
	});
}
</script>