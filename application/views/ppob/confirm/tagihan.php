<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/iCheck/all.css" />
<script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.min.js"></script>
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
      <h3 class="box-title">Konfirmasi Pembayaran <?= $costumer['jenis'] ?></h3>
    </div>
    <!-- /.box-header -->
    <?php 
    	if(preg_match("/^[a-zA-Z]+$/",$costumer['harga_tagihan'])||!empty($costumer['harga_tagihan'])||$costumer['harga_tagihan'] > 1000){ ?>
    <!-- form start -->
    	<form id="form" class="form-horizontal" action="confirm" method="post" novalidate>
	      <div class="box-body" id="beli">
	    	<div class="col-md-12">
	    		<div class="form-group">
		          <label for="first_name" class="col-sm-2 control-label">Nomor Pelanggan</label>
		          <div class="col-sm-4">
			        <h4 class=""><?= wordwrap($idpelanggan , 3 , ' ' , true ) ?></h4>
		          </div>
		        </div>
		        <div class="form-group">
		          <label for="first_name" class="col-sm-2 control-label">Atas Nama</label>
		          <div class="col-sm-4">
			        <h4 class=""><?= $costumer['nama'] ?></h4>
		          </div>
		        </div>
		        <div class="form-group">
		          <label for="first_name" class="col-sm-2 control-label">Produk</label>
		          <div class="col-sm-4">
			        <h4 class=""><strong><?= $kode ?></strong></h4>
		          </div>
		        </div>
		        <div class="form-group">
		          <label for="first_name" class="col-sm-2 control-label">Tagihan</label>
		          <div class="col-sm-4">
			        <h4 class="">Rp <strong id='harga'><?= number_format($costumer['harga_tagihan']) ?></strong></h4>
		          </div>
		        </div>
		        <div class="form-group">
		          <label for="first_name" class="col-sm-2 control-label">Biaya Admin</label>
		          <div class="col-sm-4">
			        <h4 class="">Rp <strong id='harga'><?= number_format($costumer['harga_konsumen'] - $costumer['harga_tagihan']) ?></strong></h4>
		          </div>
		        </div>
		        <div class="form-group">
		          <label for="first_name" class="col-sm-2 control-label">Total Pembayaran</label>
		          <div class="col-sm-4">
			        <h4 class="">Rp <strong id='harga'><?= number_format($costumer['harga_konsumen']) ?></strong></h4>
		          </div>
		        </div>
		        <div class="form-group">
		        	<label for="contact" class="col-sm-2 control-label">No. Hp Pelanggan</label>
		            <div class="col-sm-4">
		              <input type="text" required class="form-control" value="" name="contact" id="contact" placeholder=""  >
		            </div>
		        </div>
		        <div class="form-group">
		            <label for="email" class="col-sm-2 control-label">Email Pelanggan (optional)</label>
		            <div class="col-sm-4">
		              <input type="text"  class="form-control" value="" name="email_pelanggan" id="email_pelanggan" placeholder=""  >
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
				
				<div id="register">
					<div class="form-group">
			          <label for="first_name" class="col-md-2 control-label"></label>
			          <div class="col-md-4">
			          	 <p class="text-danger">Register to continue</p>
			          </div>
			        </div>
					<div class="form-group">
			          <label for="first_name" class="col-sm-2 control-label">Full Name</label>
			          <div class="col-sm-4">
			            <input type="text" required class="form-control" value="" name="full_name" id="first_name" placeholder="Full Name">
			          </div>
			        </div>
			        <div class="form-group">
			          <label for="email" class="col-sm-2 control-label">Email</label>
			          <div class="col-sm-4">
			            <input type="text" required class="form-control" value="" name="email" id="email" placeholder="email">
			          </div>
			        </div>
			        <div class="form-group">
			          <label for="phone" class="col-sm-2 control-label">Phone</label>
			          <div class="col-sm-4">
			            <input type="text" required class="form-control" value="" name="phone" id="phone" placeholder="phone">
			          </div>
			        </div>
			        <div class="form-group">
			          <label for="company" class="col-sm-2 control-label">Company</label>
			          <div class="col-sm-4">
			            <input type="text" required class="form-control" value="" name="company" id="company" placeholder="company">
			          </div>
			        </div>
			        <div class="form-group">
			          <label for="password" class="col-sm-2 control-label">Password</label>
			          <div class="col-sm-4">
			            <input type="password"  required class="form-control" name="password_register" id="password_register" placeholder="Password">
			          </div>
			        </div><div class="form-group">
			          <label for="password_confirm" class="col-sm-2 control-label">Password Confirm</label>
			          <div class="col-sm-4">
			            <input type="password" required  class="form-control" name="password_confirm" id="password_confirm" placeholder="password confirm">
			          </div>
			        </div>
				</div>
				
				<div class="form-group">
		          <label for="password" class="col-sm-2 control-label"></label>
		          <div class="col-sm-4">
		          	<div class="form-group has-feedback">
		          		<label>
					      <input type="radio" value="lo" name="position" id="position" class="flat-red" checked>
					      Login &nbsp;&nbsp;&nbsp;
					    </label>
					    <label>
					      <input type="radio" value="re" name="position" id="position" class="flat-red" >
					      Register
					    </label>
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
			        <?= $costumer['message'] ?>
			    </div>
			</div>
		 </div>
	      <div class="box-footer">
	      	<input type="hidden" name="harga_tagihan" value="<?= $costumer['harga_tagihan'] ?>" />
	      	<input type="hidden" name="nama" value="<?= $costumer['nama'] ?>" />
	      	<input type="hidden" name="nomer" value="<?= $idpelanggan ?>" />
	      	<input type="hidden" name="product" value="<?= $product ?>" />
	        <div class="col-md-6">
	          <button id="btn-submit" type="submit" class="btn btn-success pull-right"><i class="fa fa-money"></i> BAYAR</button>
	          <a href="javascript: window.history.go(-1)" id="btn-back" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> BACK</a>
	          <a href="" id="btn-detail" class="btn btn-primary pull-right hide"><i class="fa fa-eye"></i> DETAIL</a>
	        </div>
	      </div>
     	</form>
     	<?php } else{ ?>
     		<div class="box-body">
     			<div id="warn">
		          <div id="alertdiv" class="col-md-6 alert alert-danger">
		              <p>Terdapat kesalahan server</p><p>coba beberapa saat lagi !</p>
		              <?= $costumer['message'] ?>
		          </div>
		        </div>
     		</div>
     		<div class="box-footer">
	           <a href="javascript: window.history.go(-1)" id="btn-back" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> BACK</a>
 			</div>
     	<?php } ?>
   </div>
  <!-- /.box -->
  <script>
  	$( document ).ready(function() {
  		$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
	      checkboxClass: 'icheckbox_flat-green',
	      radioClass: 'iradio_flat-green'
	    });
	    
	    $('#login').show();
		$('#register').hide();
		
		$('input[type=radio][name=position]').on('ifToggled', function(event){
	    	if(this.value=='lo'){
	    		$('#login').show();
				$('#register').hide();
				$('#register').find('input[type=text],input[type=password],input[type=email]').prop('required',false);
				$('#login').find('input[type=text],input[type=password],input[type=email]').prop('required',true);
			}else{
				$('#login').hide();
				$('#register').show();
				$('#register').find('input[type=text],input[type=password],input[type=email]').prop('required',true);
				$('#login').find('input[type=text],input[type=password],input[type=email]').prop('required',false);
			}
		});
		
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
		            url:  base_url+"ppob/bayar_tagihan",
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