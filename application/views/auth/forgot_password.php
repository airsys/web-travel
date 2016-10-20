<?php //print_r($data_post['first_name']); ?>
<!-- Horizontal Form -->
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title"><?php echo lang('forgot_password_heading');?></h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" action="<?php echo base_url()?>auth2/forgot_password" method="post">
      <div class="box-body">      	
    <?php if($message != '') { ?>
    	<div class="alert alert-warning col-md-12"><a class="close" data-dismiss="alert">x</a><span><?php echo $message; ?></span></div>
    <?php } ?>
    	<div class="form-group">
    		<p class="col-sm-12"><?php echo sprintf(lang('forgot_password_subheading'), $identity_label);?></p>
        </div>
        <div class="form-group">
          <label for="identity" class="col-sm-2 control-label"><?php echo (($type=='email') ? sprintf(lang('forgot_password_email_label'), $identity_label) : sprintf(lang('forgot_password_identity_label'), $identity_label));?></label>
          <div class="col-sm-4">
            <input type="text" required class="form-control" name="<?php echo $identity['name']; ?>" id="first_name">
          </div>
        </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
      	<div class="col-sm-6">
	        <button type="reset" class="btn btn-default">Cancel</button>
	        <button type="submit" class="btn btn-info pull-right"><?php echo lang('forgot_password_submit_btn');?></button>
      	</div>
      </div>
      <!-- /.box-footer -->
    </form>
  </div>
  <!-- /.box -->
