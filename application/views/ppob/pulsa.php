<style>
	input[type="number"]::-webkit-outer-spin-button,
	input[type="number"]::-webkit-inner-spin-button {
	    -webkit-appearance: none;
	    margin: 0;
	}
	input[type="number"] {
	    -moz-appearance: textfield;
	}
</style>
<?php //print_r($data_post['first_name']); ?>
<!-- Horizontal Form -->
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Pembelian Pulsa</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form id="form" class="form-horizontal" action="" method="post">
      <div class="box-body">
    	<div class="col-md-12">
    		<div class="form-group">
	          <label for="nomer" class="col-sm-2 control-label">Nomer</label>
	          <div class="col-sm-4">
	            <input type="number" required class="form-control" value="" name="nomer" id="nomer" placeholder="081234567890">
	          </div>
	        </div>
			<div class="form-group">
	          <label for="nominal" class="col-sm-2 control-label">Nominal</label>
	          <div class="col-sm-4">
	            <select name="nominal" id="nominal" class="form-control" >
	            	<option value="">Isi Nomor terlebih dahulu</option>
	            </select>
	          </div>
	        </div>
	        <div class="form-group">
	          <label for="first_name" class="col-sm-2 control-label"></label>
	          <div class="col-sm-4">
		        <div id="warn"></div>
	          </div>
	        </div>
	        
        </div>
        <!-- /.col -->
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
      	<div class="col-sm-6">
	        <button id="btn-submit" type="submit" class="btn btn-success pull-right"><i class="fa fa-paper-plane"></i> Submit</button>
      	</div>
      </div>
      <!-- /.box-footer -->
    </form>
  </div>
  <!-- /.box -->
  <script>
  	$( document ).ready(function() {
  		var no_prefix = [] ;
	    $.get( base_url+'assets/ajax/no_prefix.json', function(data) {
	        $.each(data, function(i, item) {
	            no_prefix [item.number]= item;
	        });
	    });
  		
  		var key = '';
  		$("#nomer").on("keyup", function(event) {
  			var keytmp = $(this).val().substring(0,4);
  			if($(this).val().length > 3){  				
  				var op = '';
				if(key!=keytmp){
					key=keytmp;
					if(no_prefix[key]==null) op='pln'; else op = no_prefix[key].operator;
					$.get( base_url+'ppob/get_products/'+op, function(data) {
				        $("#nominal").html("");
				        $.each(data, function(i, item) {
				        	var nom = parseInt(item.nilai)+parseInt(item.markup)
				            $("#nominal").append($('<option>', {value: item.kode, text: item.operator.toUpperCase() +' - '+ item.nilai+' / '+ nom}));
				        });
				    });
				}
			} 
  		});
  		
  		$("#form").on("submit", function(event) {  			
	    	$("#btn-submit").removeClass('btn-success');
	        $("#btn-submit").addClass('btn-warning');
	        $("#btn-submit").attr('disabled',true);
	        $("#btn-submit").children("i").removeClass('fa-paper-plane');
	        $("#btn-submit").children("i").addClass('fa-refresh fa-spin');
	        event.preventDefault(); 
	        $.ajax({
	            url:  base_url+"ppob/bayar",
	            type: "post",
	            data: $(this).serialize(),
	            success: function(d, textStatus, xhr) {
	            	 	
	            	showalert(d.message,'success','#warn',60000000);
	            	
	            	$("#btn-submit").addClass('btn-success');
			        $("#btn-submit").removeClass('btn-warning');
			        $("#btn-submit").attr('disabled',false);
			        $("#btn-submit").children("i").addClass('fa-paper-plane');
			        $("#btn-submit").children("i").removeClass('fa-refresh fa-spin');
	            },
	             error: function (request, status, error) {
	                
	            }
	        });
	        
	  });
	});
  </script>