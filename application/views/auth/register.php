<?php //print_r($data_post['first_name']); ?>
<!-- Horizontal Form -->
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Register Form</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" action="" method="post">
      <div class="box-body">      	
    <?php if($message != '') { ?>
    	<div class="alert alert-warning col-md-12"><a class="close" data-dismiss="alert">x</a><span><?php echo $message; ?></span></div>
    <?php } ?>
        <div class="form-group">
          <label for="first_name" class="col-sm-2 control-label">Full Name</label>
          <div class="col-sm-4">
            <input type="text" required class="form-control" value="<?php echo ($data_post != NULL) ? $data_post['full_name'] : ""; ?>" name="full_name" id="first_name" placeholder="Full Name">
          </div>
        </div>
        <div class="form-group">
          <label for="email" class="col-sm-2 control-label">Email</label>
          <div class="col-sm-4">
            <input type="text" required class="form-control" value="<?php echo ($data_post != NULL) ? $data_post['email'] : ""; ?>" name="email" id="email" placeholder="email">
          </div>
        </div>
        <div class="form-group">
          <label for="phone" class="col-sm-2 control-label">Phone</label>
          <div class="col-sm-4">
            <input type="text" required class="form-control" value="<?php echo ($data_post != NULL) ? $data_post['phone'] : ""; ?>" name="phone" id="phone" placeholder="phone">
          </div>
        </div>
        <div class="form-group">
          <label for="password" class="col-sm-2 control-label">Password</label>
          <div class="col-sm-4">
            <input type="password"  required class="form-control" name="password" id="company" placeholder="Password">
          </div>
        </div><div class="form-group">
          <label for="password_confirm" class="col-sm-2 control-label">Password Confirm</label>
          <div class="col-sm-4">
            <input type="password" required  class="form-control" name="password_confirm" id="password_confirm" placeholder="password confirm">
          </div>
        </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
      	<div class="col-sm-6">
	        <button type="reset" class="btn btn-default">Cancel</button>
	        <button type="submit" class="btn btn-info pull-right">Sign in</button>
      	</div>
      </div>
      <!-- /.box-footer -->
    </form>
  </div>
  <!-- /.box -->
  <script>
  	$( document ).ready(function() {	    
  		$("[data-mask]").inputmask();
	});
  </script>