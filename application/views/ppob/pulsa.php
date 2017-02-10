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
 <?php if($this->ion_auth->logged_in()){ ?>
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
		            <input type="text" required class="form-control" value="<?php if($_GET){ echo $_GET['nomer'];} ?>" name="nomer" id="nomer" placeholder="08XXX">
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
	          <button id="btn-submit" type="submit" class="btn btn-success pull-right "><i class="fa fa-paper-plane"></i> Submit</button>
	        </div>
	      </div>
     	</form>
   </div>
      <?php } ?>
      <?php if(!$this->ion_auth->logged_in()){ ?>
      <div class="box box-info">
		    <div class="box-header with-border">
		      <h3 class="box-title">Pembelian Pulsa</h3>
		    </div>
		   
		    <!-- form start belum login -->
		    <form id="form_f" class="form-horizontal" action="" method="get">
		      <div class="box-body">
		    	<div class="col-md-12">
		    		<div class="form-group">
			          <label for="nomer" class="col-sm-2 control-label">Nomer</label>
			          <div class="col-sm-4">
			            <input type="text" required class="form-control" value="<?php if($_GET){ echo $_GET['nomer'];} ?>" name="nomer" id="nomer" placeholder="08XXX" autofocus>
			          </div>
			        </div>
					<div class="form-group">
			          <label for="nominal" class="col-sm-2 control-label">Nominal</label>
			          <div class="col-sm-4">
			            <select name="nominal" id="nominal" class="form-control" >
			            	<option value="" >Isi Nomor terlebih dahulu</option>
			            </select>
			         
			          </div>
			        </div>
			         <div class="form-group">
		          <label for="first_name" class="col-sm-2 control-label"></label>
		          <div class="col-sm-4">
			        <div id="warnlogin"></div>
		          </div>
		        </div>
			        <div class="form-group">
			          <label for="first_name" class="col-sm-2 control-label"></label>
			          <div class="col-sm-4">
				        <div id="warn"></div>
				        <!--<a href="#" id="btn-login" type="submit" class=" show-modal btn btn-warning pull-left"><i class="fa fa-lock"></i> Login</a>-->
			          </div>
			        </div>
			        
		        </div>
		        <!-- /.col -->
		      </div>
	      <!-- /.box-body -->
	      <div class="box-footer">
	        <div class="col-sm-6">
	        	<input type="submit" name="submit" value="submit" id="pra_login" class="btn btn-success pull-right">
	         	
	        </div>
	      </div>
	     
	      <!-- /.box-footer -->
	    </form>
  	</div>
   <?php } ?>
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
	  			get_number();
	  		});
	  		/*
	  		$("#nomer").on("mousemove", function(event) {
	  			get_number();
	      	});
			*/
  			function getUrlVars() {
			    var vars = {};
			    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,    
			    function(m,key,value) {
			      vars[key] = value;
			    });
			    return vars;
			  }

	  		 function get_number(){
				var keytmp = $("#nomer").val().substring(0,4);
		        if($("#nomer").val().length > 3){         
		          var op = '';
		        if(key!=keytmp){
		          key=keytmp;
		          if(no_prefix[key]==null) {
		          	$("#nominal").html("");
		          	$("#nominal").append($('<option>', {value: "", text: "Masukan nomer dengan benar"}));
		          } else{ 
		          	$.get( base_url+'ppob/get_products/'+no_prefix[key].operator, function(data) {
		                $("#nominal").html("");
		                $.each(data, function(i, item) {
		                  var nom = parseInt(item.nilai)+parseInt(item.markup)
		                    $("#nominal").append($('<option>', {value: item.kode, text: item.operator.toUpperCase() +' - '+ item.nilai+' / '+ nom}));
		                });
		            });	          
		          }
		        }
		      }
		     <?php 
		  		if (get('nominal')!=NULL) 
		  		echo "
		  		setTimeout(function() {
		  		$('#nominal').val(getUrlVars()['nominal']).trigger('change'); 
		  		}, 5000);
				
		  		";
  		 	?>
			} 
			<?php 
		  		if (get('nominal')!=NULL) 
		  		echo "
		  		setTimeout(function() {
		  		get_number();
		  		$('#nominal').val(getUrlVars()['nominal']).trigger('change'); 
		  		}, 500);
				
		  		";
  		 	?>
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
		
		$("#pra_login").on("click", function(event) {
		event.preventDefault();
		$('#modal-content').modal('show');
		var url = window.location.href;	
		var nomer=document.getElementById("nomer").value;
		var nominal=document.getElementById("nominal").value;
		window.history.pushState('obj', 'newtitle', base_url+'ppob/pulsa?nomer='+nomer+'&nominal='+nominal);
		});

		$(window).load(function () {
  		setTimeout(function() {
		  		get_number();
		  		$('#nominal').val(getUrlVars()['nominal']).trigger('change'); 
		  		}, 500);
		});


	});
/*	
document.getElementById("nomer").value = getSavedValue("nomer");
function saveValue(e){
            var id = e.id;  
            var val = e.value; 
            localStorage.setItem(id, val);
        }
function getSavedValue  (v){
            if (localStorage.getItem(v) === null) {
                return "";
            }
            return localStorage.getItem(v);
        }
 */
  </script>