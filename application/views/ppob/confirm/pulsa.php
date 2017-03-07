<style>
	.row{
		padding-bottom: 0px;
		padding-top: 0px;
	}
	.control-label{
		padding-bottom: 0px;
		padding-top: 20px;
	}
</style>
<!-- Horizontal Form -->
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Konfirmasi Pembelian Pulsa</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    	<form id="form" class="form-horizontal" action="confirm" method="post">
	      <div class="box-body" id="beli">
	    	<div class="col-md-12">
	    		<div class="form-group">
		          <label for="first_name" class="col-sm-2 control-label">Nomor HP</label>
		          <div class="col-sm-4">
			        <h4 class=""><?= wordwrap($nomer , 3 , ' ' , true ) ?></h4>
		          </div>
		        </div>
		        <div class="form-group">
		          <label for="first_name" class="col-sm-2 control-label">Produk</label>
		          <div class="col-sm-4">
			        <h4 class=""><strong><?= $kode.'000' ?></strong></h4>
		          </div>
		        </div>
		        <div class="form-group">
		          <label for="first_name" class="col-sm-2 control-label">Total Bayar</label>
		          <div class="col-sm-4">
			        <h4 class="">Rp <strong id='harga'><?= number_format($price) ?></strong></h4>
		          </div>
		        </div>
		        <div class="form-group">
		          <label for="first_name" class="col-sm-1 control-label"></label>
		          <div class="col-sm-5">
			        <div id="warn"></div>
		          </div>
		        </div>
		        <?php if(!$this->ion_auth->logged_in()){ ?>
		        <div id="login">
			        <div class="form-group">
			          <label for="first_name" class="col-md-2 control-label"></label>
			          <div class="col-md-4">
			          	 <p class="text-danger">Sign in to continue</p>
			          </div>
			        </div>
			        <div class="form-group">
			          <label for="email" class="col-sm-2 control-label">Email</label>
			          <div class="col-sm-4">
			          	<div class="form-group has-feedback">
					        <input required type="email" name="identity" class="form-control" placeholder="Email">
					        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
					      </div>
			          </div>
			        </div>
			        <div class="form-group">
			          <label for="password" class="col-sm-2 control-label">Password</label>
			          <div class="col-sm-4">
			          	<div class="form-group has-feedback">
					        <input required type="password" name="password" class="form-control" placeholder="Password">
					        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
					    </div>
			          </div>
			        </div>
		        </div>
		        <?php } ?>
	        </div>
	        <!-- /.col -->
	      </div>
	      <!-- /.box-body -->  
	      
	      <div id="sukses" class="box-body hide">
			<div class="col-md-6">
				<div class="alert alert-info alert-dismissible">
			        <h4><i class="icon fa fa-info"></i> Pembayaran Success</h4>
			        Pembelian pulsa <strong><?= $kode.'000' ?></strong><br>
			        ke nomor <strong><?= $nomer ?></strong>
			    </div>
			</div>
		 </div>
	      <div class="box-footer">
	      	<input type="hidden" name="nominal" value="<?= $nominal ?>" />
	      	<input type="hidden" name="nomer" value="<?= $nomer ?>" />
	        <div class="col-md-6">
	          <button id="btn-submit" type="submit" class="btn btn-success pull-right"><i class="fa fa-money"></i> BAYAR</button>
	          <a href="javascript: window.history.go(-1)" id="btn-back" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> BACK</a>
	          <a href="" id="btn-detail" class="btn btn-primary pull-right hide"><i class="fa fa-eye"></i> DETAIL</a>
	        </div>
	      </div>
     	</form>
   </div>
  <!-- /.box -->
  <script>
  	$( document ).ready(function() {
  	    var s=false;
  		$("#form").on("submit", function(event) {
  			event.preventDefault(); 
  			if(s){
				alert();
			}else{
				if($("#form").valid()){
		    	$("#btn-submit").removeClass('btn-success');
		        $("#btn-submit").addClass('btn-warning');
		        $("#btn-submit").attr('disabled',true);
		        $("#btn-submit").children("i").removeClass('fa-money');
		        $("#btn-submit").children("i").addClass('fa-refresh fa-spin');
		        $.ajax({
		            url:  base_url+"ppob/bayar",
		            type: "post",
		            data: $(this).serialize(),
		            success: function(d, textStatus, xhr) {
		            	if(d.code == 0){
							//showalert(d.message,'success','#warn',60000000);
							$("#beli").fadeOut();
							$("#sukses").fadeIn();
							$("#sukses").removeClass("hide");
							s = true;
							$("#btn-submit").hide();
							$("#btn-detail").removeClass('hide');
							$("#btn-detail").prop("href", base_url+"ppob/finance/"+d.id+'/confirm')
							
						}else{
							showalert(d.message,'danger','#warn',60000000);
							$("#btn-submit").addClass('btn-success');
					        $("#btn-submit").removeClass('btn-warning');
					        $("#btn-submit").attr('disabled',false);
					        $("#btn-submit").children("i").addClass('fa-money');
					        $("#btn-submit").children("i").removeClass('fa-refresh fa-spin');
						}
						if(d.login==1){
							$("#login").html('');
							$("#login").hide();
						}
		            },
		             error: function (request, status, error) {
		             	showalert(request.message,'danger','#warn',60000000);
		                $("#btn-submit").addClass('btn-success');
				        $("#btn-submit").removeClass('btn-warning');
				        $("#btn-submit").attr('disabled',false);
				        $("#btn-submit").children("i").addClass('fa-money');
				        $("#btn-submit").children("i").removeClass('fa-refresh fa-spin');
		            }
		        });
		   		} //form validation
			}
  			
	  });
	  
	});
  </script>