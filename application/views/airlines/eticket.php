<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>INDSITI | E TICKET</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
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
<div class="wrapper" style="width: 800px;">
<!-- Main content -->
<section class="invoice">
  <!-- title row -->
  <div class="row">
    <div class="col-xs-12">
      
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
    	<h2 class="page-header">
		  Booking Code: <br><b style="color:#21d321;"><?php echo $data_detail->booking_code; ?></b>
		</h2>
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
      
    </div>
    <!-- /.col -->
    
    <div class="col-xs-12 col-md-6">
      
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