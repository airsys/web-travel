<?php //print_r($data_post['first_name']); ?>
<!-- Horizontal Form -->
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Topup</h3>
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
	            <input type="text" required class="form-control" value="" name="idpelanggan" id="idpelanggan" placeholder="No. Rek">
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
  		$('#ppob_alert').hide();
  		$("#form").on("submit", function(event) {  			
	    	$("#btn-submit").removeClass('btn-success');
	        $("#btn-submit").addClass('btn-warning');
	        $("#btn-submit").attr('disabled',true);
	        $("#btn-submit").children("i").removeClass('fa-paper-plane');
	        $("#btn-submit").children("i").addClass('fa-refresh fa-spin');
	        event.preventDefault(); 
	        $.ajax({
	            url:  base_url+"ppob/cek_tagihan",
	            type: "get",
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