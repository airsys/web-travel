<script src="<?php echo base_url(); ?>/assets/plugins/mixitup/mixitup.js"></script>
<script src="<?php echo base_url(); ?>/assets/plugins/tooltip/tooltipster.bundle.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/plugins/tooltip/tooltipster.bundle.min.css" />
<style>
	.err {
    	color: #ff1313;
	}
</style>
<div class="box" id="cari">
	<form id="form" method="post" name="form">
		<div class="box-body" style="padding-left: 30px;padding-right: 60px;left: 6%;position: relative;">
			<div class="col-search3">
				<div class="form-group">
					<label>LEAVING FROM</label>
                    <div class="input-group ">
                        <div class="input-group-addon" style="background-color: #d2d6de;">
                            <i class="fa fa-map-marker"></i>
                        </div> 
                        <select class="form-control bandara" id='from' required name='from' style="width: 101%;" >
							<option value=""></option>
						</select>
                    </div>
				</div>
			</div>
			<div class="col-search3">
				<div class="form-group">
					<label>GOING TO</label>
                    <div class="input-group">
                        <div class="input-group-addon" style="background-color: #d2d6de;">
                            <i class="fa fa-map-marker"></i>
                        </div>
                                <select class="form-control bandara" required id='to' name='to' style="width: 101%;">
							         <option value=""></option>
						      </select>
                    </div>
				</div>
			</div>
			<div class="col-search2" style="padding-right: 0;margin-right: 0;">
				<div class="form-group">
					<label>DEPARTING ON</label>
					<div class="input-group" style="width: 101%;">
						<div class="input-group date">
							<div class="input-group-addon" style="background-color: #d2d6de;">
								<i class="fa fa-calendar"></i>
							</div><input class="form-control pull-right" id="datepicker" name='date' required type="text" 
                                        style="padding-right: 50px;background-color: white;">
						</div>
					</div>
				</div>
			</div>
			
           <div class="col-md-1" style="padding-right: 0;margin-right: 0;padding-left: 0;margin-left: 0;">
                <div class="form-group">
                  <label>PASSENGER</label>
                  <div class="input-group margin" style="margin: 0px 0px 0px 0px; width: 100%;">
                      <div class="input-group ">
                            <button type="button" class="btn btn-flat dropdown-toggle" data-toggle="dropdown" style="padding-right: 0px;padding-top: 0px;padding-bottom: 0px;padding-left: 0px;">
                              <i class="fa fa-user" style="margin-left: 10px;"></i>
                              <input type="text" class="" id="passanger" value="1" name="passanger" style="width: 70%;height: 32px; border-width: 0px 0px 0px 0px;text-align: center;margin-left: 10px;" readonly>
                              <input type="text" class="" id="triggerPass" value="1" name="triggerPass" style="width: 30px;height: 32px; border-width: 0px 0px 0px 0px; margin-left: 0px;text-align: center" hidden>
                            </button>
                                 <ul class="dropdown-menu" role="menu">
                                    <div class="countuser input-group" align="center"style="width:100%;">
                                            <label for="adult">Adult <strong>12+ th</strong></label><br>
                                            <input type="button" value="-" onClick="adultmin()" class="btn btn-flat btn-success" onFocus="startUser();" onBlur="stopUser();"/> 
                                            <input type="text" name="adult" size="5" id="adult" value="1" readonly
                                                    style="height: 35.22222px;
                                                            padding-top: 0px;
                                                            border-width: 0px 0px 0px 0px;
                                                            text-align: center;
                                                        "></td>
                                            <input type="button" value="+" onClick="adultplus()" class="btn btn-flat btn-success" onFocus="startUser();" onBlur="stopUser();" 
                                            data-toggle="popover" data-trigger="" data-timeout="2000" data-content="Jumlah Dewasa dan Anak tidak boleh melebihi 7"/> 

                                     </div>   
                                    <div class="countuser input-group" align="center"style="width:100%;">
                                            <label for="child">Children <strong></strong></label><br>
                                            <input type="button" value="-" onClick="childmin()" class="btn btn-flat btn-success" onFocus="startUser();" onBlur="stopUser();"/> 
                                            <input type="text" name="child" size="5" id="child" value="0" readonly
                                                     style="height: 35.22222px;
                                                            padding-top: 0px;
                                                            border-width: 0px 0px 0px 0px;
                                                            text-align: center;
                                                        "></td>
                                            <input type="button" value="+" onClick="childplus()" class="btn btn-flat btn-success" onFocus="startUser();" onBlur="stopUser();" 
                                            data-toggle="popover" data-trigger="" data-timeout="2000" data-content="Jumlah Dewasa dan Anak tidak boleh melebihi 7"/> 

                                    </div>
                                     <div class="countuser input-group" align="center"style="width:100%;">
                                        <label for="infant">Infant <strong></strong></label><br>
                                        <input type="button" value="-" onClick="infantmin()" class="btn btn-flat btn-success" onFocus="startUser();" onBlur="stopUser();"/> 
                                        <input type="text" name="infant" size="5" id="infant" value="0" readonly
                                                     style="height: 35.22222px;
                                                            padding-top: 0px;
                                                            border-width: 0px 0px 0px 0px;
                                                            text-align: center;
                                                        "></td>
                                        <input type="button" value="+" onClick="infantplus()" class="btn btn-flat btn-success"  onFocus="startUser();" onBlur="stopUser();" 
                                        data-toggle="popover" data-trigger="" data-timeout="1000" data-content="Jumlah bayi tidak boleh melebihi jumlah dewasa"/> 
                                    </div> 
                                </ul>
                      </div>
                  </div>
                </div>
            </div>
			<div class="col-search2">
				 <input type="hidden" name="tipe" id="tipe" value="all"/>
				 <div class="btn-group" style="margin-top: 25px;">
                 <!--smentara sriwijaya di tutup, search diarahkan ke lion-->
                 <!-- <button type="submit" id='btn-search' class="btn btn-success btn-flat btn-lg" style="height: 34px;padding-top: 0px;padding-bottom: 0px;"><i class="fa fa-search"></i> | SEARCH</button>-->
                 <a href="#" class="btn  btn-success btn-flat tipe" type="submit" data-type='lion' id='search-lion' ><i class="fa fa-search"></i> | SEARCH</a>
                  <button type="button" class="btn btn-success btn-flat dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  
                  <ul class="dropdown-menu" role="menu">
                    <li style="text-align: center" ><a href="#" class="btn btn-flat tipe" type="submit" data-type='lion' id='search-lion' >Lion Air</a></li>
                    <!--<li style="text-align: center" ><a href="#" class="btn btn-flat tipe" type="submit" data-type='sriwijaya' id='search-sriwijaya' >Sriwijaya Air</a></li>-->
                  </ul>
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
		<input id='h_airline' name='airline' type="hidden" value=''>
		<input id='h_flight_number' name='flight_number' type="hidden" value=''>
	</form><!-- /.box-footer-->
</div><!-- /.box -->
<div id="result-content" class="box box-primary center-block" style="width: 100%">
	<div class="box-header with-border">
	</div><!-- /.box-header -->
	<div class="box-body">
		<div id="alert"></div>
		<div id="result-id" class="result">
			<div class="box-title text-center">			
				<button id="sorttime" data-sort="time:asc total:asc" class="sort-btn btn btn-primary btn-xs" data-sort="time_depart">Depart time <i class="fa " aria-hidden="true"></i></button>
	    		<button id="sortprice" data-sort="total:asc time:asc" class="sort-btn btn btn-primary btn-xs" data-sort="total">Price <i class="fa " aria-hidden="true"></i></button>
			</div><br />
 			<div class="list center-block" style="max-width: 1000px;"></div>		
 		</div>
	</div>
</div><!-- /.box-body -->

<script>
$(document).ready(function(){
    //Date picker
    if (screen.width <=650) {
        $('#datepicker').datepicker({
           showOtherMonths: true,
           selectOtherMonths: true,
           dateFormat: 'dd-mm-yy', 
           minDate: 0,
           numberOfMonths: 1,
    });
    }else{
        $('#datepicker').datepicker({
           showOtherMonths: true,
           selectOtherMonths: true,
           dateFormat: 'dd-mm-yy', 
           minDate: 0,
           numberOfMonths: 2,
    	});
    };
    
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
    $('.bandara').on('change', function() {
	    $(this).valid();
	});
    
    $(".tipe").on('click',function(event) {
    	$("#tipe").val($(this).attr('data-type'));
    	$("#h_airline").val($(this).attr('data-type'));
     	if($("#form").valid()){			
    		coba(); 
		}
		$(".box-title").hide();
    });
    
    $("#btn-search").on('click',function(event) {
    	$("#tipe").val('all');
    	$(".box-title").hide();
    });
    
    
	$('form input').tooltipster({
        trigger: 'custom',
        position: 'bottom'
    });
    
    $('#form').validate({
	    rules: {
	        to: {
	            required: true
	        },
	        from: {
	            required: true
	        },
	        date: {
	            required: true
	        },
	        passanger:{
				min: 1, required: true
			}
	    },
	    errorElement: "span",
    	errorClass: "err",
	    errorPlacement: function(error, element) {
	    	error.insertAfter(element.parent());
		}	   
	});
    
    function coba(tipe2=''){
		var tipe = $("#tipe").val();
		//tipe2 = 'lion';
		var mylink = "airlines/get_bestprice/"+tipe2;
		if(tipe != 'all'){
			mylink = "airlines/search";
			$(".list").empty();
		}
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
        $.ajax({
            url:  base_url+mylink,
            type: "post",
            data: $('#form').serialize(),
            async: true,
            success: function(d) {
                $('#overlay').remove();
                if(tipe != 'all'){
					 json_tabel(d);
				} else{
					json_tabel2(d);
					$(".box-title").show();
					$('.fa').removeClass('fa-caret-down');
					$('.fa').removeClass('fa-caret-up');
				}
                
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
        		//setTimeout(coba('sriwijaya'), 3000)
             }
        });
	}
    
	$("#form").on('submit',function(event) {
        event.preventDefault();
		if($("#form").valid()){		
    		coba('lion');
    		coba('sriwijaya');
		}      
  	});
    
  
  $("#result-content").hide();

    function KelasGenerate(j,i,seat){
    	var array_kelas = {'promo':['U','O','R','X','V','T'],'ekonomi':['Q','N','M','L','K','H','B','S','W','G','A','Y'],'bisnis':['Z','I','D','J','C']};
        $.each( array_kelas, function( key, value ) {
           var group = $('<optgroup label="' + key + '" />');
                group.appendTo($('#kelas_'+j+'_'+i));
                for (x = 0; x < value.length; x++) {
                    if(seat[value[x]]!==undefined){
                        $($('#kelas_'+j+'_'+i))
                          .append($('<option value="'+seat[value[x]].flight_key+'">')
                          .text('class '+value[x]+' - '+seat[value[x]].available));
                    } else{
                    	$($('#kelas_'+j+'_'+i))
                          .append($('<option disabled value="">')
                          .text('class '+value[x]+' - 0'));
					}
                }
        }); 
    }
    
    function json_tabel(json){
        var j = 0 ;
        var color = '';
        var flight_key = [];
        $.each(json, function() {
            j++;
            data = this;
            var tampilan = '<div data-time="" id="group-panel'+j+'" class="panel-group">'+
                                '<div class="panel panel-info">'+
                                    '<div id="group'+j+'"><\/div>'+
                                    '<div class="row">'+
                                        '<div class="col-md-12">'+
                                            '<div class="col-md-10 col-sm-10 col-xs-12">'+
                                                '<div class ="pull-right container-loading_'+j+'" ><i class="fa fa-refresh fa-spin"></i> Loading<\/div>'+ 
                                                '<div class="pull-right container-fare_'+j+'">Rp <span id="fare_'+j+'"><\/span>(fare)+Rp <span id="tax_'+j+'"><\/span>(tax) <label>TOTAL = Rp <span id="total_'+j+'"> <\/span><\/label><\/div>'+
                                            '<\/div>'+                           
                                        '<button flight_number="" flight_key="" type="button" disabled class="btn-booking button-booking_'+j+' disabled col-md-2 col-sm-2 col-xs-12 btn btn-flat btn-default btn-sm"><i class="fa fa-book"><\/i> | BOOKING<\/button>'+
                                        '<\/div>'+
                                    '<\/div>'+
                                '<\/div>'+                           
                           '<\/div>' ;
            var tampilan2 = '';
            var flight_number = '';
            var time_depart = 'time_depart';
            $(tampilan).appendTo($(".list"));
            $(".container-fare_"+j).hide();
            $(".container-loading_"+j).hide();
            for (i = 1; i <= data.flight_count; i++) {
                color = 'bg-success';
                if(i%2 == 0){
                    color = 'bg-info';
                }
                tampilan2 = '<div class="panel-body '+color+'">'+
                                '<div class="col-md-6 text-center">'+
									'<h4><span id="depart_'+j+'_'+i+'"><\/span> | <span class="'+time_depart+'" id="timedepart_'+j+'_'+i+'"><\/span> - <span id="arrive_'+j+'_'+i+'"><\/span> | <span id="timearrive_'+j+'_'+i+'"><\/span><\/h4>'+
                                '<\/div>'+
                                '<div class="col-md-2 col-xs-6">'+ 
                                    '<label><img id="image_'+j+'_'+i+'" src="" height="36" alt="" />&nbsp;<span id="flightid_'+j+'_'+i+'"><\/span><\/label> '+                
                                '<\/div>'+
                                '<div class="col-md-4 col-xs-6"> '+
                                    '<select data="'+j+'_'+i+'_'+data.flight_count+'" id="kelas_'+j+'_'+i+'" class="kelas form-control" style="width: 100%;">'+
                                        '<option value="">Pilih kelas<\/option>'+
                                    '<\/select>'+            
                                '<\/div>'+
                           '<\/div>';
                $(tampilan2).appendTo($("#group"+j));
                $('#depart_'+j+'_'+i).text(data.segment[i].area_depart);
                $('#arrive_'+j+'_'+i).text(data.segment[i].area_arrive);
                $('#timedepart_'+j+'_'+i).text(data.segment[i].time_depart);
                $('#timearrive_'+j+'_'+i).text(data.segment[i].time_arrive);
                $('#flightid_'+j+'_'+i).text(data.segment[i].flight_id);
                $('#image_'+j+'_'+i).attr("src", data.segment[i].airline_icon);
                time_depart = '';
                KelasGenerate(j,i,data.segment[i].seat);
                flight_number = flight_number + data.segment[i].flight_id+',';
            	$(".button-booking_"+j).attr("flight_number", flight_number);
            	if(i==1) $('#group-panel'+j).attr("data-time", data.segment[i].time_depart);
            }
        });
        
        $('.kelas').on('change', function(){
            $("#h_flight_key").val('');
            var data = $(this).attr('data').split('_'); // 0.urutan 1.segmen 2.flight_count
            var flightcount = 0;
            for (x = 1; x <= data[2]; x++) {
                if($('#kelas_'+data[0]+'_'+x).val()!=''){
                    flight_key[x] = $('#kelas_'+data[0]+'_'+x).val();
                    flightcount++;
                    if(flightcount == data[2]){
                    	disable("#group-panel"+data[0]);
                        $(".container-loading_"+data[0]).show();
                        $.ajax({
                            url:  base_url+"airlines/get_fare",
                            type: "post",
                            data: {
                                key : flight_key,
                                tipe : $("#tipe").val()
                            },
                            success: function(d) {
                                disable("#group-panel"+data[0],false);
                                $('#fare_'+data[0]).text(addCommas(d.fare));
                                $('#tax_'+data[0]).text(addCommas(d.tax));
                                $('#total_'+data[0]).text(addCommas(d.fare+d.tax));
                                $(".container-fare_"+data[0]).show();
                                $(".container-loading_"+data[0]).hide();
                                $(".button-booking_"+data[0]).removeClass("disabled");
                                $(".button-booking_"+data[0]).removeClass("btn-default");
                                $(".button-booking_"+data[0]).addClass("btn-success");
                                $(".button-booking_"+data[0]).prop('disabled',false);
                                $(".button-booking_"+data[0]).attr("flight_key", d.flight_key);
                            },
                             error: function (request, status, error) {
                                disable("#group-panel"+data[0],false);
                                $(".button-booking_"+data[0]).addClass("btn-success");                                
                                $(".button-booking_"+data[0]).removeClass("btn-default");
                                showalert(request.responseText,'warning');
                                $(".container-loading_"+data[0]).hide();
                            }
                        });
                    }
                }
            }
        });
        
        $('.btn-booking').on('click', function(){
            $("#h_flight_key").val($(this).attr('flight_key'));
            $("#h_flight_number").val($(this).attr('flight_number'));
			booking();
        });
        
        function booking(){
        	$('#booking').submit()
        }
        
        function disable(elemen,dis){
			 if (typeof(dis)==='undefined') dis = true;
        	$(elemen).css("cursor", "wait");
			$(elemen).find('input, textarea, button, select, img, label').prop('disabled',true);
        	if(dis==false){
				$(elemen).css("cursor", "auto");
				$(elemen).find('input, textarea, button, select, img, label').prop('disabled',false);
			}
        }
        
        if($('.list').mixItUp('isLoaded')){
			$('.list').mixItUp('destroy');
		}        
        $('.list').mixItUp({
			  load: {
			  	sort: 'time:asc'
			  },
			  selectors: {
			    target: '.panel-group',
			    sort: '.sort-btn'
			  },
			  layout: {
				display: 'inherit'
			 }
		});
    }
	
	function json_tabel2(json){
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
                                    '<div class="col-md-8 col-xs-8">'+
                                    	'<div id="group'+j+'">'+
                                    		'<div id="image_'+j+'" class="col-md-2 col-xs-6"><\/div>'+
                                    		'<div class="col-md-4 col-xs-6"><label data-count="'+data.flight_count+'_'+j+'" class="tooltips label bg-green" >'+transit+'</label></div>'+
                                    	'</div>'+
                                    '<\/div>'+
                                	
									'<div class="col-md-4 col-xs-4"> '+
									  '<div class="text-center container-fare_'+j+'"><label>Rp <span class="tooltips-harga" title="Rp '+addCommas(data.fare)+'(fare) + Rp '+addCommas(data.tax)+'(tax)" id="total_'+j+'">'+addCommas(data.fare+data.tax)+'<\/span><\/label><\/div>'+
									  '<button flight_number="" flight_key="'+data.flight_key+'" type="button" class="center-block btn-booking button-booking_'+j+' btn btn-flat btn-success btn-sm"><i class="fa fa-book"><\/i> | BOOKING<\/button>' +
									'<\/div>'+
                                    '<div class="row">'+
                                    '<\/div>'+
                                '<\/div>'+                           
                           '<\/div>' ;
            var tampilan2 = '';
            var flight_number = '';
            var time_depart = 'time_depart';
            var flightid = 'flightid';
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
                tampilan2 = '<div class="col-md-6 col-xs-12" style="'+display+'">'+
                                '<div class="">'+
                                    '<h5 style="display:none"><span id="depart_'+j+'_'+i+'"><\/span> | <span class="'+time_depart+'" id="timedepart_'+j+'_'+i+'"><\/span> - <span id="arrive_'+j+'_'+i+'"><\/span> | <span id="timearrive_'+j+'_'+i+'"><\/span><\/h5>'+
                                    '<h5 style=""><span>'+data.area_depart+'<\/span> | <span>'+data.time_depart+'<\/span> - <span>'+data.area_arrive+'<\/span> | <span>'+data.time_arrive+'<\/span><\/h5>'+
                                '<\/div>'+
                           '<\/div>';
                flights = flights + '<label><img id="image_'+j+'_'+i+'" src="'+data.airline_icon+'" height="25" alt="" />&nbsp;<span class="'+flightid+'" id="flightid_'+j+'_'+i+'"><\/span><\/label>';
                $(tampilan2).appendTo($("#group"+j));
                $('#depart_'+j+'_'+i).text(data.segment[i].area_depart);
                $('#arrive_'+j+'_'+i).text(data.segment[i].area_arrive);
                $('#timedepart_'+j+'_'+i).text(data.segment[i].time_depart);
                $('#timearrive_'+j+'_'+i).text(data.segment[i].time_arrive);
                flight_number = flight_number + data.segment[i].flight_id+',';
            	$(".button-booking_"+j).attr("flight_number", flight_number);
                time_depart = ''; flightid='';
            }
            $(flights).appendTo($("#image_"+j));
        });
        
        $('.btn-booking').on('click', function(){
            $("#h_flight_key").val($(this).attr('flight_key'));
            $("#h_flight_number").val($(this).attr('flight_number'));
			booking();
        });
        
        function booking(){
        	$('#booking').submit();
        }
        
        function disable(elemen,dis){
			if (typeof(dis)==='undefined') a = true;
			$(elemen).css("cursor", "wait");
			$(elemen).find('input, textarea, button, select, img, label').prop('disabled',true);
			if(dis==false){
				$(elemen).css("cursor", "auto");
				$(elemen).find('input, textarea, button, select, img, label').prop('disabled',false);
			}
        }
		
        if($('.list').mixItUp('isLoaded')){
			$('.list').mixItUp('destroy');
		}        
        $('.list').mixItUp({
			  load: {
			  	sort: 'total:asc time:asc'
			  },
			  selectors: {
			    target: '.panel-group',
			    sort: '.sort-btn'
			  },
			  layout: {
				display: 'inherit'
			 }
		});
    }
	
	$('.sort-btn').on('click', function(){
		$('.sort-btn').find('.fa').removeClass('fa-caret-up');
		$('.sort-btn').find('.fa').removeClass('fa-caret-down');
		var val= $(this).attr("data-sort")
		
		if($(this).attr("data-sort").indexOf("asc")>1){			
			$(this).attr("data-sort", val.replace(/asc/gi,"desc"))
			$(this).find('.fa').addClass('fa-caret-up');
		}else{
			$(this).attr("data-sort", val.replace(/desc/gi,"asc"))
			$(this).find('.fa').addClass('fa-caret-down');
		}
		$(this).removeClass('active');
    });
	
    $('#from').on('change', function(){
    	$('#to').select2('open');
    });
    $('#to').on('change', function(){
    	$("#datepicker").datepicker("show");
    });
    $('#datepicker').on('change', function(){
    	$("#adult").focus();
    	$(this).valid();
    });    
});

/*passanger*/
function adultplus() {
        var adult = parseFloat(document.form.adult.value);
        var triggerPass = parseFloat(document.form.triggerPass.value);
        if (triggerPass <=6) {
            var countadult=adult+1;
            form.adult.value=countadult
        }else {
           $('[data-toggle="popover"]').popover();
        };
    }
function adultmin() {
        var adult = parseFloat(document.form.adult.value);
        var countadult=adult-1;
        if (countadult >=0) {
            form.adult.value=countadult
        };
    }

function childplus() {
        var child = parseFloat(document.form.child.value);
        var triggerPass = parseFloat(document.form.triggerPass.value);
        if (triggerPass <=6) {
            var countchild=child+1;
            form.child.value=countchild
        }else {
           $('[data-toggle="popover"]').popover();
        };
    }
function childmin() {
        var child = parseFloat(document.form.child.value);
        var countchild=child-1;
        if (countchild >= 0) {
            form.child.value=countchild
        };
    }
function infantplus() {
        var infant = parseFloat(document.form.infant.value);
        var adult = parseFloat(document.form.adult.value);
        if (infant < adult) {
             var countinfant=infant+1;
            form.infant.value=countinfant
        }else {
            $('[data-toggle="popover"]').popover();
        };
    }
function infantmin() {
        var infant = parseFloat(document.form.infant.value);
        var countinfant=infant-1;
            if (countinfant >=0) {
        form.infant.value=countinfant
    };
    }
function startUser(){
    interval=setInterval("user()",1)
  }
function user(){
    adult=document.form.adult.value;
    child=document.form.child.value;
    infant=document.form.infant.value;
    document.form.passanger.value=(adult*1)+(child*1)+(infant*1)
    document.form.triggerPass.value=(adult*1)+(child*1)
  }
function stopUser(){
      clearInterval(interval)
    }

$('[data-toggle="popover"][data-timeout]').on('shown.bs.popover', function() {
                this_popover = $(this);
                setTimeout(function () {
                    this_popover.popover('hide');
                }, $(this).data("timeout"));
            });
  </script> 