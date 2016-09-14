<div class="box box-primary" style="width: 100%">
    <div class="box-header with-border">
      <h3 class="box-title">Add Bank Account</h3>
    </div>
    <!-- /.box-header -->
    
  <form action="" method="post" >
  <div class="box-body">
  	<div id="warning"></div>
  	<div class="row">
	  	<div class="form-group">
	      <label for="first_name" class="col-sm-2 control-label">No. Rekening</label>
	      <div class="col-sm-4">
	        <input type="text" required class="form-control" value="" name="rek_number" id="rek_number" placeholder="No. Rek">
	      </div>
	    </div>
    </div>
    <div class="row">
    <div class="form-group">
      <label for="bank" class="col-sm-2 control-label">Bank Name</label>
      <div class="col-sm-4">
        <select required name="bank" id="bank" class="form-control" >
        	<?php echo listDataOption('setting_bank','name','name'); ?>
        </select>
      </div>
    </div>
    </div>
    <div class="row">
    <div class="form-group">
      <label for="account_name" class="col-sm-2 control-label">Account name</label>
      <div class="col-sm-4">
        <input type="text" required class="form-control" value="" name="account_name" id="account_name" placeholder="account name">
      </div>
    </div>
    </div>
  </div>
  <div class="box-footer">
  	<div class="form-group col-md-6">
  		<a href="<?php echo base_url().'admin/setting/bank' ?>" class="btn btn-default">Cancel</a>
  		<button type="submit" class="btn btn-success pull-right">SAVE</button>
  	</div>
  </div>
  </form>
</div>