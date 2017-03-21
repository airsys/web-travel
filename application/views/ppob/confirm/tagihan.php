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
	.form-horizontal .form-group {
	     margin-right: -5px; 
	     margin-left: -5px; 
	}
</style>
<!-- Horizontal Form -->
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Konfirmasi Pembayaran <?= $costumer['jenis'] ?></h3>
    </div>
    <!-- /.box-header -->
    <?php 
    	if($data_pulsa['code'] == 1 || $data_pulsa==NULL){
    	if(preg_match("/^[a-zA-Z]+$/",$costumer['harga_tagihan'])||!empty($costumer['harga_tagihan'])||$costumer['harga_tagihan'] > 1000){ ?>
    <!-- form start -->
    	<form id="form" class="form-horizontal" action="<?= base_url() ?>ppob/bayar_tagihan" method="post" novalidate>
	      <div class="box-body" id="beli">
	    	<div class="col-md-12">
	    		<div class="form-group">
		          <label for="first_name" class="col-sm-2 control-label">Nomor Pelanggan</label>
		          <div class="col-sm-4">
			        <span class="form-control" style="border: 0"><?= wordwrap($idpelanggan , 3 , ' ' , true ) ?></span>
		          </div>
		        </div>
		        <div class="form-group">
		          <label for="first_name" class="col-sm-2 control-label">Atas Nama</label>
		          <div class="col-sm-4">
			        <span class="form-control" style="border: 0"><?= $costumer['nama'] ?></span>
		          </div>
		        </div>
		        <div class="form-group">
		          <label for="first_name" class="col-sm-2 control-label">Produk</label>
		          <div class="col-sm-4">
			        <span class="form-control" style="border: 0"><strong><?= $kode ?></strong></span>
		          </div>
		        </div>
		        <div class="form-group">
		          <label for="first_name" class="col-sm-2 control-label">Tagihan</label>
		          <div class="col-sm-4">
			        <span class="form-control" style="border: 0">Rp <strong id='harga'><?= number_format($costumer['harga_tagihan']) ?></strong></span>
		          </div>
		        </div>
		        <div class="form-group">
		          <label for="first_name" class="col-sm-2 control-label">Biaya Admin</label>
		          <div class="col-sm-4">
			        <span class="form-control" style="border: 0">Rp <strong id='harga'><?= number_format($costumer['harga_konsumen'] - $costumer['harga_tagihan']) ?></strong></span>
		          </div>
		        </div>
		        <div class="form-group">
		          <label for="first_name" class="col-sm-2 control-label">Total Pembayaran</label>
		          <div class="col-sm-4">
			        <span class="form-control" style="border: 0">Rp <strong id='harga'><?= number_format($costumer['harga_konsumen']) ?></strong></span>
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
			        <?php if($data_pulsa != NULL){
		          		if($data_pulsa['code']==1){
							echo "<div id='alertdiv' class='alert alert-danger'><i class='close' data-dismiss='alert'>X</i><span>$data_pulsa[message]</span></div>";	
						}
					} ?>
		          </div>
		        </div>
		        <?php if(!$this->ion_auth->logged_in()){ ?>
		        <div id="login"  style="background-color:#f3f3f3">
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
				
				<div id="register"  style="background-color:#f3f3f3">
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
			        </div>
			        <div class="form-group">
			          <label for="password_confirm" class="col-sm-2 control-label">Password Confirm</label>
			          <div class="col-sm-4">
			            <input type="password" required  class="form-control" name="password_confirm" id="password_confirm" placeholder="password confirm">
			          </div>
			        </div>
			        <div class="form-group"></div>
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
		 
	      <div class="box-footer">
	      	<input type="hidden" name="harga_tagihan" value="<?= $costumer['harga_tagihan'] ?>" />
	      	<input type="hidden" name="nama" value="<?= $costumer['nama'] ?>" />
	      	<input type="hidden" name="nomer" value="<?= $idpelanggan ?>" />
	      	<input type="hidden" name="product" value="<?= $product ?>" />
	        <div class="col-md-6">
	          <button id="btn-submit" type="submit" class="btn btn-success pull-right"><i class="fa fa-money"></i> BAYAR</button>
	          <a href="<?= base_url().'ppob/tagihan/'; ?>" id="btn-back" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> BACK</a>
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
	           <a href="<?= base_url().'ppob/tagihan/'; ?>" id="btn-back" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> BACK</a>
 			</div>
     	<?php } 
     	} //data_pulsa
     	?>
     	
     	<?php 
		  	if($data_pulsa != NULL){
		  		if($data_pulsa['code']==0){ ?>	      
		  <div id="sukses" class="box-body">
				<div class="col-md-6">
					<div class="alert alert-info alert-dismissible">
				        <h4><i class="icon fa fa-info"></i> Pembayaran Success</h4>
				        <?= $costumer['message'] ?>
				    </div>
				</div>
			 </div>		 
		  <div class="box-footer">
		    <div class="col-md-6">
		      <a href="<?= base_url().'ppob/tagihan/'; ?>" id="btn-back" class="btn btn-default"><i class="fa fa-arrow-circle-left"></i> BACK</a>
		      <a href="<?= base_url().'ppob/finance/'.$data_pulsa['id']; ?>"" id="btn-detail" class="btn btn-primary pull-right"><i class="fa fa-eye"></i> DETAIL</a>
		    </div>
		  </div>	 
	   <?php } } ?>
     	
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
		
		$("#btn-submit").on("click", function(event) {
  			event.preventDefault(); 
				if($("#form").valid()){					
		    	$("#btn-submit").removeClass('btn-success');
		        $("#btn-submit").addClass('btn-warning');
		        $("#btn-submit").attr('disabled',true);
		        $("#btn-submit").children("i").removeClass('fa-money');
		        $("#btn-submit").children("i").addClass('fa-refresh fa-spin');
		        
		        $("#form").submit();
		   	} //form validation
  			
	   });
	  
	});
  </script>