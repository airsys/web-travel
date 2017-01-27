<?php //print_r($data_post['first_name']); ?>
<!-- Horizontal Form -->
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Cek Tagihan</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form id="form" class="form-horizontal" action="" method="post">
      <div class="box-body">
    	<div class="col-md-12">
			<div class="form-group">
	          <label for="email" class="col-sm-2 control-label">Operator</label>
	          <div class="col-sm-4">
	            <select name="oprcode" id="oprcode" class="form-control" >
	            	<option value="CEK.PLN">PLN</option>
	            	<option value="CEK.TELKOM">TELKOM</option>
	            </select>
	          </div>
	        </div>
	        <div class="form-group">
	          <label for="first_name" class="col-sm-2 control-label">Rek. ID</label>
	          <div class="col-sm-4">
	            <input type="text" required class="form-control" value="" name="idpelanggan" id="idpelanggan" placeholder="No. Rek" onkeyup='saveValue(this);'>
	          </div>
	        </div>
	        <div class="form-group">
	          <label for="first_name" class="col-sm-2 control-label"></label>
	          <div class="col-sm-4">
		        <div id="warn"></div>
		       <a href="<?php echo base_url().'ppob/telkom' ?>" id="btn-payment" type="button" class="btn btn-flat btn-primary pull-left"><i class="fa fa-paper-plane"></i> Payment</a>
	          	
	          </div>
	        </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        <div class="col-sm-6">
          <button id="btn-submit" type="submit" class="btn btn-flat btn-success pull-right "><i class="fa fa-paper-plane"></i> Submit</button>
        </div>
      </div>
     
      <!-- /.box-footer -->
    </form>
  </div>
  <!-- /.box -->
   <!-- Modal 
  <div id="modal-telkom-payment" class="modal fade modal-default" id="" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <p class="modal-title" id="">Payment</p>
        </div>-->
            <!-- form start
          <form id="form-telkom-payment" action="" method="post" role="form">
          <div class="modal-body">
                <div class="box-body">
                  	<label for="nomer" class="control-label">ID Number</label>
		            <div class="form-group">
		              <input type="number" required class="form-control" value="value" name="idpelanggan" id="idpelanggan" placeholder="" onkeyup='saveValue(this);' autofocus>
		            </div>
                 	<div class="form-group">
                  		<label for="nominal" class="control-label">Total Payment (Rp)</label>
			            <div class="form-group">
			              <input type="number" required class="form-control" value="" name="nominal" id="nominal" placeholder=""  >
			            </div>
              		</div>
              		<div class="form-group">
			            <label for="informasi" class="control-label">Email OR Mobile Number</label>
			            <div class="form-group">
			              <input type="text" required class="form-control" value="" name="informasi" id="informasi" placeholder=""  >
			            </div>
         			</div>
                </div>
                  <div class="form-group">
		            <label for="first_name" class="control-label"></label>
		            <div class="">
		              <div id="warnmodal"></div>
		            </div>
		          
          </div> -->
                <!-- /.box-body 
       </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
            <button id="btn-submit" type="submit" class="btn btn-flat btn-dafault"><i class="fa fa-paper-plane"></i> Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>-->
  <script>
  	$( document ).ready(function() {
  		$('#ppob_alert').hide();
  		$('#btn-payment').hide();
  		$("#form").on("submit", function(event) {  			
	    	$("#btn-submit").removeClass('btn-success');
	        $("#btn-submit").addClass('btn-warning');
	        $("#btn-submit").attr('disabled',true);
	        $("#btn-submit").children("i").removeClass('fa-paper-plane');
	        $("#btn-submit").children("i").addClass('fa-refresh fa-spin');
	        event.preventDefault(); 
	        $.ajax({
	            url:  base_url+"ppob/cek_tagihan",
	            type: "post",
	            data: $(this).serialize(),
	            success: function(d, textStatus, xhr) {
	            	 	
	            	showalert(d.message,'success','#warn',60000000);

	            	$('#btn-payment').show();
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
		/*
      $("#form-telkom-payment").on("submit", function(event) {       
        $("#btn-submit").removeClass('btn-success');
          $("#btn-submit").addClass('btn-warning');
          $("#btn-submit").attr('disabled',true);
          $("#btn-submit").children("i").removeClass('fa-paper-plane');
          $("#btn-submit").children("i").addClass('fa-refresh fa-spin');
          event.preventDefault(); 
          $.ajax({
              url:  base_url+"ppob/bayarTelkom",
              type: "post",
              data: $(this).serialize(),
              success: function(d, textStatus, xhr) {
                  
                showalert(d.message,'info','#warnmodal',60000000);
                
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
	 $('.show-modal-payment').on('click', function() {
          $('#modal-telkom-payment').modal('show');
          
      });*/
});

   

document.getElementById("idpelanggan").value = getSavedValue("idpelanggan");
function saveValue(e){
            var id = e.id;  
            var val = e.value; 
            localStorage.setItem(id, val);
        }
function getSavedValue(v){
            if (localStorage.getItem(v) === null) {
                return "";
            }
            return localStorage.getItem(v);
        }

  </script>