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
	          <label for="status" class="col-sm-2 control-label">Status</label>
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
      	<div id="warning"></div>
      	<div class="col-sm-12 col-md-6">      		
          <div class="">
            <div class="col-sm-4 col-md-4">
              <div class="input-group">
                <select id="status" class="form-control">
	      			<option value="confirm">confirm</option>
	      			<option value="reject">reject</option>
	      		</select>
              </div>
              <!-- /input-group -->
            </div>
            <!-- /.col-lg-6 -->
            <div class="col-sm-4 col-md-4">
              <div class="input-group">
                <button id="reject" type="button" class="btn btn-danger">Change Status</button>
              </div>
              <!-- /input-group -->
            </div>
            <!-- /.col-lg-6 -->
          </div>
          <!-- /.row -->
	        <a href="./" type="submit" class="btn btn-info pull-right" style="margin-right: 5px;">Back</a>
      		<input id="id" type="hidden" value="<?php echo $data_topup->id; ?>" />
      	</div>
      </div>
      <!-- /.box-footer -->
    </div>
  </div>
  <!-- /.box -->
  <script>
  $(function () {
  	$('#reject').click(function() {
  		if(confirm("Are you sure you want to "+$('#status').val()+" this?")){
  		$.ajax({
            url:  base_url+"payment/topup_change_status/"+$('#status').val(),
            type: "post",
            data: {
            	'id': $('#id').val(),
            },
            success: function(d,textStatus, xhr) {
               if(xhr.status==200 && d.data==1){
			   	 showalert(d.message,'success','#warning');
			   	 window.location = base_url+"payment/topup_list/";
			   }
            },
             error: function (request, status, error) {
             	 var err = eval("(" + request.responseText + ")");
                 showalert(err.message,'danger','#warning');
            }
        });
    }
    else{
        return false;
    }
  	});
  });
  </script>