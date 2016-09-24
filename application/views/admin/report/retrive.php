<!-- Main content -->
<section class="invoice">
  <!-- title row -->
  <div class="row">
    <div class="col-xs-12">
      <h2 class="page-header">
        <i class="fa fa-book"> </i> Booking Code: <b><?php echo $data_detail->booking_code; ?></b>
        <small class="pull-right">Booking time: <?php echo date("d-m-Y", $data_detail->booking_time); ?></small>
      </h2>
    </div>
    <!-- /.col -->
  </div>
  <!-- info row -->
  <div class="row invoice-info">
    <div class="col-sm-4 invoice-col">
      From
      <address>
        <strong><?php echo $bandara[$data_detail->area_depart]['city'] ?><br>
        		<?php echo $bandara[$data_detail->area_depart]['name_airport'].'-'. $bandara[$data_detail->area_depart]['code_route']; ?>
        </strong><br>
      </address>
    </div>
    <!-- /.col -->
    <div class="col-sm-4 invoice-col">
      To
      <address>
        <strong><?php echo $bandara[$data_detail->area_arrive]['city'] ?><br>
        		<?php echo $bandara[$data_detail->area_arrive]['name_airport'].'-'. $bandara[$data_detail->area_arrive]['code_route']; ?>
        </strong><br>
      </address>
    </div>
    <!-- /.col -->
    <div class="col-sm-4 invoice-col">
      <span>Contact</span><br>
      Name:<b> <?php echo $data_detail->name; ?></b><br>
      Phone:<b> <?php echo $data_detail->phone; ?></b><br>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

  <!-- Table row -->
  <h3>Itinerary List</h3>
  <div class="row">
    <div class="col-xs-12 table-responsive">
      <table class="table table-striped">
        <thead>
        <tr>
          <th>#</th>
          <th>Flight ID</th>
          <th>Area Depart</th>
          <th>Date Depart</th>
          <th>Time Depart</th>
          <th>Area Arrive</th>
          <th>Area Arrive</th>
          <th>Time Arrive</th>
        </tr>
        </thead>
        <tbody>
        	<?php 
        		$i=0;
        		foreach($data_detail->flight_list as $val){ 
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
        		foreach($data_detail->passenger_list as $val){ 
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
      <p class="lead">Payment Time Limit <b><?php echo date("d-m-Y", $data_detail->time_limit); ?> | <?php echo date("H:i", $data_detail->time_limit); ?></b><br></p>

      <div class="table-responsive">
        <table class="table">
          <tr>
            <th style="width:50%">Fare:</th>
            <td>Rp <?php echo number_format($data_detail->base_fare+$data_detail->tax); ?></td>
          </tr>
          <tr>
            <th>NTA:</th>
            <td>Rp <?php echo number_format($data_detail->NTA); ?></td>
          </tr>
          <tr>
            <th>Profit:</th>
            <td>Rp <?php echo number_format($data_detail->base_fare+$data_detail->tax-$data_detail->NTA); ?></td>
          </tr>
        </table>
      </div>
    </div>
    <!-- /.col -->
    
    <div class="col-xs-12 col-md-6">
      <p class="lead">Booking status:</p>

      <div class="table-responsive">
        <table class="table">
        <?php foreach($status as $val){ ?>
          <tr>
            <th style="width:20%"><?php echo "<span class='label' style='background-color:".$color[$val->status]."; font-size:0.9em'>".$val->status."</span>" ?></th>
            <td><?php echo date("d-m-Y H:i:s",$val->{'time status'}); ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

  <!-- this row will not appear when printing -->
  <div id="warning"></div>
  <?php if($this->session->flashdata('message')!=NULL) echo "<div id='warning' class='alert alert-success'>".$this->session->flashdata('message')."</div>"; ?>
  <div class="row no-print">
    <div class="col-xs-12">
      <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
      <?php if($status[0]->status=='booking'){ ?>
      <button id="issued" type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment </button>
      <?php } ?>
    </div>
  </div>
  <input type="hidden" id="id" value="<?php echo $id_booking ?>"/>
</section>
<!-- /.content -->