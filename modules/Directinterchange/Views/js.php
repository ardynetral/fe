<script type="text/javascript">
$(document).ready(function() {
	// SELECT2
	$('.select-cncode').select2();		
	$('.select-pr').select2();

	$(".tanggal").datepicker({
		autoclose:true,
		format:'dd-mm-yyyy',
		startDate: '-5y'
	});
		
    // DATATABLE
    runDataTables();
    var table = $('#ctTable').DataTable({
        "processing": true,
        "serverSide": true,
        "autoWidth": false,
        "fixedColumns": true,
        "ajax": $.fn.dataTable.pipeline( {
            url: '<?=site_url('directinterchange/list_data');?>',
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



	$("#saveData").click(function(e){
		e.preventDefault();
		var formData = "chorderno=" + $("#chorderno").val();
		formData += "&crno=" + $("#crno").val();
		formData += "&prcode=" + $("#prcode").val();
		formData += "&cpcust=" + $("#cpcust").val();
		formData += "&onhiredate=" + $("#onhiredate").val();
		formData += "&chgnote=" + $("#chgnote").val();

		$.ajax({
			url: "<?php echo site_url('directinterchange/add'); ?>",
			type: "POST",
			data: formData,
			dataType: 'json',
			success: function(json) {
				if(json.status == "success") {
					Swal.fire({
					  icon: 'success',
					  title: "Success",
					  html: '<div class="text-success">'+json.message+'</div>'
					});							
					window.location.href = "<?php echo site_url('directinterchange'); ?>";
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

    $("#prcode").on("change", function(){
        var prcode = $(this).val();
        $("#cpcust").val(prcode);
    });

    $("#crno").on("keyup", function() {
        var crno = $("#crno").val();
        var status = "";
        $("#rpcrno").val(crno);   
        $("#cpopr0").val("");          
        $("#cpcust0").val("");          
        $(this).val($(this).val().toUpperCase());
        $.ajax({
            url: "<?= site_url('directinterchange/getContainer'); ?>",
            type: "POST",
            data: { "crno": crno },
            dataType: "JSON",
            success: function(json) {
                $("#cpcust0").val(json.cpcust);                
                $("#cpopr0").val(json.cpopr);
            }
        });
    });


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