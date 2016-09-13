<?php 
 	$color = array(
 				'pending'=>'#636c70',
 				'submit'=>'#186bd1',
 				'confirm'=>'#00bd30',
 				'reject'=>'#ec1e23',
 				'cancel'=>'#d3ce0a',
 				'timeup'=>'#e7bd41',
 			);
?>

<div class="box center-block" style="width: 100%">
	<div class="box-header with-border">
		<h3 class="box-title">Topup List</h3>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div id="warning"></div>
		<div class="table-responsive no-padding col-md-10">
		  <table class="table table-hover table-striped">
		  	<thead>
		    <tr>
		      <th class="text-center">Nominal</th>
		      <th class="text-center">Unique</th>
		      <th class="text-center">Total</th>
		      <th class="text-center">From</th>
		      <th class="text-center">To</th>
		      <th class="text-center">Date</th>
		      <th class="text-center">Status</th>
		      <th class="text-center">Action</th>
		    </tr>
		    </thead>
		    <?php
			foreach($data_table as $value){ ?>
		    <tr>
		      <td class='text-center'><?php echo number_format($value->nominal) ?></td>
		      <td class='text-center'><?php echo $value->unique ?></td>
		      <td class='text-center'><?php echo number_format($value->unique+$value->nominal); ?></td>   
		      <td class="text-center"><?php echo $bank[$value->id_bank]->bank."-".$bank[$value->id_bank]->rek_number."-".$bank[$value->id_bank]->account_name; ?></td>
		      <td class="text-center"><?php echo $bank[$value->id_bank_to]->bank."-".$bank[$value->id_bank_to]->rek_number."-".$bank[$value->id_bank_to]->account_name; ?></td>
		      <td class='text-center'><?php echo date("d-m-Y H:i:s",$value->time_status) ?></td>
		      <td class="text-center">
		      	 <?php echo "<span class='label' style='background-color:".$color[$value->status]."; font-size:0.9em'>".$value->status."</span>"; ?>
		      </td>
		      <td class="text-center">
		      	<a href="<?php echo base_url().'payment/topup_list/'.$value->id; ?>" title="view detail" type="button" class="btn btn-success btn-sm"><li class="fa fa-eye"></li></a>
		      	<button title="cancel" data-toggle="<?php echo $value->id ?>" type="button" class="cancel btn btn-danger btn-sm"><li class="fa fa-close"></li></button>
		      	<button title="submit" data-toggle="<?php echo $value->id ?>" type="button" class="submit btn btn-primary btn-sm"><li class="fa fa-paper-plane"></li></button>
		      </td>
		    </tr>
		    <?php } ?>		    
		  </table>
		</div>
	</div>
</div><!-- /.box-body -->

<script>
$(function () {
	$('.submit').click(function() {
		change('submit','Pastikan jumlah yang anda transfer sesuai Total (Nominal+Unique)',$(this).attr('data-toggle'));
	});
	$('.cancel').click(function() {
		change('cancel','Anda yakin ingin membatalkan transaksi',$(this).attr('data-toggle'));
	});
	
	<?php if($data_table==NULL){?>
		showalert('Tidak ada topup berstatus PENDING','success','#warning',600000);
		$('.table').hide();
	<?php }?>
	
	function change(status='',pesan='',id){
		if(confirm(pesan)){
			$.ajax({
		        url:  base_url+"payment/topup_change_status/"+status,
		        type: "post",
		        data: {
		        	'id': id,
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