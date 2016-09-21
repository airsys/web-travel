<!-- Horizontal Form -->
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Topup Detail</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <div class="form-horizontal">
      <div class="box-body"> 
    	<div class="col-md-12">
			<div class="form-group">
	          <label for="email" class="col-sm-2 control-label">Transfer to</label>
	          <div class="col-sm-4">
	             <label class="control-label"><?php echo $bank[$data_topup->{'bank to'}]['account name']." - ".$bank[$data_topup->{'bank to'}]['rek number']." - ".$bank[$data_topup->{'bank to'}]['bank']; ?></label>
	          </div>
	        </div>
        </div>
        <!-- /.col -->
        <div class="col-md-12">
			<div class="form-group">
	          <label for="email" class="col-sm-2 control-label">Transfer from</label>
	          <div class="col-sm-4">
	             <label class="control-label"><?php echo $bank[$data_topup->{'bank from'}]['account name']." - ".$bank[$data_topup->{'bank from'}]['rek number']." - ".$bank[$data_topup->{'bank from'}]['bank']; ?></label>
	          </div>
	        </div>
        </div>
        <!-- /.col -->
        <div class="col-md-12">
			<div class="form-group">
	          <label for="email" class="col-sm-2 control-label">Nominal</label>
	          <div class="col-sm-4">
	             <label class="control-label"><?php echo number_format($data_topup->nominal); ?></label>
	          </div>
	        </div>
        </div>
        <!-- /.col -->
        <div class="col-md-12">
			<div class="form-group">
	          <label for="email" class="col-sm-2 control-label">Unique</label>
	          <div class="col-sm-4">
	             <label class="control-label"><?php echo $data_topup->unique; ?></label>
	          </div>
	        </div>
        </div>
        <!-- /.col -->
        <div class="col-md-12">
			<div class="form-group">
	          <label for="email" class="col-sm-2 control-label">TOTAL</label>
	          <div class="col-sm-4">
	             <label class="control-label"><?php echo number_format($data_topup->unique+$data_topup->nominal); ?></label>
	          </div>
	        </div>
        </div>
        <div class="col-md-12">
			<div class="form-group">
	          <label for="status" class="col-sm-2 control-label">Status</label>
	          <div class="col-sm-4">
	          	 <?php 
	          	 foreach($data_status as $val) {  ?>
	             <label class="control-label"> - <?php echo $val->status."&nbsp;&nbsp;&nbsp;". date("d-m-Y H:i:s", $val->{'time status'}) ; ?></label><br>
	             <?php } ?>
	          </div>
	        </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
      	<div id="warning"></div>
      	<div class="col-sm-6 col-md-6">
	        <a href="./" type="submit" class="btn btn-info pull-right" style="margin-right: 5px;">Back</a>
      		<input id="id" type="hidden" value="<?php echo $data_topup->id; ?>" />
      	</div>
      </div>
      <!-- /.box-footer -->
    </div>
  </div>
  <!-- /.box -->
  