<?php //print_r($bank[$id][9]); ?>
<!-- Horizontal Form -->
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">User Bank</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
      <div class="box-body">
    	<div class="col-md-12">
    		<form class="form-horizontal" action="" method="post">
			<div class="form-group">
	          <label for="bank" class="col-sm-2 control-label">Bank</label>
	          <div class="col-sm-4">
	            <input type="text" disabled class="form-control" value="<?php echo ($bank[$id] != NULL) ? $bank[$id]->bank : ""; ?>" id="bank" placeholder="bank">
	          </div>
	        </div>
	        <div class="form-group">
	          <label for="account_name" class="col-sm-2 control-label">Account Name</label>
	          <div class="col-sm-4">
	            <input type="text" required disabled class="form-control" value="<?php echo ($bank[$id] != NULL) ? $bank[$id]->account_name : ""; ?>" name="account_name" id="account_name" placeholder="Account Name">
	          </div>
	        </div>
	        <div class="form-group">
	          <label for="rek_number" class="col-sm-2 control-label">Rek. Number</label>
	          <div class="col-sm-4">
	            <input type="text" required disabled class="form-control" value="<?php echo ($bank[$id] != NULL) ? $bank[$id]->rek_number : ""; ?>" name="rek_number" id="rek_number" placeholder="rek number">
	          </div>
	        </div>
	        <div class="form-group">
	          <label for="status" class="col-sm-2 control-label">Status</label>
	          <div class="col-sm-4">
	            <select class="form-control" name="status" id="status">
	            	<option value="1" <?php echo ($bank[$id]->rek_number == 1) ? "selected" : ""; ?> >Enable</option>
	            	<option value="0" <?php echo ($bank[$id]->rek_number == 0) ? "selected" : ""; ?> >Disable</option>
	            </select>
	          </div>
	        </div>
	        <input type="hidden" name="id" value="<?php echo $id ?>"/>
	        <div class="form-group">
	          <label for="password_confirm" class="col-sm-2 control-label"></label>
			  <div class="col-sm-4">
			    <div class="pull-right">
	      			<a href="<?php echo base_url().'admin/setting/bank' ?>" class="btn btn-default">Cancel</a>
	      			<button type="submit" class="btn btn-info">Save</button>
	      		</div>	
			  </div>
			</div>      	
            </form>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
      	
      </div>
      <!-- /.box-footer -->
  </div>
  <!-- /.box -->