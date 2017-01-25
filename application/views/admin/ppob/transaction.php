<?php //print_r($payfor); 
	   $color = array(
                '1111'=>'#0145d1', //sedang diproses
                '0'=>'#00bd30', //berhasil
                '2222'=>'#d3ce0a', //menunggu SN operator
                '1001'=>'#fc2cae', //refund
                '999'=>'#ff2025', //faild
            );
       $tulisan = array(
                '1111'=>'processing', //sedang diproses
                '0'=>'succes', //berhasil
                '2222'=>'waiting SN', //menunggu SN operator
                '1001'=>'refund', //refund
                '999'=>'failed', //faild
            ); 
?>
<!-- Jquery Tag Editor -->
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/plugins/daterangepicker/daterangepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>

  <!-- Form Element sizes -->
  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">Date Filter</h3>
    </div>
    <div class="box-body">
    	 <!-- Date range -->
          <div class="form-group">
            <label>Date range:</label>

            <div class="input-group col-md-3">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="text" value="<?php echo $date_range; ?>" class="form-control pull-right" id="reservation">
            </div>
            <!-- /.input group -->
          </div>
          <!-- /.form group -->
    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.box -->
  
  <?php if($data_table != NULL){?>
  	<div id="result-content" class="box box-primary center-block" style="width: 100%">
		<div class="box-header with-border">
			<h3 class="box-title">Transaction List</h3>
		</div><!-- /.box-header -->
		<div class="box-body">
			<div id="alert"></div>
			<div id="">
				<div class="box-body table-responsive no-padding">
				  <table class="table table-hover table-striped">
				    <tr>
				      <th>Company</th>
				      <th>Product</th>
				      <th>Message</th>
				      <th>Ref TRXID</th>
				      <th>TRXID</th>
				      <th>Status</th>
				      <th>Date</th>
				    </tr>
				    <?php
				    $i=0;
					foreach($data_table as $value){ $i++;?>
				    <tr>
				      <td><?php echo $value->brand ?></td>
				      <td><?php echo $value->product ?></td>
				      <td><?php echo $value->message ?></td>
				      <td><?php echo $value->ref_trxid ?></td>
				      <td><?php echo $value->trxid ?></td>
				      <td><?php echo "<span class='label' style='background-color:".$color[$value->status]."; font-size:0.9em'>".$tulisan[$value->status]."</span>" ?></td>
				      <td><?php echo $value->created2 ?></td>
				    </tr>
				    <?php } ?>
				    
				  </table>
				</div>
				<!-- /.box-body -->
			</div>
		</div>
	</div><!-- /.box-body -->  
  <?php } ?>
  
 
<script>
$(document).ready(function(){
	$('#reservation').daterangepicker(
				{
					"opens": "right",
					'autoApply': true,
					locale: {format: 'DD/MM/YYYY'},
				}
	).on('apply.daterangepicker', function(ev, p){
          window.location = base_url+"ppob/transaction?range="+$(this).val();
    });
});
</script>