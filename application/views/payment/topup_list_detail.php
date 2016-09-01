<?php //print_r($data_post['first_name']); ?>
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
	             <span class="form-control"><?php echo $bank[$data_topup->id_bank_to]->account_name." - ".$bank[$data_topup->id_bank_to]->rek_number." - ".$bank[$data_topup->id_bank_to]->bank; ?></span>
	          </div>
	        </div>
        </div>
        <!-- /.col -->
        <div class="col-md-12">
			<div class="form-group">
	          <label for="email" class="col-sm-2 control-label">Transfer from</label>
	          <div class="col-sm-4">
	             <span class="form-control"><?php echo $bank[$data_topup->id_bank]->account_name." - ".$bank[$data_topup->id_bank]->rek_number." - ".$bank[$data_topup->id_bank]->bank; ?></span>
	          </div>
	        </div>
        </div>
        <!-- /.col -->
        <div class="col-md-12">
			<div class="form-group">
	          <label for="email" class="col-sm-2 control-label">Nominal</label>
	          <div class="col-sm-4">
	             <span class="form-control"><?php echo number_format($data_topup->nominal); ?></span>
	          </div>
	        </div>
        </div>
        <!-- /.col -->
        <div class="col-md-12">
			<div class="form-group">
	          <label for="email" class="col-sm-2 control-label">Unique</label>
	          <div class="col-sm-4">
	             <span class="form-control"><?php echo $data_topup->unique; ?></span>
	          </div>
	        </div>
        </div>
        <!-- /.col -->
        <div class="col-md-12">
			<div class="form-group">
	          <label for="email" class="col-sm-2 control-label">TOTAL</label>
	          <div class="col-sm-4">
	             <span class="form-control"><?php echo number_format($data_topup->unique+$data_topup->nominal); ?></span>
	          </div>
	        </div>
        </div>
        <div class="col-md-12">
			<div class="form-group">
	          <label for="email" class="col-sm-2 control-label">Status</label>
	          <div class="col-sm-4">
	          	 <?php 
	          	 foreach($data_status as $val) {  ?>
	             <span class="form-control"><?php echo $val->status."&nbsp;&nbsp;&nbsp;". date("d-m-Y H:i:s", $val->time_status) ; ?></span><br>
	             <?php } ?>
	          </div>
	        </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
      	<div class="col-sm-6">
	        <a href="topup_list" type="submit" class="btn btn-info pull-right">Back</a>
      	</div>
      </div>
      <!-- /.box-footer -->
    </div>
  </div>
  <!-- /.box -->
  <script>
  
  </script>