<script src="http://cdn.jsdelivr.net/jquery.mixitup/latest/jquery.mixitup.min.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/tooltip/tooltipster.bundle.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/plugins/tooltip/tooltipster.bundle.min.css" />
<style>
	.vertical-center {
  display:flex;
    align-items: center;
    text-align: center;
    }
    .row {
			padding: 0px;
		}
	.tooltip_templates { display: none; }
	.panel-group { margin-bottom: 10px; }
	label { margin-bottom: 2px;}
</style>
<div class="box" id="cari">
	<form id="form" method="post" name="form">
		<div class="box-body">
			<div class="col-md-3">
				<div class="form-group">
					<label>LEAVING FROM</label> <select class="form-control bandara" id='from' name='from' style="width: 100%;">
							<option value=""></option>
						</select>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>GOING TO</label> <select class="form-control bandara" id='to' name='to' style="width: 100%;">
							<option value=""></option>
						</select>
				</div>
			</div>
			<div class="col-md-2 col-sm-6 col-xs-12">
				<div class="form-group">
					<label>DEPARTING ON</label>
					<div class="input-group">
						<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div><input class="form-control pull-right" id="datepicker" name='date' type="text">
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-2 col-sm-6 col-xs-12" style="padding: 0;margin: 0;">
				<div class="col-md-4 col-sm-4 col-xs-4">
					<label>ADULT</label>
					<div class="input-group">
						<select title="adult" class=" form-control" id='adult' name='adult' style="width: 100%;">
							<option selected="selected">
								1
							</option>
							<option>
								2
							</option>
							<option>
								3
							</option>
							<option>
								4
							</option>
							<option>
								5
							</option>
							<option>
								6
							</option>
							<option>
								7
							</option>
						</select>
					</div>
				</div>
				<div class="col-md-4 col-sm-4 col-xs-4">
					<label>CHILDREN</label>
					<div class="input-group">
						<select class="form-control" id='child' name='child' style="width: 100%;">
							<option selected="selected">
								0
							</option>
							<option>
								1
							</option>
							<option>
								2
							</option>
							<option>
								3
							</option>
							<option>
								4
							</option>
							<option>
								5
							</option>
							<option>
								6
							</option>
							<option>
								7
							</option>
						</select>
					</div>
				</div>
				<div class="col-md-4 col-sm-4 col-xs-4">
					<label>INFANT</label>
					<div class="input-group">
						<select class="form-control" id='infant' name='infant' style="width: 100%;">
							<option selected="selected">
								0
							</option>
							<option>
								1
							</option>
							<option>
								2
							</option>
							<option>
								3
							</option>
						</select>
					</div>
				</div>
			</div>
			<div class="col-md-2 col-sm-4 col-xs-4">
				<div class="input-group" style="margin-top: 17px;">
				<button id='btn-search' class="btn btn-flat btn-success btn-lg"><i class="fa fa-search"></i> | SEARCH</button>
				</div>
			</div>
		</div><!-- /.box-body -->
	</form>
	<form id="booking" action="<?php echo base_url()?>airlines/booking" method="post">
		<input id='h_from' name='from' type="hidden" value=''> 
		<input id='h_to' name='to' type="hidden" value=''> 
		<input id='h_date' name='date' type="hidden" value=''> 
		<input id='h_adult' name='adult' type="hidden" value=''> 
		<input id='h_child' name='child' type="hidden" value=''> 
		<input id='h_infant' name='infant' type="hidden" value=''> 
		<input id='h_flight_key' name='key' type="hidden" value=''>
	</form><!-- /.box-footer-->
</div><!-- /.box -->

<div id="result-content" class="box box-primary center-block" style="width: 100%">
	<div class="box-header with-border">
		<h3 class="box-title"></h3>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div id="alert"></div>
		<div class="result" id="result-id">
			 <button class="sort-btn" data-sort="time:asc">Depart time</button>
    		 <button class="sort-btn" data-sort="total:asc">Total</button>
			<div class="list" id="list"></div>
		</div>
	</div>
</div><!-- /.box-body -->
<div class="tooltip_templates">
    <span id="tooltip_content">
        <span id="flights">This is the content<br> of my tooltip!</span>
    </span>
</div>

<script>
$(document).ready(function(){
    //Date picker
    $('#datepicker').datepicker({
       showOtherMonths: true,
       selectOtherMonths: true,
       dateFormat: 'dd-mm-yy', 
       minDate: 0,
    });
    var flights = [];
    
     $('body').on('mouseenter', '.tooltips:not(.tooltipstered)', function(){
         flights = $(this).attr('data-count').split("_");
         var itenary = '';
         for (a = 0; a < flights[0]; a++) {
         	itenary = itenary + $('#depart_'+flights[1]+'_'+a).text() + ' | ' + $('#timedepart_'+flights[1]+'_'+a).text() + ' - ';
         	itenary = itenary + $('#arrive_'+flights[1]+'_'+a).text() + ' | ' + $('#timearrive_'+flights[1]+'_'+a).text() + '';
         	itenary = itenary + '<br>';
         }
         
         $(this).tooltipster({
            contentAsHTML: true,
            animation: 'grow',
            position: 'top',
            contentCloning: true,
            interactive: true,
            content:'',
            functionFormat: function(instance, helper, content){
		        var displayedContent = itenary;		        
		        return displayedContent;
		    },		    
            trigger: 'custom',
		    triggerOpen: {
		        mouseenter: true,
		        touchstart: true,
		        tap: true
		    },
		    triggerClose: {
		        mouseleave: true,
		    }
         });
         $(this).tooltipster('open');
    });
    
    $('body').on('mouseenter', '.tooltips-harga:not(.tooltipstered)', function(){
        $(this).tooltipster({
            contentAsHTML: true,
            animation: 'grow',
            position: 'top',
            contentCloning: true,
            interactive: true,
            trigger: 'custom',
		    triggerOpen: {
		        mouseenter: true,
		        touchstart: true,
		        tap: true
		    },
		    triggerClose: {
		        mouseleave: true,
		    }
         });
         $(this).tooltipster('open');
    });
    
    $(".bandara").select2();
    var bandara = [] ;
    $.get( base_url+'assets/ajax/iata_bandara.json', function(data) {
        $.each(data, function(i, item) {
            bandara [item.code_route]= item.city + ' ' + item.name_airport ;
            $(".bandara").append($('<option>', {value: item.code_route, text: item.code_route +' - '+ item.city + ' ' + item.name_airport}));
        });
    	$('#from').select2('open');
    });
    
  $("#result-content").hide();
  $("#form").on("submit", function(event) {
        $("#h_from").val($("#from").val());
        $("#h_to").val($("#to").val());
        $("#h_date").val($("#datepicker").val());
        $("#h_adult").val($("#adult").val());
        $("#h_child").val($("#child").val());
        $("#h_infant").val($("#infant").val());
        
        $("#btn-search").removeClass('btn-success');
        $("#btn-search").addClass('btn-warning');
        $("#btn-search").children("i").removeClass('fa-search');
        $("#btn-search").children("i").addClass('fa-refresh fa-spin');
        
        $(over).appendTo("#cari");
        event.preventDefault(); 
        $(".list").empty();
        $.ajax({
            url:  base_url+"airlines/get_bestprice",
            type: "post",
            data: $(this).serialize(),
            success: function(d) {
                $('#overlay').remove();
                json_tabel(d);
                $('.panel-group').css('display','');
                $("#btn-search").removeClass('btn-warning');
		        $("#btn-search").addClass('btn-success');
		        $("#btn-search").children("i").addClass('fa-search');
		        $("#btn-search").children("i").removeClass('fa-refresh fa-spin');
            },
             error: function (request, status, error) {
                $('#overlay').remove();
                $("#btn-search").removeClass('btn-warning');
		        $("#btn-search").addClass('btn-success');
		        $("#btn-search").children("i").addClass('fa-search');
		        $("#btn-search").children("i").removeClass('fa-refresh fa-spin');
                showalert(request.responseText,'warning');
            },
             complete: function() {
        		$("#result-content").show();
             }
        });
        
  });
    
    function json_tabel(json){
        var j = 0 ;
        var color = '';
        var flight_key = [];
        $.each(json, function() {
            j++;
            data = this;
            var transit = 'Langsung';
            var display = '';
            if(data.flight_count > 1) transit = "Transit " + (parseInt(data.flight_count)-1);
            var tampilan = '<div data-time="'+data.time_depart+'" data-total="'+(data.fare+data.tax)+'" id="group-panel'+j+'" class="panel-group">'+
                                '<div class="panel panel-info ">'+
                                    '<div id="group'+j+'"><\/div>'+
                                    '<div style="margin:7px;">'+
                                    '<div class="col-md-1 col-xs-6 text-center"><label data-count="'+data.flight_count+'_'+j+'" class="tooltips label bg-green" >'+transit+'</label></div>'+
                                    '<div class="col-md-2 col-xs-6"> '+ 
									  '<div class=" text-center container-fare_'+j+'"><label>Rp <span class="tooltips-harga" title="Rp '+addCommas(data.fare)+'(fare) + Rp '+addCommas(data.tax)+'(tax)" id="total_'+j+'">'+addCommas(data.fare+data.tax)+'<\/span><\/label><\/div>'+
									'<\/div>'+
									'</div>'+
									'<div class="col-md-2 col-xs-12"> '+ 
									  '<button flight_key="'+data.flight_key+'" type="button" class="center-block btn-booking button-booking_'+j+' btn btn-flat btn-success btn-sm"><i class="fa fa-book"><\/i> | BOOKING<\/button>' +
									'<\/div>'+
                                    '<div class="row">'+
                                    '<\/div>'+
                                '<\/div>'+                           
                           '<\/div>' ;
            var tampilan2 = '';
            var time_depart = 'time_depart';
            var flights = '';
            $(tampilan).appendTo($(".list"));
           // $(".container-fare_"+j).hide();
            //$(".container-loading_"+j).hide();
            for (i = 0; i <= data.flight_count-1; i++) {
            	var button = '';
                color = 'bg-success';
                if(i%2 == 0){
                    color = 'bg-info';
                }
               
                if(i != 0) { display='display:none;';}
                tampilan2 = '<div class="panel-body '+' col-md-7 col-xs-12" style="'+display+'">'+
                                '<div class="col-md-6 text-center">'+
                                    '<h4 style="display:none"><span id="depart_'+j+'_'+i+'"><\/span> | <span class="'+time_depart+'" id="timedepart_'+j+'_'+i+'"><\/span> - <span id="arrive_'+j+'_'+i+'"><\/span> | <span id="timearrive_'+j+'_'+i+'"><\/span><\/h4>'+
                                    '<h4><span>'+data.area_depart+'<\/span> | <span>'+data.time_depart+'<\/span> - <span>'+data.area_arrive+'<\/span> | <span>'+data.time_arrive+'<\/span><\/h4>'+
                                '<\/div>'+
                                '<div id="image_'+j+'" class="col-md-6 col-xs-12 text-center">'+ 
                                               
                                '<\/div>'+
                           '<\/div>';
                flights = flights + '<label><img id="image_'+j+'_'+i+'" src="'+data.airline_icon+'" height="25" alt="" />&nbsp;<span id="flightid_'+j+'_'+i+'">'+data.segment[i].flight_id+'<\/span><\/label><br>';
                $(tampilan2).appendTo($("#group"+j));
                $('#depart_'+j+'_'+i).text(data.segment[i].area_depart);
                $('#arrive_'+j+'_'+i).text(data.segment[i].area_arrive);
                $('#timedepart_'+j+'_'+i).text(data.segment[i].time_depart);
                $('#timearrive_'+j+'_'+i).text(data.segment[i].time_arrive);
              //  $('#flightid_'+j+'_'+i).text(data.segment[i].flight_id);
               // $('#image_'+j+'_'+i).attr("src", data.airline_icon);
                time_depart = '';
            }
            $(flights).appendTo($("#image_"+j));
        });
        
        $('.btn-booking').on('click', function(){
            $("#h_flight_key").val($(this).attr('flight_key'));
			booking();
        });
        
        function booking(){
        	$('#booking').submit()
        }
        
        function disable(elemen,dis=true){
        	$(elemen).css("cursor", "wait");
			$(elemen).find('input, textarea, button, select, img, label').prop('disabled',true);
        	if(dis==false){
				$(elemen).css("cursor", "auto");
				$(elemen).find('input, textarea, button, select, img, label').prop('disabled',false);
			}
        }
        
		if($('#list').mixItUp('isLoaded')){
			$('#list').mixItUp('destroy');
		}        
        $('#list').mixItUp({
			  load: {
			  	sort: 'total:asc time:asc'
			  },
			  selectors: {
			    target: '.panel-group',
			    sort: '.sort-btn'
			  },
			  layout: {
				display: 'inherit'
			 },			 
			  callbacks: {
			    onMixEnd: function(state){
			      console.log(state)
			    }
			  }
		});
    }
    $('#from').on('change', function(){
    	$('#to').select2('open');
    });
    $('#to').on('change', function(){
    	$("#datepicker").datepicker("show");
    });
    $('#datepicker').on('change', function(){
    	$("#adult").focus();
    });
});
</script>