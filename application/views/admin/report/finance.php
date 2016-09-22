<?php //print_r($payfor); ?>
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
				      <th>Code</th>
				      <th>Credit</th>
				      <th>Debet</th>
				      <th>Pay for</th>
				      <th>Date</th>
				      <th>Detail</th>
				    </tr>
				    <?php
				    $i=0;
					foreach($data_table as $value){ $i++;?>
				    <tr>
				      <td><?php echo $value->brand ?></td>
				      <td><?php echo $value->code ?></td>
				      <td><?php echo $value->credit ?></td>
				      <td><?php echo $value->debet ?></td>
				      <td><?php echo $payfor[$value->code][$value->{'pay for'}]; ?></td>
				      <td><?php echo $value->created ?></td>
				      <td><a href="<?php echo base_url()."airlines/retrieve/".$value->id ;?>" type="button" class="btn btn-success btn-sm"><li class="fa fa-eye"></li></a></td>
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
          window.location = base_url+"report/finance?range="+$(this).val();
    });
});
</script>