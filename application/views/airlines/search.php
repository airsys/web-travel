<div class="box" id="cari">
	<div class="box-header with-border">
	</div>
	<form id="form" method="post" name="form">
		<div class="box-body" style="padding-left: 30px;padding-right: 60px;left: 6%;position: relative;">
			<div class="col-search3">
				<div class="form-group">
					<label>LEAVING FROM</label>
                    <div class="input-group ">
                        <div class="input-group-addon" style="background-color: #d2d6de;">
                            <i class="fa fa-map-marker"></i>
                        </div> 
                        <select class="form-control bandara" id='from' name='from' style="width: 101%;" >
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
                                <select class="form-control bandara" id='to' name='to' style="width: 101%;">
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
							</div><input class="form-control pull-right" id="datepicker" name='date' required="" type="text" readonly="readonly" 
                                        style="padding-right: 50px;background-color: white;">
						</div>
					</div>
				</div>
			</div>
			<!-- <div class="col-search2" style="padding: 0;margin: 0;">
				<div class="col-search4">
					<label>ADULT</label>
					<div class="input-group">
						<select class="form-control" id='adult' name='adult' style="width: 100%;">
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
				<div class="col-search4">
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
				<div class="col-search4">
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
			</div> -->
           <div class="col-md-1" style="padding-right: 0;margin-right: 0;padding-left: 0;margin-left: 0;">
                <div class="form-group">
                    <label>PASSENGER</label>
                    <div class="input-group margin" style="margin: 0px 0px 0px 0px; width: 50%;">
                    <i class="fa fa-user" style="
                                                padding-top: 10px;
                                                width: 35px;
                                                height: 34px;
                                                border-top-width: 1px;
                                                padding-left: 11px;
                                                background-color: #d2d6de;"> <!--passanger -->
                    </i> 
                    <div class="input-group-btn ">
                            <button type="button" class="btn btn-flat dropdown-toggle" data-toggle="dropdown" style="padding-right: 0px;padding-top: 0px;padding-bottom: 0px;padding-left: 0px;">
                                
                                <input type="text" class="" id="passanger" value="1" name="passanger" style="width: 71px;height: 32px; border-width: 0px 0px 0px 0px;text-align: center" readonly>
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
				<div class="input-group" style="margin-top: 25px;">
				<button id='btn-search' class="btn btn-flat btn-success btn-lg" style="height: 34px;padding-top: 0px;padding-bottom: 0px;">
                    <i class="fa fa-search"></i> | SEARCH</button>
				</div>
			</div>
		</div><!-- /.box-body -->
		<div class="box-footer">
			
		</div>
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
		<div class="result"></div>
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
        $(".result").empty();
        $.ajax({
            url:  base_url+"airlines/search",
            type: "post",
            data: $(this).serialize(),
            success: function(d) {
                $('#overlay').remove();
                json_tabel(d);
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
            var tampilan = '<div id="group-panel'+j+'" class="panel-group">'+
                                '<div class="panel panel-info">'+
                                    '<div id="group'+j+'"><\/div>'+
                                    '<div class="row">'+
                                        '<div class="col-md-12">'+
                                            '<div class="col-md-10 col-sm-10 col-xs-12">'+
                                                '<div class ="pull-right container-loading_'+j+'" ><i class="fa fa-refresh fa-spin"></i> Loading<\/div>'+ 
                                                '<div class="pull-right container-fare_'+j+'">Rp <span id="fare_'+j+'"><\/span>(fare)+Rp <span id="tax_'+j+'"><\/span>(tax) <label>TOTAL = Rp <span id="total_'+j+'"> <\/span><\/label><\/div>'+
                                            '<\/div>'+                           
                                        '<button flight_key="" type="button" disabled class="btn-booking button-booking_'+j+' disabled col-md-2 col-sm-2 col-xs-12 btn btn-flat btn-default btn-sm"><i class="fa fa-book"><\/i> | BOOKING<\/button>'+
                                        '<\/div>'+
                                    '<\/div>'+
                                '<\/div>'+                           
                           '<\/div>' ;
            var tampilan2 = '';
            $(tampilan).appendTo($(".result"));
            $(".container-fare_"+j).hide();
            $(".container-loading_"+j).hide();
            for (i = 1; i <= data.flight_count; i++) {
                color = 'bg-success';
                if(i%2 == 0){
                    color = 'bg-info';
                }
                tampilan2 = '<div class="panel-body '+color+'">'+
                                '<div class="col-md-6 text-center">'+
                                    '<h4><span id="depart_'+j+'_'+i+'"><\/span> | <span id="timedepart_'+j+'_'+i+'"><\/span> - <span id="arrive_'+j+'_'+i+'"><\/span> | <span id="timearrive_'+j+'_'+i+'"><\/span><\/h4>'+
                                '<\/div>'+
                                '<div class="col-md-3 col-xs-6">'+ 
                                    '<label><img id="image_'+j+'_'+i+'" src="" height="36" alt="" />&nbsp;<span id="flightid_'+j+'_'+i+'"><\/span><\/label> '+                
                                '<\/div>'+
                                '<div class="col-md-2 col-xs-6"> '+
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
                
                KelasGenerate(j,i,data.segment[i].seat);
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
                                key : flight_key
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
                                showalert(error,'warning');
                                $(".container-loading_"+data[0]).hide();
                            }
                        });
                    }
                }
            }
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