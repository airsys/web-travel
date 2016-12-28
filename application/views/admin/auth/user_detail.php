<?php 
 	$color = array(
 				'1'=>'#00bd30',
 				'0'=>'#ec1e23',
 			);
?>
<!-- Horizontal Form -->
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Profile User</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
      <div class="box-body">
      	<div class="form-horizontal" enctype="multipart/form-data" action="" method="post">
			<div class="form-group">
	          <label for="company" class="col-sm-2 control-label">Company</label>
	          <div class="col-sm-4">
	            <label class="control-label"><?= $data->brand ?></label>
	          </div>
	        </div>
			<div class="form-group">
	          <label for="email" class="col-sm-2 control-label">Email</label>
	          <div class="col-sm-4">
				<label class="control-label"><?= $data->email ?></label>
	          </div>
	        </div>
	        <div class="form-group">
	          <label for="first_name" class="col-sm-2 control-label">Full Name</label>
	          <div class="col-sm-4">
				<label class="control-label"><?= $data->full_name ?></label>
	          </div>
	        </div>
	        <div class="form-group">
	          <label for="phone" class="col-sm-2 control-label">Phone</label>
	          <div class="col-sm-4">
				<label class="control-label"><?= $data->phone ?></label>
	          </div>
	        </div>
	        <div class="form-group">
	          <label for="phone" class="col-sm-2 control-label">Register On</label>
	          <div class="col-sm-4">
				<label class="control-label"><?= $data->register_on ?></label>
	          </div>
	        </div>
	        <div class="form-group">
	          <label for="phone" class="col-sm-2 control-label">Last Login</label>
	          <div class="col-sm-4">
				<label class="control-label"><?= $data->last_login ?></label>
	          </div>
	        </div>
	        <div class="form-group">
	          <label for="phone" class="col-sm-2 control-label">Active</label>
	          <div class="col-sm-4">
				<label class="control-label"><?= $data->active ?></label>
	          </div>
	        </div>
	        <div class="form-group">
	          <label for="password_confirm" class="col-sm-2 control-label"></label>
			  <div class="col-sm-4">
			    <div class="pull-right">
	      			<a href="<?= base_url().'admin/auth/users' ?>" class="btn btn-info">Back</a>
	      		</div>	
			  </div>
			</div>   	
	    </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
      	
      </div>
      <!-- /.box-footer -->
  </div>
  <!-- /.box -->