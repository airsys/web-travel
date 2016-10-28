<?php 
 	$color = array(
 				'booking'=>'#636c70',
 				'issued'=>'#00bd30',
 				'cancel'=>'#d3ce0a',
 				'timeup'=>'#e7bd41',
 			);
?>
<!-- Jquery Tag Editor -->
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/plugins/jquery.tag-editor/jquery.tag-editor.css" />
<script src="<?php echo base_url(); ?>assets/plugins/jquery.tag-editor/jquery.tag-editor.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/jquery.tag-editor/jquery.caret.min.js"></script>
<style>
	.ui-autocomplete {
    position: absolute;
    z-index:1000 !important;
    cursor: default;
    padding: 0;
    margin-top: 2px;
    list-style: none;
    background-color: #ffffff;
    border: 1px solid #ccc
    -webkit-border-radius: 5px;
       -moz-border-radius: 5px;
            border-radius: 5px;
    -webkit-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
       -moz-box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
}
.ui-autocomplete > li {
  padding: 3px 10px;
}
.ui-autocomplete > li.ui-state-focus {
  background-color: #DDD;
}
.ui-helper-hidden-accessible {
  display: none;
}
</style>
  <!-- Form Element sizes -->
  <?php if($this->uri->segment(4)!='finance'){ ?>
  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">Search</h3>
    </div>
    <div class="box-body">
    	<div class="col-md-8 col-sm-8 col-xs-8">
			<div class="form-group">
				<input id="booking_code" class="form-control" type="text" placeholder="Search anything: booking code, date booking, date departure">
			</div>
			<input type="hidden" id="dp" />
		</div>
		<div class="col-md-1 col-sm-2 col-xs-2">
			<div class="form-group">
				<a href="#" id="cek" class="btn btn-info btn-flat">CEK</a>
			</div>
		</div>
    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.box -->
  <?php } ?>
  
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
				      <th>Payment limit</th>
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
				      <td><?php echo date("d-m-Y H:i:s",$value->{'time limit'}) ?></td>
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
  
 <?php if($data_detail != NULL){ ?>
   
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
          <?php if($this->uri->segment(4)!='finance'){ ?>
	          <div class="btn-group">
				<button type="button" class="btn btn-primary btn-block dropdown-toggle" data-toggle="dropdown">
				    <span class="col-lg-10">
				        <i class="fa fa-print"></i> Print Invoice  <span class="caret"></span>
				    </span>
				</button>
				<ul class="dropdown-menu btn-block" role="menu">
					<li><a href='<?php echo base_url()."airlines/invoice/$data_detail->booking_code/pdf" ?>' target="_blank"><i class="fa fa-file-pdf-o"></i> PDF</a></li>
					<li><a href='<?php echo base_url()."airlines/invoice/$data_detail->booking_code" ?>' target="_blank"><i class="fa fa-external-link"></i> WEB</a></li>
					<li><a href="#"><i class="fa fa-envelope-o"></i> Email</a></li>
				</ul>
			  </div>
			  
	          <?php if($status[0]->status=='booking'){ ?>
	          <button id="issued" type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment </button>
	          <?php }else{ ?>
	          <div class="btn-group pull-right">
				<button type="button" class="btn btn-danger btn-block dropdown-toggle" data-toggle="dropdown">
				    <span class="col-lg-10">
				        <i class="fa fa-print"></i> Print Ticket  <span class="caret"></span>
				    </span>
				</button>
				<ul class="dropdown-menu btn-block" role="menu">
					<li><a href='<?php echo base_url()."airlines/eticket/$data_detail->booking_code" ?>' target="_blank"><i class="fa fa-file-pdf-o"></i> PDF</a></li>
					<li><a href='<?php echo base_url()."airlines/eticket/$data_detail->booking_code/web" ?>' target="_blank"><i class="fa fa-external-link"></i> WEB</a></li>
					<li><a href="#"><i class="fa fa-envelope-o"></i> Email</a></li>
				</ul>
			  </div>
			  <?php } ?>
          <?php } else { ?>
          	  <a href='#' onclick="window.history.go(-1); return false;" class="btn btn-info"><i class="fa fa-arrow-circle-left"></i> Back</a>
          <?php } ?>
          
        </div>
      </div>
      <input type="hidden" id="id" value="<?php echo $id_booking ?>"/>
    </section>
    <!-- /.content -->
    <?php } ?>
    
    <script>
    	$(document).ready(function(){
			var href = '';
	    	var booking_code ='';
	    	href = window.location.href;
	    	if(href.indexOf("q=")===-1){
				booking_code = href.substr(href.lastIndexOf('/') + 1);
	    		if(booking_code !='retrieve')$("#booking_code").val(booking_code.replace('#',''));
			}else {
				booking_code = href.substr(href.lastIndexOf('=') + 1);
				$("#booking_code").val(decodeURI(booking_code.replace('#','')));
			}  
			
			$('#booking_code').tagEditor(
				{
				  	autocomplete:{ 
	  					'source': ['booking code:','contact name:','date booking:'],
	  					select: function( event, ui ) {
	  						if(ui.item.value.indexOf('date') !== -1){
								$('#dp').datepicker({
					        		changeMonth: true,
					        		changeYear: true,
					        		showOn: 'both',
									dateFormat: 'dd-mm-yy',
									onSelect: function(date) {								
										 var tags = $('#booking_code').tagEditor('getTags')[0].tags;					 
					    				 $('#booking_code').tagEditor('addTag',ui.item.value+date);
					    				 $('#booking_code').tagEditor('removeTag', tags[tags.length-1]);
					    				 $(this).datepicker("destroy");
									},
								});
		  						$('#dp').datepicker('show');
							}				  						
					  	},
				  	},
				  	removeDuplicates:false,
				  	clickDelete: true,
				  	placeholder: 'Search anything: booking code, date booking, date departure',
				  	forceLowercase:false,
				  	onChange: function(field, editor, tags, tag, val) {
						$(document).keypress(function(event){
						  	$('#dp').datepicker('hide');
						  	$('#dp').datepicker("destroy");
						});
				    },
				}
			);
			
    	$("#cek").on("click", function(event) {
    		if($("#booking_code").val().search(/:|,/)===-1)
    			window.location = base_url+"airlines/retrieve/"+$("#booking_code").val();
    		else window.location = base_url+"airlines/retrieve?q="+$("#booking_code").val();
    	});
    	
    	$("#issued").on("click", function(event) {
    		if(confirm("Are you sure want to make issued?")){
	  		$.ajax({
	            url:  base_url+"payment/issued",
	            type: "post",
	            data: {
	            	'id': $('#id').val(),
	            },
	            success: function(d,textStatus, xhr) {
	               if(xhr.status==200 && d.data==1){
				   	 showalert(d.message,'success','#warning',6000000);
				   	 location.reload();
				   }
	            },
	             error: function (request, status, error) {
	             	 var err = eval("(" + request.responseText + ")");
	                 showalert(err.message,'danger','#warning',6000000);
	            }
	        });
	    }
	    else{
	        return false;
	    }
    	});
    });
    </script>