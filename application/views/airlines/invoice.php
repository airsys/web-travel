<?php 
 	$color = array(
 				'booking'=>'#636c70',
 				'issued'=>'#00bd30',
 				'cancel'=>'#d3ce0a',
 				'timeup'=>'#e7bd41',
 			);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>INDSITI | Invoice</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/AdminLTE.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<div class="wrapper">
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
          <th>Class</th>
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
        		  <td><?php echo $val->code ?></td>
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
          <th>Ticket No.</th>
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
	              <td><?php echo $val->ticket_no ?></td>
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
            <th style="width:50%">Base Fare:</th>
            <td>Rp <?php echo number_format($data_detail->base_fare); ?></td>
          </tr>
          <tr>
            <th>Tax:</th>
            <td>Rp <?php echo number_format($data_detail->tax); ?></td>
          </tr>
          <tr>
            <th>Total:</th>
            <td>Rp <?php echo number_format($data_detail->base_fare+$data_detail->tax); ?></td>
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
</section>
<!-- /.content -->
</div>
<!-- ./wrapper -->
</body>
</html>