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
	$('.select-syid').select2();
	// $("input:radio[name=cpofe]").prop('disabled', true);
	// $("input:radio[name=cpochrgbm]").prop('disabled', true);
	// $("input:radio[name=cpopaidbm]").prop('disabled', true);
	$("input:checkbox[name=crcdp]").prop('disabled', true);
	$("input:checkbox[name=cracep]").prop('disabled', true);
	$("input:checkbox[name=crcsc]").prop('disabled', true);
	// DATATABLE
	// $("#ctTable").DataTable({
 //        "paging": false,
 //        "info": false,		
	// });
	// datePicker
	$(".tanggal").datepicker({
		autoclose:true,
		format:'dd-mm-yyyy',
	});
	$(".tanggal").datepicker('setDate',new Date());

	$("#addOrder").on('click', function(e){
		e.preventDefault();
		$("#save").show();
		$("#update").hide();
		$("#form1")[0].reset();
		$("#formDetail")[0].reset();
		$("#detTable tbody").html("");
		window.location.href = "#OrderPra";
	});

	$("form#fGateOut").on("submit", function(e){
		e.preventDefault();													
		var cpid = $("#cpid").val();
		var orderno = $("#cpoorderno").val();
		$.ajax({
			url: "<?php echo site_url('gateout/add'); ?>",
			type: "POST",
			data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,				
			dataType: 'json',
			success: function(json) {
				if(json.status == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});			
			        window.open("<?php echo site_url('gateout/print_eir_out/'); ?>" + orderno+'/'+cpid, '_blank', 'height=600,width=700,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
					window.location.href = "<?php echo site_url('gateout'); ?>";
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

	$('#ctTable').on('click', '.edit', function(e){
		e.preventDefault();
		var crno = $(this).data("crno");
		var cpoorderno = $(this).data("orderno");
		window.location.href = "<?=site_url('gateout/edit/');?>"+crno;
	});

	$("#crcdp").on("change", function(){
		var val = this.checked ? '1' : '0';
		return $(this).val(val);
	});
	$("#cracep").on("change", function(){
		var val = this.checked ? '1' : '0';
		return $(this).val(val);
	});
	$("#crcsc").on("change", function(){
		var val = this.checked ? '1' : '0';
		return $(this).val(val);
	});	
	$("#cpishold").on("change", function(){
		var val = this.checked ? '1' : '0';
		return $(this).val(val);
	});

	$("#cpochrgbm").on("change", function(){
		var val = this.checked ? '1' : '0';
		return $(this).val(val);
	});

	$("#crno").on("keyup", function(){
		var crno = $("#crno").val();
		var status = "";
		var cpofe = $('input:radio[name=cpofe]');
		$("#fGateOut").trigger("reset");
		$("#crno").val(crno);	
		$(this).val($(this).val().toUpperCase());		
		$.ajax({
			url:"<?=site_url('gateout/get_data_gateout');?>",
			type:"POST",
			data: "crno="+crno,
			dataType:"JSON",
			success: function(json){
				if(json.status=="Failled") {
					$(".err-crno").show();
					$(".err-crno").html(json.message);
					$("#crno").css("background", "#ffbfbf!important");
					$("#crno").css("border-color", "#ea2525");	
				} else {
					console.log(json.data);
					$(".err-crno").html("");
					$(".err-crno").hide();
					$("#crno").css("background", "#fff!important");
					$("#crno").css("border-color", "#ccc");
					$("#cpid").val(json.data.crcpid);
					$("#cpopr1").val(json.data.cpopr1);
					$("#cpcust1").val(json.data.cpcust1);
					$("#cccode").val(json.data.cccode);
					$("#cclength").val(json.data.cclength);
					$("#ccheight").val(json.data.ccheight);
					$("#ctcode").val(json.data.ctcode);
					$("#cpoterm").val(json.data.cpoterm);
					$("#crmanuf").val(json.data.crmanuf);
					$("#manufdate").val(json.data.manufdate);
					$("#crlastact").val(json.data.crlastact);
					$("#crlastcond").val(json.data.crlastcond);
					if(json.data.crcdp==1) {
						$("#crcdp").prop("checked", true);
					} else {
						$("#crcdp").prop("checked", false);
					}					
					$("#crcdp").val(json.data.crcdp);
					if(json.data.cracep==1) {
						$("#cracep").prop("checked", true);
					} else {
						$("#cracep").prop("checked", false);
					}						
					$("#cracep").val(json.data.cracep);
					if(json.data.crcsc==1) {
						$("#crcsc").prop("checked", true);
					} else {
						$("#crcsc").prop("checked", false);
					}						
					$("#crcsc").val(json.data.crcsc);
					$("#crweightk").val(json.data.crweightk);
					$("#crweightl").val(json.data.crweightl);
					$("#crtarak").val(json.data.crtarak);
					$("#crtaral").val(json.data.crtaral);
					$("#crnetk").val(json.data.crnetk);
					$("#crnetl").val(json.data.crnetl);
					$("#crvol").val(json.data.crvol);
					$("#mtdesc").val(json.data.mtdesc);
					$("#svsurdat").val(json.data.svsurdat);
					$("#syid").val(json.data.syid);
					$("#cpodisdat").val(json.data.cpodisdat);
					$("#cpodriver").val(json.data.cpodriver);
					$("#cponopol").val(json.data.cponopol);
					$("#cpoorderno").val(json.data.cpoorderno);
					$("#cpoeir").val(json.data.cpoeir);
					$("#cporefout").val(json.data.cporefout);
					$("#cpopratgl").val(json.data.cpopratgl);
					$("#cpojam").val(json.data.cpojam);
					$("#cporeceptno").val(json.data.cporeceptno);

					if(json.data.cpochrgbm==1) {
						$("#cpochrgbm").prop('checked',true);
						$("#cpochrgbm:checked").val(json.data.cpochrgbm);
					} else {
						$("#cpochrgbm").prop('checked',false);
						$("#cpochrgbm").val("1");
					}

					if(json.data.cpopaidbm==1) {
						$("#cpopaidbm").prop('checked',true);
						$("#cpopaidbm:checked").val(json.data.cpopaidbm);
					}

					$("#cpoload").val(json.data.cpoload);

				    if(json.data.cpofe==1) {
				        cpofe.filter('[value=1]').prop('checked', true);
				    } else {
				        cpofe.filter('[value=0]').prop('checked', true);
					}					
					$("#cpocargo").val(json.data.cpocargo);
					$("#vesid").val(json.data.vesid);
					$("#cposeal").val(json.data.cposeal);
					$("#cporeceiv").val(json.data.cporeceiv);
					$("#cpovoyid").val(json.data.cpovoyid);
					// $("#vesopr").val(json.data.vesopr);
				}
			}
		});
	});

	$('#ctTable').on("click",".print", function(e){
		e.preventDefault();
		var orderno = $(this).data("orderno");
		var cpid = $(this).data("cpid");
        window.open("<?php echo site_url('gateout/print_eir_out/'); ?>" + orderno + '/' + cpid, '_blank', 'height=600,width=700,toolbar=no,directories=no,status=no, menubar=no,scrollbars=no,resizable=no ,modal=yes');
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

	// DATATABLE
	runDataTables();
	var table = $('#ctTable').DataTable({
        "processing": true,
        "serverSide": true,
        "autoWidth": false,
        "fixedColumns": true,
        "ajax": $.fn.dataTable.pipeline( {
            url: '<?=site_url('gateout/list_data');?>',
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