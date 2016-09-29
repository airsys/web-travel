<style>
	.control-span {
  padding-top: 7px;
  font-weight: normal !important;
}
</style>
<!-- Horizontal Form -->
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Topup Detail</h3>
    </div>
    <!-- /.box-header -->
    <div id="warning"></div>
    <!-- form start -->
    <div class="form-horizontal">
      <div class="box-body"> 
    	<div class="col-md-12">
			<div class="form-group">
	          <label for="email" class="col-sm-2 control-label">Transfer to</label>
	          <div class="col-sm-4">
	             <label class="control-span"><?php echo $bank[$data_topup->{'bank to'}]['account name']." - ".$bank[$data_topup->{'bank to'}]['rek number']." - ".$bank[$data_topup->{'bank to'}]['bank']; ?></label>
	          </div>
	        </div>
        </div>
        <!-- /.col -->
        <div class="col-md-12">
			<div class="form-group">
	          <label for="email" class="col-sm-2 control-label">Transfer from</label>
	          <div class="col-sm-4">
	             <label class="control-span"><?php echo $bank[$data_topup->{'bank from'}]['account name']." - ".$bank[$data_topup->{'bank from'}]['rek number']." - ".$bank[$data_topup->{'bank from'}]['bank']; ?></label>
	          </div>
	        </div>
        </div>
        <!-- /.col -->
        <div class="col-md-12">
			<div class="form-group">
	          <label for="email" class="col-sm-2 control-label">Nominal</label>
	          <div class="col-sm-4">
	             <label class="control-span"><?php echo number_format($data_topup->nominal); ?></label>
	          </div>
	        </div>
        </div>
        <!-- /.col -->
        <div class="col-md-12">
			<div class="form-group">
	          <label for="email" class="col-sm-2 control-label">Unique</label>
	          <div class="col-sm-4">
	             <label class="control-span"><?php echo $data_topup->unique; ?></label>
	          </div>
	        </div>
        </div>
        <!-- /.col -->
        <div class="col-md-12">
			<div class="form-group">
	          <label for="email" class="col-sm-2 control-label">TOTAL</label>
	          <div class="col-sm-4">
	             <label class="control-span"><?php echo number_format($data_topup->unique+$data_topup->nominal); ?></label>
	          </div>
	        </div>
        </div>
        <div class="col-md-12">
			<div class="form-group">
	          <label for="status" class="col-sm-2 control-label">Status</label>
	          <div class="col-sm-4">
	          	 <?php 
	          	 foreach($data_status as $val) {  ?>
	             <label class="control-span"> - <?php echo $val->status."&nbsp;&nbsp;&nbsp;". date("d-m-Y H:i:s", $val->{'time status'}) ; ?></label><br>
	             <?php } ?>
	          </div>
	        </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
      	<div class="col-sm-6 col-md-6">
	        <a href="#" onclick="window.history.go(-1); return false;" class="btn btn-info"><i class="fa fa-arrow-circle-left"></i> Back</a>
      	</div>
      </div>
      <!-- /.box-footer -->
    </div>
  </div>
  <!-- /.box -->
<script>
$(function () {
	$('.submit').click(function() {
		change('submit','Pastikan jumlah yang anda transfer sesuai Total (Nominal+Unique)');
	});
	$('.cancel').click(function() {
		change('cancel','Anda yakin ingin membatalkan transaksi');
	});
	
	function change(status='',pesan=''){
		if(confirm(pesan)){
			$.ajax({
		        url:  base_url+"payment/topup_change_status/"+status,
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
		             window.location = base_url+"payment/topup_list/";
		        }
		    });
		}
		else{
		    return false;
		}
	}
});
</script>