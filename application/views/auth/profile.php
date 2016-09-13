<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/iCheck/all.css" />
<script src="<?php echo base_url() ?>assets/plugins/iCheck/icheck.min.js"></script>
<!-- Horizontal Form -->
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Profile User</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" action="" method="post">
      <div class="box-body">
      <div id="warning"></div>	
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
				  <div class="col-sm-4">
				    
				  </div>
				</div>
              </div>
              <!-- tab-pane -->
              <div class="tab-pane" id="tab_2">
               <div class="row">
	           	  <div class="table-responsive no-padding col-md-6">
					  <table class="table table-hover table-striped">
					  	<thead>
						    <tr>
						      <th class="text-center">Account Name</th>
						      <th class="text-center">Bank</th>
						      <th class="text-center">Rek. Number</th>
						      <th class="text-center">Action</th>
						    </tr>
					    </thead>
					    	<?php foreach($bank as $key=>$val){ ?>
					    	<tr class="text-center">
					    		<td><?php echo $val->account_name ?></td>
					    		<td><?php echo $val->bank ?></td>
					    		<td><?php echo $val->rek_number ?></td>
					    		<td>
					    			<label class="disable">
					    			  <input class="flat-red" disabled type="checkbox" data-toggle="<?php echo $key ?>" <?php echo ($val->enable) ? "checked" : "" ?> /> Enable 
					    			</label>
					    		</td>
					    	</tr>
					    	<input type="hidden" id="status_<?php echo $key ?>" name="status[<?php echo $key ?>]" value="<?php echo $val->enable; ?>" />
					    	<?php } ?>		    
					  </table>
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
	    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
	      checkboxClass: 'icheckbox_flat-green',
	    });
	    
  		var edit = 0;
  		$("#edit").on("click", function(event) {
  			edit++;
  			if(edit%2!=0){
				$(".form-horizontal").find('input[type=checkbox],select,input[type=text],input[type=password],button').prop('disabled',false);
				$(".icheckbox_flat-green").removeClass('disabled');
				$("#edit").val('Cancel');
			}			     
			else{
				$(".form-horizontal").find('input[type=checkbox],select,input[type=text],input[type=password],button').prop('disabled',true); 
				$(".icheckbox_flat-green").addClass('disabled');
				$("#edit").val('Edit');
			}
  		});
  		  		
  		$('.flat-red').on('ifChanged', function(event){
  			var data = 0;
  			if($(this).iCheck('update')[0].checked==false){
				data = 0
			} else{
				data = 1
			}
  			$('#status_'+$(this).attr('data-toggle')).val(data);
  			alert($('#status_'+$(this).attr('data-toggle')).val());
		  });
	});
  </script>