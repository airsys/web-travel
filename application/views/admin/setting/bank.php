<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/iCheck/all.css" />
<script src="<?php echo base_url() ?>assets/plugins/iCheck/icheck.min.js"></script>
<div class="box box-primary" style="width: 100%">
    <div class="box-header with-border">
      <h3 class="box-title">Bank Account</h3>
    </div>
    <!-- /.box-header -->
    
  <div class="box-body">
  	<div id="warning"></div>
  	<div class="form-group">
  		<a href="<?php echo base_url().'admin/setting/bank_add' ?>" type="button" class="btn btn-success"><li class="fa fa-plus"></li>&nbsp;ADD</a>
  	</div>
  	<div class="table-responsive no-padding">
	  <table class="table table-hover table-striped">
	  	<thead>
	    <tr>
	      <th class="text-center">Account name</th>
	      <th class="text-center">Rek number</th>
	      <th class="text-center">Bank</th>
	      <th class="text-center">Enable</th>
	    </tr>
	    </thead>
	    <?php
		foreach($bank as $key => $value){ ?>
	    <tr>
	      <td class="text-center"><?php echo ucwords($value->account_name) ?></td>
	      <td class='text-center'><?php echo $value->rek_number ?></td>
	      <td class='text-center'><?php echo $value->bank ?></td>
	      <td class='text-center'>
	      	<label>
			  <input class="flat-red" type="checkbox" data-toggle="<?php echo $key ?>" <?php echo ($value->enable) ? "checked" : "" ?> /> Enable 
			</label>
	      </td>
	    </tr>
	    <?php } ?>	    
	  </table>
	</div>
  </div>
  <div class="box-footer">
  </div>
</div>

<script>
  $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
	  checkboxClass: 'icheckbox_flat-green',
	  radioClass: 'iradio_flat-green'
	});
  
  $('.flat-red').on('ifChanged', function(event){
	$.ajax({
	        url:  base_url+"setting/change_status_bank/",
	        type: "post",
	        data: {
	        	'id_bank': $(this).attr('data-toggle'),
	        	'status': $(this).iCheck('update')[0].checked,
	        },
	        success: function(d,textStatus, xhr) {
	           if(xhr.status==200 && d.data==1){
			   	 showalert(d.message,'success','#warning');
			   }
	        },
	         error: function (request, status, error) {
	         	 var err = eval("(" + request.responseText + ")");
	             showalert(err.message,'danger','#warning');
	        }
	    });
  });
  
</script>