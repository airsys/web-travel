<div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">PPOB MENU</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body text-center">
      <p>Click menu below</p>
      <a id="pulsa" class="btn btn-app bg-black">
        <i class="fa fa-mobile"></i> PULSA HP
      </a>
      <a id="plnpra" class="btn btn-app bg-yellow">
        <i class="fa fa-bolt"></i> PLN Prabayar
      </a>
      <a id="telkom" class="btn btn-app bg-red">
        <i class="fa fa-phone"></i> TELKOM
      </a><br>
      <!--
      <a class="btn btn-app ">
        <i class="fa fa-bolt"></i> PLN Pascabayar
      </a>
      <a class="btn btn-app ">
        <i class="fa fa-gamepad"></i> Voucher Game
      </a>
      <a class="btn btn-app ">
        <i class="fa fa-medkit"></i> BPJS
      </a> -->
    </div>
</div>
 <!-- /.box -->
 <script>
 	$("#pulsa").on("click", function(event) { 
 		window.open(base_url+"ppob/pulsa","_self");
 	});
 	$("#plnpra").on("click", function(event) { 
 		window.open(base_url+"ppob/pln","_self");
 	});
 	$("#telkom").on("click", function(event) { 
 		window.open(base_url+"ppob/telkom","_self");
 	});
 	
 	var path = window.location.hash.split('#')[1];
 	
 	$(document).ready(function(){
 		if(path=='login'){
			$('#modal-content').modal('show');
		}
 	});
 	
 </script>