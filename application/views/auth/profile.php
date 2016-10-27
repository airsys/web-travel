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
              	<form class="form-horizontal" enctype="multipart/form-data" action="" method="post">
				<div class="form-group">
		          <label for="company" class="col-sm-2 control-label">Company</label>
		          <div class="col-sm-4">
		            <input type="email" disabled class="form-control" value="<?php echo ($data_post != NULL) ? $company[$data_post['company']] : ""; ?>" id="company" placeholder="company">
		          </div>
		        </div>
				<div class="form-group">
		          <label for="email" class="col-sm-2 control-label">Email</label>
		          <div class="col-sm-4">
		            <input type="email" disabled class="form-control" value="<?php echo ($data_post != NULL) ? $data_post['email'] : ""; ?>" id="email" placeholder="email">
		          </div>
		        </div>
		        <div class="form-group">
		          <label for="first_name" class="col-sm-2 control-label">Full Name</label>
		          <div class="col-sm-4">
		            <input type="text" required disabled class="form-control" value="<?php echo ($data_post != NULL) ? $data_post['full name'] : ""; ?>" name="full_name" id="first_name" placeholder="Full Name">
		          </div>
		        </div>
		        <div class="form-group">
		          <label for="phone" class="col-sm-2 control-label">Phone</label>
		          <div class="col-sm-4">
		            <input type="text" required disabled class="form-control" value="<?php echo ($data_post != NULL) ? $data_post['phone'] : ""; ?>" name="phone" id="phone" placeholder="phone">
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
		          <label for="first_name" class="col-sm-2 control-label">Logo(optional)</label>
		          <div class="col-sm-4">
		          	<input type="file" disabled class="form-control" name="logo" id="logo">
		          	<img src="<?= base_url().'assets/dist/img/logo/'.$data_post['logo'] ?>" alt="logo" height="150px;" class="margin">
		          </div>
		        </div>
		        <div class="form-group">
		          <label for="password_confirm" class="col-sm-2 control-label"></label>
				  <div class="col-sm-4">
				    <div class="pull-right">
		      			<input type="button" class="btn btn-default" id='edit' value='Edit'>
		      			<button type="submit" disabled class="btn btn-info">Save</button>
		      		</div>	
				  </div>
				</div>      	
                </form>
              </div>
              <!-- tab-pane -->
              <div class="tab-pane" id="tab_2">
               <div class="row">
               	  <?php if($bank!=NULL){ ?>
	           	  <div class="table-responsive no-padding col-md-6">
					  <table class="table table-hover table-striped">
					  	<thead>
						    <tr>
						      <th class="text-center">Account Name</th>
						      <th class="text-center">Bank</th>
						      <th class="text-center">Rek. Number</th>
						      <th class="text-center">Status</th>
						      <th class="text-center">Action</th>
						    </tr>
					    </thead>
					    
					    	<?php 
					    	foreach($bank as $key=>$val){ ?>
					    	<tr class="text-center">
					    		<td><?php echo $val['account name'] ?></td>
					    		<td><?php echo $val['bank'] ?></td>
					    		<td><?php echo $val['rek number'] ?></td>
					    		<td>
					    			<?php $status =  ($val['enable']==1 ? "ENABLE" : "DISABLE"); ?>
					    			<?php echo "<span class='label' style='background-color:".$color[$val['enable']]."; font-size:0.9em'>".$status."</span>"; ?>
					    		</td>
					    		<td><a href="<?php echo base_url()."auth2/bank_detail/".$key ?>" type="button" class="btn btn-success btn-sm"><li class="fa fa-eye"></li></a></td>
					    	</tr>
					    	<?php } ?>		    
					  </table>
				  </div>
				  <?php } 
				  	else{
							echo '<div class="alert alert-success">belum ada data bank, <br>silahkan Input Topup terlebih dahulu</div>';	
						}
				  ?> 
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
      	
      </div>
      <!-- /.box-footer -->
  </div>
  <!-- /.box -->
  <script>
  	$( document ).ready(function() { 
  		var edit = 0;
  		$("#edit").on("click", function(event) {
  			edit++;
  			if(edit%2!=0){
				$(".form-horizontal").find('input[type=checkbox],select,input[type=file],input[type=text],input[type=password],button').prop('disabled',false);
				$("#edit").val('Cancel');
			}			     
			else{
				$(".form-horizontal").find('input[type=checkbox],select,input[type=text],input[type=file],input[type=password],button').prop('disabled',true); 
				$("#edit").val('Edit');
			}
  		});
	});
  </script>