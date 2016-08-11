  <!-- Form Element sizes -->
  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">Booking Code</h3>
    </div>
    <div class="box-body">
    	<div class="col-md-3 col-sm-6 col-xs-6">
			<div class="form-group">
				<input id="booking_code" class="form-control input-lg" type="text" placeholder="Booking Code">
			</div>
		</div>
		<div class="col-md-3 col-sm-6 col-xs-6">
			<div class="form-group">
				<a href="#" id="cek" class="btn btn-info btn-flat btn-lg">CEK</a>
			</div>
		</div>
    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.box -->
 <?php if($data != NULL){ ?>
   
  <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <i class="fa fa-book"> </i> Booking Code: <b><?php echo $data->booking_code; ?></b>
            <small class="pull-right">Booking time: <?php echo date("d-m-Y", $data->booking_time); ?></small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          From
          <address>
            <strong><?php echo $bandara[$data->area_depart]['city'] ?><br>
            		<?php echo $bandara[$data->area_depart]['name_airport'].'-'. $bandara[$data->area_depart]['code_route']; ?>
            </strong><br>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          To
          <address>
            <strong><?php echo $bandara[$data->area_arrive]['city'] ?><br>
            		<?php echo $bandara[$data->area_arrive]['name_airport'].'-'. $bandara[$data->area_arrive]['code_route']; ?>
            </strong><br>
          </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
          <span>Contact</span><br>
          Name:<b> <?php echo $data->name; ?></b><br>
          Phone:<b> <?php echo $data->phone; ?></b><br>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <h3>Itenary List</h3>
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
              <th>#</th>
              <th>flight_id</th>
              <th>area_depart</th>
              <th>date_depart</th>
              <th>time_depart</th>
              <th>area_arrive</th>
              <th>date_arrive</th>
              <th>time_arrive</th>
            </tr>
            </thead>
            <tbody>
            	<?php 
            		$i=0;
            		foreach($data->flight_list as $val){ 
            			$i++;
            	?>
            		 <tr>
		              <td><?php echo $i ?></td>
            		  <td><?php echo $val->flight_id ?></td>
		              <td style="background-color:#e0dedf;"><?php echo $val->area_depart ?></td>
		              <td style="background-color:#e0dedf;"><?php echo $val->date_depart ?></td>
		              <td style="background-color:#e0dedf;"><?php echo $val->time_depart ?></td>
		              <td style="background-color:#d6d1d3;"><?php echo $val->area_arrive ?></td>
		              <td style="background-color:#d6d1d3;"><?php echo $val->date_arrive ?></td>
		              <td style="background-color:#d6d1d3;"><?php echo $val->time_arrive ?></td>
		            </tr>
            	<?php } ?>
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      
      <!-- Table row -->
      <h3>Passenger List</h3>
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Birth Date</th>
              <th>Passenger Type</th>
            </tr>
            </thead>
            <tbody>
            	<?php 
            		$i=0;
            		foreach($data->passenger_list as $val){ 
            			$i++;
            	?>
            		 <tr>
		              <td><?php echo $i ?></td>
		              <td><?php echo $val->name ?></td>
		              <td><?php echo $val->birth_date ?></td>
		              <td><?php echo $val->passenger_type ?></td>
		            </tr>
            	<?php } ?>
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-12 col-md-6 pull-right">
          <p class="lead">Payment Time Limit <b><?php echo date("d-m-Y", $data->time_limit); ?> | <?php echo date("H:i", $data->time_limit); ?></b><br>
          Payment Status <b><?php echo $data->payment_status; ?></b></p>

          <div class="table-responsive">
            <table class="table">
              <tr>
                <th style="width:50%">base fare:</th>
                <td>Rp <?php echo number_format($data->base_fare); ?></td>
              </tr>
              <tr>
                <th>Bonus:</th>
                <td>Rp <?php echo number_format($data->base_fare-$data->NTA); ?></td>
              </tr>
              <tr>
                <th>NTA:</th>
                <td>Rp <?php echo number_format($data->NTA); ?></td>
              </tr>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <!-- this row will not appear when printing -->
      <div class="row no-print">
        <div class="col-xs-12">
          <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
          <button type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment
          </button>
          <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
            <i class="fa fa-download"></i> Generate PDF
          </button>
        </div>
      </div>
    </section>
    <!-- /.content -->
    <?php } ?>
    
    <script>
    	var href = '';
    	href = window.location.href;
		$("#booking_code").val(href.substr(href.lastIndexOf('/') + 1));
    	$("#cek").on("click", function(event) {
    		window.location = base_url+"airlines/booking_detail/"+$("#booking_code").val();
    	});
    	
    </script>