<style>
	table {
    	border-collapse: collapse;
    	table-layout: auto;
	}

	table, th, td {
	    border: 0px solid black;
	    padding: 8px;
	}
	td {
	 white-space: nowrap;
	}
</style>

<div style="padding: 20px; ">
<img height="50px" src="<?php echo base_url().'assets/dist/img/logo-company.jpg' ?>"/>
<table>
	<tr>
		<td>
			<span>From</span><br />
			<strong><?php echo $bandara[$data_detail->area_depart]['city'] ?><br>
	        		<?php echo $bandara[$data_detail->area_depart]['name_airport'].'-'. $bandara[$data_detail->area_depart]['code_route']; ?>
	        </strong><br>
		</td>
		<td>
			<span>To</span><br />
			<strong><?php echo $bandara[$data_detail->area_arrive]['city'] ?><br>
	        		<?php echo $bandara[$data_detail->area_arrive]['name_airport'].'-'. $bandara[$data_detail->area_arrive]['code_route']; ?>
	        </strong><br>
		</td>
		<td style="text-align: right; width:300px;">
			<span style="font-size: 20px;">Booking Code:</span><br>
			<span style="font-size: 28px; color:#21d321">
				<b><?php echo $data_detail->booking_code; ?></b>
			</span>
		</td>
	</tr>
</table>
<h3>Itinerary List</h3>
<table width="1000px" style="">
    <thead>
    <tr style="font-weight: bold;">
      <td>#</td>
      <td>Flight ID</td>
      <td>Class</td>
      <td>Area Depart</td>
      <td>Date Depart</td>
      <td>Time Depart</td>
      <td>Area Arrive</td>
      <td>Area Arrive</td>
      <td>Time Arrive</td>
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
 <br />
 <h3>Passanger List</h3>
 <table width="800px" >
    <thead>
    <tr style="font-weight: bold;">
      <td>#</td>
      <td>Name</td>
      <td>Passenger Type</td>
      <td>Ticket No.</td>
    </tr>
    </thead>
    <tbody>
    	<?php 
    		$i=0;
    		foreach($data_detail->passenger_list as $val){ 
    			$i++;
    		$color='#ebebeb';
    		if($i%2==0)$color='#ebebeb';
    	?>
    		 <tr bgcolor="<?php echo $color; ?>">
              <td><?php echo $i ?></td>
              <td><?php echo $val->name ?></td>
              <td><?php echo $val->passenger_type ?></td>
              <td><?php echo $val->ticket_no ?></td>
            </tr>
    	<?php } ?>
    </tbody>
  </table>
  <br>
  <img src="<?php echo base_url().'assets/dist/img/ticket_peringatan.jpg' ?>" align="middle;" width="600px;"/>
</div>