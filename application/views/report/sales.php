<?php 
 	$color = array(
 				'booking'=>'#636c70',
 				'issued'=>'#00bd30',
 				'cancel'=>'#d3ce0a',
 				'timeup'=>'#e7bd41',
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
			<h3 class="box-title">Retrieve List</h3>
		</div><!-- /.box-header -->
		<div class="box-body">
			<div id="alert"></div>
			<div id="">
				<div class="box-body table-responsive no-padding">
				  <table class="table table-hover table-striped">
				    <tr>
				      <th>Booking Code</th>
				      <th>Tujuan</th>
				      <th>Contact</th>
				      <th>Booking time</th>
				      <th>Time Issued</th>
				      <th>Fare</th>
				      <th>NTA</th>
				      <th>Passanger</th>
				      <th>Status booking</th>
				      <th>Detail</th>
				    </tr>
				    <?php
				    $i=0;
					foreach($data_table as $value){ $i++;?>
				    <tr>
				      <td><?php echo $value->airline." - ".$value->{'booking code'} ?></td>
				      <td><?php echo $value->{'area depart'}."-".$value->{'area arrive'} ?></td>
				      <td><?php echo $value->name ?></td>
				      <td><?php echo date("d-m-Y H:i:s",$value->{'booking time'}) ?></td>
				      <td><?php echo date("d-m-Y H:i:s",$value->{'time status'}) ?></td>
				      <td><?php echo number_format($value->{'base fare'}+$value->tax) ?></td>
				      <td><?php echo number_format($value->NTA) ?></td>
				      <td>
				      	<?php echo "A: $value->adult | C: $value->child | I: $value->infant" ?>
				      	
				      </td>
				      <td><?php echo "<span class='label' style='background-color:".$color[$value->status]."; font-size:0.9em'>".$value->status."</span>" ?></td>
				      <td><a href="<?php echo base_url()."airlines/retrieve/".$value->{'booking code'} ?>" type="button" class="btn btn-success btn-sm"><li class="fa fa-eye"></li></a></td>
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
	var change = 0;
	$('#reservation').on('change', function() {
		change++;
		if(change>3){
			window.location = base_url+"report/sales?range="+$(this).val();
		}		
		//window.location = base_url+"report/sales?range="+$(this).val();
	});
	$('#reservation').daterangepicker(
				{
					"opens": "right",
					'autoApply': true,
					locale: {format: 'DD/MM/YYYY'},
				}
	);
});
</script>