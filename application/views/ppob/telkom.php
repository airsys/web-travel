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
      <h3 class="box-title">Pembayaran Tagihan TELKOM</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form id="form" class="form-horizontal" action="" method="post">
      <div class="box-body">
      <div class="col-md-12">
        <div class="form-group">
            <label for="nomer" class="col-sm-2 control-label">Nomer</label>
            <div class="col-sm-4">
              <input type="number" required class="form-control" value="" name="nomer" id="nomer" onkeyup='saveValue(this);' >
            </div>
          </div>
          <div class="form-group">
            <label for="nominal" class="col-sm-2 control-label">Jumlah Tagihan (Rp)</label>
            <div class="col-sm-4">
              <input type="number" readonly required class="form-control" value="" name="nominalbayar" id="nominalbayar" placeholder=""  >
              <input type="hidden" required value="" name="harga_tagihan" id="harga_tagihan"  >
            </div>
          </div>
          <div class="form-group">
            <label for="contact" class="col-sm-2 control-label">Nama</label>
            <div class="col-sm-4">
              <input type="text" readonly required class="form-control" value="" name="nama" id="nama" placeholder=""  >
            </div>
          </div>
          <div class="form-group">
            <label for="contact" class="col-sm-2 control-label">No. Hp</label>
            <div class="col-sm-4">
              <input type="text" required class="form-control" value="" name="contact" id="contact" placeholder=""  >
            </div>
          </div>
          <div class="form-group">
            <label for="email" class="col-sm-2 control-label">Email (optional)</label>
            <div class="col-sm-4">
              <input type="text"  class="form-control" value="" name="email" id="email" placeholder=""  >
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
          <button id="btn-check" type="button" class="btn btn-primary "><i class="fa fa-paper-plane"></i> Check</button>
          <?php if($this->ion_auth->logged_in()){ ?>      
	          <button id="btn-submit" disabled type="submit" class="btn btn-success pull-right "><i class="fa fa-paper-plane"></i> Submit</button>
	      <?php } ?>
        </div>
       
      <?php if(!$this->ion_auth->logged_in()){ ?>
        <div class="col-sm-6">
          <a href="#" id="login-header" type="submit" class=" show-modal btn btn-success pull-right" 
          data-placement="top" data-toggle="popover" data-trigger="hover" data-content="You must login !" ><i class="fa fa-lock"></i> Submit</a>
        </div>
      <?php } ?>	  
      </div>
      <!-- /.box-footer -->
    </form>
  </div>
  <!-- /.box -->
  <script>
    $( document ).ready(function() {
            
    $("#form").on("submit", function(event) {      
        $("#btn-submit").removeClass('btn-success');
          $("#btn-submit").addClass('btn-warning');
          $("#btn-submit").attr('disabled',true);
          $("#btn-submit").children("i").removeClass('fa-paper-plane');
          $("#btn-submit").children("i").addClass('fa-refresh fa-spin');
          event.preventDefault(); 
          $.ajax({
              url:  base_url+"ppob/bayarTelkom",
              type: "post",
              data: $(this).serialize()+'&product='+products,
              success: function(d, textStatus, xhr) {
                  if(d.code!=0){
				  	showalert(d.message,'danger','#warn',60000000);
				  }else{
				  	showalert(d.message,'success','#warn',60000000);
				  }                 
                
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
    
    var products = '';
    get_products();	   
	function get_products(){
		$.get( base_url+'ppob/get_products', function(data) {
	        $.each(data, function(i, item) {
	        	if(item.kode == 'BAYAR.TELKOM'){
					products= item.id+'_'+item.FT;
					//console.log(products);
				}
	        });
	    });
	}
    
    $("#btn-check").on("click", function(event) {
    	  var mybutton =  $(this);
    	  //$("#warn").hide();
          mybutton.removeClass('btn-primary');
          mybutton.addClass('btn-warning');
          mybutton.attr('disabled',true);
          mybutton.children("i").removeClass('fa-paper-plane');
          mybutton.children("i").addClass('fa-refresh fa-spin');
          $("#btn-submit").attr('disabled',true);
          $.ajax({
              url: base_url+"ppob/cek_tagihan",
              type: "post",
              data: {
              	idpelanggan : $("#nomer").val(),
	            	product : products
              },
              success: function(d, textStatus, xhr) {
                  
                if(d.nama != null) {
                	$("#nominalbayar").val(d.harga);
	                $("#harga_tagihan").val(d.harga_tagihan);
	                $("#nama").val(d.nama);
                	$("#btn-submit").attr('disabled',false);
                	showalert(d.message,'success','#warn',690000000);
                }else{
					showalert(d.message,'danger','#warn',690000000);
				}
              },
               error: function (request, status, error) {
                  showalert('Terdapat ERROR','danger','#warn',690000000);
              },
              complete : function (request, status, error) {
              	 mybutton.addClass('btn-primary');
              	 mybutton.removeClass('btn-warning');
              	 mybutton.attr('disabled',false);
              	 mybutton.children("i").addClass('fa-paper-plane');
              	 mybutton.children("i").removeClass('fa-refresh fa-spin');
              }
          });          
    });
    
  });

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
  </script>