<?php //print_r($data_post['first_name']); ?>
<!-- Horizontal Form -->
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Topup</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form class="form-horizontal" action="" method="post">
      <div class="box-body">      	
    <?php if($message != '') { ?>
    	<div class="alert alert-warning col-md-12"><a class="close" data-dismiss="alert">x</a><span><?php echo $message; ?></span></div>
    <?php } ?>
    	<div class="col-md-12">
			<div class="form-group">
	          <label for="email" class="col-sm-2 control-label">Input Method</label>
	          <div class="col-sm-4">
	            <select name="payment_tipe" id="payment_tipe" class="form-control" >
	            	<option value="0">input manual</option>
	            	<option value="1">from bank account</option>
	            </select>
	          </div>
	        </div>
	        <div id="saved-bank">
	        <div class="form-group">
	          <label for="first_name" class="col-sm-2 control-label">Bank Account</label>
	          <div class="col-sm-4">
	            <select id="bank_account" name="bank_account" class="form-control" >
	            	<?php foreach($bank_account as $val){
	            		echo "<option value=$val->id>$val->bank-$val->rek_number-$val->account_name</option>";	
	            	 } ?>
	            </select>
	          </div>
	        </div>
	        </div>
	        <div id="manual">
	        <div class="form-group">
	          <label for="first_name" class="col-sm-2 control-label">No. Rekening</label>
	          <div class="col-sm-4">
	            <input type="text" required class="form-control" value="" name="rek_number" id="rek_number" placeholder="No. Rek">
	          </div>
	        </div>
	        <div class="form-group">
	          <label for="bank" class="col-sm-2 control-label">Bank Name</label>
	          <div class="col-sm-4">
	            <select required name="bank" id="bank" class="form-control" >
	            	<option value="BRI">BRI</option>
	            	<option value="BCA">BCA</option>
	            	<option value="MANDIRI">MANDIRI</option>
	            	<option value="BNI">BNI</option>
	            </select>
	          </div>
	        </div>
	        <div class="form-group">
	          <label for="account_name" class="col-sm-2 control-label">Account name</label>
	          <div class="col-sm-4">
	            <input type="text" required class="form-control" value="" name="account_name" id="account_name" placeholder="account name">
	          </div>
	        </div>
	        </div>
	        <hr />
	        <div class="form-group">
	          <label for="id_bank_to" class="col-sm-2 control-label">Transfer to</label>
	          <div class="col-sm-4">
	            <select required name="id_bank_to" id="id_bank_to" class="form-control" >
	            	<?php foreach($bank as $val){
	            		echo "<option value=$val->id>$val->bank - $val->rek_number - $val->account_name</option>";	
	            	 } ?>
	            </select>
	          </div>
	        </div>
	        <div class="form-group">
	          <label for="nominal" class="col-sm-2 control-label">Nominal</label>
	          <div class="col-sm-4">
	            <input type="text" required class="form-control" name="nominal" id="nominal" placeholder="nominal">
	          </div>
	        </div>
	        <div class="form-group">
	          <label for="unique" class="col-sm-2 control-label">Unique Code</label>
	          <div class="col-sm-4">
	            <input disabled class="form-control" value="<?php echo $unique ?>">
	          </div>
	        </div>
	        <input type="hidden" id="unique" name="unique" value="<?php echo $unique ?>"/>
	        <div class="form-group has-success">
              <label class="col-sm-2 control-label" for="must"><i class="fa fa-check"></i> Must Transfer</label>
              <div class="col-sm-4">
	              <input type="text" class="form-control" readonly="" id="must" value="<?php echo $unique ?>">
	              <span class="help-block">Jumlah yang harus anda transfer ke bank</span>
              </div>
            </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
      	<div class="col-sm-6">
	        <button type="submit" class="btn btn-info pull-right">Save</button>
      	</div>
      </div>
      <!-- /.box-footer -->
    </form>
  </div>
  <!-- /.box -->
  <script>
  	$( document ).ready(function() {
  		$('#saved-bank').hide();
		$('#manual').show();
  		$('#payment_tipe').on('change', function() {			
  			//var rek_data = $('#payment_tipe :selected').text().split('-');
  			if($("#payment_tipe").val()!=0){
				$('#rek_number').prop('readonly',true);
				$('#rek_number').prop('required',false);
				
				$('#bank').prop('required',false);
				$('#bank').prop('disabled',true);
				
				$('#account_name').prop('required',false);
				$('#account_name').prop('readonly',true);
				
				$('#saved-bank').show();
				$('#manual').hide();
				
				$('#bank_account').val('');
				$('#bank_account').prop('required',true);				
								
			}else{
				$('#rek_number').prop('required',true);
				$('#rek_number').prop('readonly',false);
				
				$('#bank').prop('required',true);
				$('#bank').prop('disabled',false);
				
				$('#account_name').prop('required',true);
				$('#account_name').prop('readonly',false);
				
				$('#saved-bank').hide();
				$('#manual').show();
				
				$('#rek_number').val('');
				$('#bank').val('');
				$('#account_name').val('');
				$('#bank_account').prop('required',false);
			}
  		});
  		$('#nominal').val(1000000);
  		$('#must').val(addCommas(parseInt($('#nominal').val())+parseInt($('#unique').val())));
  		$('#nominal').on('keyup', function() {
  			$('#must').val(addCommas(parseInt($(this).val())+parseInt($('#unique').val())));
  		});
  		$('#nominal').on('change', function() {
  			$('#must').val(addCommas(parseInt($(this).val())+parseInt($('#unique').val())));
  		});
	});
  </script>