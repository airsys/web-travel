<!-- Horizontal Form -->
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title"><?php echo lang('reset_password_heading');?></h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" action="<?php echo base_url().'auth2/reset_password/' . $code; ?>" method="post">
      <div class="box-body">      	
    <?php if($message != '') { ?>
    	<div class="alert alert-warning col-md-12"><a class="close" data-dismiss="alert">x</a><span><?php echo $message;?></span></div>
    <?php } ?>
        <div class="form-group">
          <label for="password" class="col-sm-2 control-label"><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?></label>
          <div class="col-sm-4">
            <input type="password" required class="form-control" name="<?php echo $new_password['name']; ?>" id="password">
          </div>
        </div>
        <div class="form-group">
          <label for="password1" class="col-sm-2 control-label"><?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm');?></label>
          <div class="col-sm-4">
            <input type="password" required class="form-control" name="<?php echo $new_password_confirm['name']; ?>" id="password">
          </div>
        </div>
        <?php echo form_input($user_id);?>
		<?php echo form_hidden($csrf); ?>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
      	<div class="col-sm-6">
	        <button type="reset" class="btn btn-default">Cancel</button>
	        <button type="submit" class="btn btn-info pull-right"><?php echo lang('reset_password_submit_btn');?></button>
      	</div>
      </div>
      <!-- /.box-footer -->
    </form>
  </div>
  <!-- /.box -->
