
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/select2.css" />
<link rel="stylesheet" type="text/css" type="text/css" href="<?php echo base_url() ?>assets/css/toaster.css" media="screen" />

<div class="search-box-wrapper">
    <div class="search-box container">
        <ul class="search-tabs clearfix">
            <li class="active"><a href="#flights-tab" data-toggle="tab">FLIGHTS</a></li>
        </ul>
        <div class="visible-mobile">
            <ul id="mobile-search-tabs" class="search-tabs clearfix">
                <li class="active"><a href="#flights-tab">FLIGHTS</a></li>
            </ul>
        </div>
        
        <div class="search-tab-content" id='cari'>
            <div class="tab-pane fade active in" id="flights-tab">
                <form id='form'  method="post">
                    <div class="row">
                        <div class="col-md-4">
                            <h4 class="title">Where</h4>
                            <div class="form-group">
                                <label>Leaving From</label>
                                <div class="">
									<select name='from' id="e1" class="full-width">
									<option value=''>Pilih Tujuan</option>
									</select>
								</div>
                            </div>
                            <div class="form-group">
                                <label>Going To</label>
								 <div class="">
									<select name='to' id="e2" class="full-width">
									<option value=''>Pilih Keberangkatan</option>
									</select>
								</div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <h4 class="title">When</h4>
								
							<div class="col-md-6 pull-right">
								<label>Round trip</label>
								<div class="selector">
									<select id='search_type' name='search_type' class="full-width">
										<option value="0">TIDAK</option>
										<option value="1">YA</option>
									</select>
								</div>
							</div>
                            <label>Departing On</label>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <div class="datepicker-wrap">
                                        <input required name='date' type="text" class="input-text full-width" placeholder="dd-mm-yyyy" />
                                    </div>
                                </div>
                            </div>
                            <label>Arriving On</label>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <div id='retdate_div' class="datepicker-wrap">
                                        <input disabled name='ret_date' type="text" class="input-text full-width" placeholder="dd-mm-yyyy" />
                                    </div>
									<div id='retdate_div2' class="">
                                        <input disabled type="text" class="input-text full-width" placeholder="dd-mm-yyyy" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <h4 class="title">Who</h4>
                            <div class="form-group row">
                                <div class="col-xs-3">
                                    <label>Adults</label>
                                    <div class="selector">
                                        <select id='adult' name='adult' class="full-width">
                                            <option value="1">01</option>
                                            <option value="2">02</option>
                                            <option value="3">03</option>
                                            <option value="4">04</option>
                                            <option value="5">05</option>
                                            <option value="6">06</option>
                                            <option value="7">07</option>
                                            <option value="8">08</option>
                                            <option value="9">09</option>
                                            <option value="10">10</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <label>Kids</label>
                                    <div class="selector">
                                        <select id='child'  name='child' class="full-width">
                                            <option value="0">00</option>
                                            <option value="1">01</option>
                                            <option value="1">01</option>
                                            <option value="2">02</option>
                                            <option value="3">03</option>
                                            <option value="4">04</option>
                                            <option value="5">05</option>
                                            <option value="6">06</option>
                                            <option value="7">07</option>
                                            <option value="8">08</option>
                                            <option value="9">09</option>
                                            <option value="10">10</option>
                                        </select>
                                    </div>
                                </div>
								<div class="col-xs-3">
                                    <label>Infants</label>
                                    <div class="selector">
                                        <select id='infant' name='infant' class="full-width">
                                            <option value="0">00</option>
                                            <option value="1">01</option>
                                            <option value="2">02</option>
                                            <option value="3">03</option>
                                            <option value="4">04</option>
                                        </select>
                                    </div>
                                </div>
								<div class="col-xs-6">
									<br><br>
                                    <button id='search_flight' class="full-width icon-check">SEARCH NOW</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container sections">
	<div class="blog-infinite">
		<div class="post">
			<div class="post-content-wrapper">
			 	<br />
					<div id='pesan'></div>
					<div class="table-responsive" id='result'>
						<table class="table table-bordered" id='tabel_result'>
						 <thead>
						  <tr>
							<th rowspan="2">MASKAPAI</th>
							<th rowspan="2">BERANGKAT</th>
							<th rowspan="2">TUJUAN</th>
							<th colspan="5">Business</th>
							<th colspan="12">Economy</th>
							<th colspan="6">Promo</th>
						  </tr>
						  <tr>
							<th>C</th>
							<th>J</th>
							<th>D</th>
							<th>I</th>
							<th>Z</th>
							<th>Y</th>
							<th>A</th>
							<th>G</th>
							<th>W</th>
							<th>S</th>
							<th>B</th>
							<th>H</th>
							<th>K</th>
							<th>L</th>
							<th>M</th>
							<th>N</th>
							<th>Q</th>
							<th>T</th>
							<th>V</th>
							<th>X</th>
							<th>R</th>
							<th>O</th>
							<th>U</th>
						  </tr>
						  </thead>
						  <tbody>
						  <tr>
							<td>NOterbang</td>
							<td>dari<br>(mulai)</td>
							<td>ke<br>(sampai)</td>
							<td>C</td>
							<td>J</td>
							<td>D</td>
							<td>I</td>
							<td>Z</td>
							<td>Y</td>
							<td>A</td>
							<td>G</td>
							<td>W</td>
							<td>S</td>
							<td>B</td>
							<td>H</td>
							<td>K</td>
							<td>L</td>
							<td>M</td>
							<td>N</td>
							<td>Q</td>
							<td>T</td>
							<td>V</td>
							<td>X</td>
							<td>R</td>
							<td>O</td>
							<td>U</td>
						  </tr>
						  </tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
</div>
<div id='hidden'>
	
</div>

<script type="text/javascript" src="<?php echo base_url() ?>assets/js/select2.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/js/toaster.js"></script>

<script type="text/javascript">		
    tjq(document).ready(function($) {
		$("#retdate_div").hide();
		$("#e1").select2();
		$("#e2").select2();
		
		var bandara = [] ;
		$.get( base_url()+'assets/ajax/iata_bandara.json', function(data) {
			$.each(data, function(i, item) {
				bandara [item.code_route]= item.city + ' ' + item.name_airport ;
				$("#e1").append($('<option>', {value: item.code_route, text: item.code_route +' - '+ item.city + ' ' + item.name_airport}));
				$("#e2").append($('<option>', {value: item.code_route, text: item.code_route +' - '+ item.city + ' ' + item.name_airport}));
			})
		});
		
		 $("#form").on("submit", function(event) {
				$(over).appendTo("#cari");
                event.preventDefault(); 
                $.ajax({
                    url:  base_url()+"Lion/search",
                    type: "post",
                    data: $(this).serialize(),
                    success: function(d) {
						$('#overlay').remove();
						json_tabel(d);
                    },
					 error: function (request, status, error) {
						$('#overlay').remove();
						toastpesan(request.responseText);
					}
                });
				
          });
		  
		   $("#search_type").on("change", function(event) {
				if($(this).val()==1){
					$("#retdate_div").show();
					$("#retdate_div2").hide();
				} else{
					$("#retdate_div").hide();
					$("#retdate_div2").show();
				}
          });
			
			$(".berangkat").live('click', function(){
				getfare(this);
				//maketoast ($(this).val());
			});
		
		function toastpesan(pesan){
			toastr.options = {
			  "closeButton": true,
			  "debug": false,
			  "newestOnTop": false,
			  "progressBar": false,
			  "positionClass": "toast-bottom-center",
			  "preventDuplicates": false,
			  "onclick": null,
			  "showDuration": "300",
			  "hideDuration": "1000",
			  "timeOut": "5000",
			  "extendedTimeOut": "1000",
			  "showEasing": "swing",
			  "hideEasing": "linear",
			  "showMethod": "fadeIn",
			  "hideMethod": "fadeOut"
			}
			toastr["info"](pesan);
		}
			  
		function maketoast (fare, tax, key, urutan)
		{		
			toastr.options = {
			  "closeButton": true,
			  "debug": false,
			  "newestOnTop": false,
			  "progressBar": false,
			  "positionClass": "toast-bottom-full-width",
			  "preventDuplicates": false,
			  "onclick": null,
			  "showDuration": "30000",
			  "hideDuration": "1000",
			  "timeOut": 0,
			  "extendedTimeOut": 0,
			  "showEasing": "swing",
			  "hideEasing": "linear",
			  "showMethod": "fadeIn",
			  "hideMethod": "fadeOut",
			  "tapToDismiss": false
			}
			
			var isi = 'total harga untuk: <span style="color:blue">'+$('#adult').val()+' adult, '+$('#child').val()+' child, '+$('#infant').val()+' infant </span><br>'+
					  'fare = IDR '+addCommas(fare)+' | tax = IDR '+addCommas(tax)+' | <br><span style="color:blue">TOTAL = IDR '+addCommas((tax+fare)) + '</span>';
			var isi2 = '<div class="col-md-6"> <div class="col-md-6">'+isi+'</div><div class="col-md-6"><button type="button" id="booking" class="btn">BOOK</button></div></div>';
			//toastr["warning"]('<div class="col-md-6"> <div class="col-md-6">'+isi+'</div><div class="col-md-6"><button type="button" id="booking" class="btn">BOOK</button></div></div>');
			 var $toast = toastr["warning"](isi2);
			 if ($toast.find('#booking').length) {
                $toast.delegate('#booking', 'click', function () {
                    booking(urutan);
                });
            }
		}
		
		  function getfare(kursi){
		  	var codekursi = [];
		  	var flightcount = 0;
		  	var urutan = $(kursi).attr('data-kursi').split('_'); //1 urutan, 2 segmen, 3 flightcount, 4 seat
		  	$('input:radio:not(.urutan'+urutan[1]+')').removeProp('checked');
		  	for (i = 1; i <= urutan[3]; i++) {
		  		if ($('input[name=berangkat_'+i+''+urutan[1]+']:checked').length > 0) { 
		  			//console.log('segmen');
		  			codekursi[i] = $('input[name=berangkat_'+i+''+urutan[1]+']:checked').val();
		  			$('#urutan'+urutan[1]).find('input[name="seat['+urutan[2]+']"]').val(urutan[4]);
		  			$('#urutan'+urutan[1]).find('input[name="key['+urutan[2]+']"]').val($('input[name=berangkat_'+i+''+urutan[1]+']:checked').val());
		  			flightcount ++;
		  			//console.log(i+'*'+$('input[name=berangkat_'+i+''+urutan[1]+']:checked').val());
		  		}
			}
			if(flightcount==urutan[3]){
				
				$(over).appendTo("#result");
		  		$.ajax({
                    url:  base_url()+"Lion/get_fare",
                    type: "post",
                    data: {
                    	key : codekursi
                    },
                    success: function(d) {
						$('#overlay').remove();
						maketoast(d.fare,d.tax,codekursi, urutan[1]);
						
                    },
					 error: function (request, status, error) {
						$('#overlay').remove();
						toastpesan(request.responseText);
					}
                })
			}
		  }
		  
		  function json_tabel(json){
			var myjson = json;
			var data ;
			var rowspan = '' ;
			var kelas = '' ;
			var color = '' ;
			var j = 0 ;
			var datakursi = '';
			var array_kelas = ['C','J','D','I','Z','Y','A','G','W','S','B','H','K','L','M','N','Q','T','V','X','R','O','U'];
			
			$('#tabel_result > tbody:last-child').empty();
			$('#hidden').empty();
			
			$.each(myjson, function() {
				j++;
				color = ' class="info" ';
				if(j%2 == 0){
					color = ' class="success" ';
				}
				//var t = makeid();
				$('#tabel_result > tbody:last-child').append('<tr><td height="1" colspan=26></td></tr>');
				data = this;
				datakursi = '<form id="urutan'+j+'">';
				for (i = 1; i <= data.flight_count; i++) {
					kelas = '';
					$.each(array_kelas, function() {
						if(data.segment[i].seat[this]===undefined){
							kelas += '<td></td>';
						} else {
							kelas += '<td><input type="radio" data-kursi="kursi_'+j+'_'+i+'_'+data.flight_count+'_'+this+'" class="berangkat '+'segmen'+i+' '+'urutan'+j+'" name="berangkat_'+i+''+j+'" value="'+data.segment[i].seat[this].flight_key+'"></br>'+data.segment[i].seat[this].available+'</td>';
						}
						
					});
					datakursi += 
					  '<input type="hidden" value="'+data.segment[i].airline_icon+'" name=img['+i+']  />'+
					  '<input type="hidden" value="'+data.segment[i].flight_id+'" name=flightid['+i+'] />'+
					  '<input type="hidden" value="'+data.segment[i].area_depart+'" name=area_depart['+i+'] />'+
					  '<input type="hidden" value="'+data.segment[i].time_depart+'" name=time_depart['+i+'] />'+
					  '<input type="hidden" value="'+data.segment[i].area_arrive+'" name=area_arrive['+i+'] />'+
					  '<input type="hidden" value="'+data.segment[i].time_arrive+'" name=time_arrive['+i+'] />'+
					  '<input type="hidden" value="" name=seat['+i+'] />'+
					  '<input type="hidden" value="" name=key['+i+'] />';
					$('#tabel_result > tbody:last-child').append(
															'<tr'+color+'>'+
															  '<td><img src="'+data.segment[i].airline_icon+'" height="32" width="32"><br>'+data.segment[i].flight_id+'<br></td>'+
															  '<td>'+data.segment[i].area_depart+'<br>'+data.segment[i].time_depart+'<br>'+bandara[data.segment[i].area_depart]+'</td>'+
															  '<td>'+data.segment[i].area_arrive+'<br>'+data.segment[i].time_arrive+'<br>'+bandara[data.segment[i].area_arrive]+'</td>'+
															   kelas +
															'</tr>'
														   );
					}
					datakursi += '</form>';
					$(datakursi).appendTo("#hidden");
					
				});
			}
			
		  function booking(urutan){
		  	window.location = base_url()+"Lion/booking?"+$('#urutan'+urutan).serialize()+'&'+$('#form').serialize();
		  	//console.log(base_url()+"Lion/booking?"+$('#urutan'+urutan).serialize()+$('#form').serialize());
		  }
    });
	
</script>
