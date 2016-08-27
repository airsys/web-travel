<?php //print_r($data_post['first_name']); ?>
<!-- Horizontal Form -->
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Profile User</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" action="" method="post">
      <div class="box-body">      	
    <?php if($message != '') { ?>
    	<div class="alert alert-warning col-md-12"><a class="close" data-dismiss="alert">x</a><span><?php echo $message; ?></span></div>
    <?php } ?>
    	<div class="col-md-12">
          <!-- Custom Tabs -->
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab_1" data-toggle="tab">Profile</a></li>
              <li><a href="#tab_2" data-toggle="tab">Bank Account</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_1">
				<div class="form-group">
		          <label for="email" class="col-sm-2 control-label">Email</label>
		          <div class="col-sm-4">
		            <input type="email" disabled class="form-control" value="<?php echo ($data_post != NULL) ? $data_post->email : ""; ?>" id="email" placeholder="email">
		          </div>
		        </div>
		        <div class="form-group">
		          <label for="first_name" class="col-sm-2 control-label">Full Name</label>
		          <div class="col-sm-4">
		            <input type="text" required disabled class="form-control" value="<?php echo ($data_post != NULL) ? $data_post->full_name : ""; ?>" name="full_name" id="first_name" placeholder="Full Name">
		          </div>
		        </div>
		        <div class="form-group">
		          <label for="phone" class="col-sm-2 control-label">Phone</label>
		          <div class="col-sm-4">
		            <input type="text" required disabled class="form-control" value="<?php echo ($data_post != NULL) ? $data_post->phone : ""; ?>" name="phone" id="phone" placeholder="phone">
		          </div>
		        </div>
		        <div class="form-group">
		          <label for="password" class="col-sm-2 control-label">Password (optional)</label>
		          <div class="col-sm-4">
		            <input type="password" disabled class="form-control" name="password" id="company" placeholder="Password">
		          </div>
		        </div>
		        <div class="form-group">
		          <label for="password_confirm" class="col-sm-2 control-label">Password Confirm (optional)</label>
		          <div class="col-sm-4">
		            <input type="password" disabled  class="form-control" name="password_confirm" id="password_confirm" placeholder="password confirm">
		          </div>
		        </div>
		        <div class="form-group">
		          <label for="password_confirm" class="col-sm-2 control-label"></label>
				  <div class="col-sm-4"">
				    
				  </div>
				</div>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="tab_2">
               <div style="background-color: #f4f4f4" class="row">
	                <div class="col-md-6">
		              <div class="form-group">
		                <label>No. Rekening</label>
		                <input type="text" name="rek_number" class="form-control" />
		              </div>
		              <!-- /.form-group -->
	                </div>
	            	<div class="col-md-2">
		              <div class="form-group">
		                <label>No. Rekening</label>
		                <select class="form-control" style="width: 100%;">
		                  <option>BRI</option>
		                  <option>BCA</option>
		                  <option>MANDIRI</option>
		                  <option>BNI</option>
		                </select>
		              </div>
		              <!-- /.form-group -->
		            </div>
	            </div>
	            
	          </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
      	<div class="col-sm-6">
      		<div class="pull-right">
      			<input type="button" class="btn btn-default" id='edit' value='Edit'>
      			<button type="submit" disabled class="btn btn-info">Save</button>
      		</div>
	        
      	</div>
      </div>
      <!-- /.box-footer -->
    </form>
  </div>
  <!-- /.box -->
  <script>
  	$( document ).ready(function() {  
  		var edit = 0;
  		$("#edit").on("click", function(event) {
  			edit++;
  			if(edit%2!=0){
				$(".form-horizontal").find('select,input[type=text],input[type=password],button').prop('disabled',false);
				$("#edit").val('Cancel');
			}			     
			else{
				$(".form-horizontal").find('select,input[type=text],input[type=password],button').prop('disabled',true); 
				$("#edit").val('Edit');
			}
  		});
	});
  </script>