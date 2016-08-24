<script src="<?php echo base_url(); ?>assets/plugins/jQueryUI/jquery-ui.min.js"></script>
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
  padding: 3px 20px;
}
.ui-autocomplete > li.ui-state-focus {
  background-color: #DDD;
}
.ui-helper-hidden-accessible {
  display: none;
}
</style>
  <!-- Form Element sizes -->
  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">Search</h3>
    </div>
    <div class="box-body">
    	<div class="col-md-11 col-sm-6 col-xs-6">
			<div class="form-group">
				<input id="booking_code" class="form-control input-lg" type="text" placeholder="Search anything: booking code, date booking, date departure">
			</div>
		</div>
		<div class="col-md-1 col-sm-6 col-xs-6">
			<div class="form-group">
				<a href="#" id="cek" class="btn btn-info btn-flat btn-lg">CEK</a>
			</div>
		</div>
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
				      <th>booking_time</th>
				      <th>payment_status</th>
				      <th>base_fare</th>
				      <th>Passanger</th>
				    </tr>
				    <?php
				    $i=0;
					foreach($data_table as $value){ $i++;?>
				    <tr>
				      <td><?php echo $value->airline."<br><a href='".base_url()."airlines/retrieve/".$value->booking_code."'>".$value->booking_code." </a>" ?></td>
				      <td><?php echo $value->area_depart."-".$value->area_arrive ?></td>
				      <td><?php echo $value->name."<br>".$value->phone ?></td>
				      <td><?php echo $value->booking_time ?></td>
				      <td><?php echo $value->payment_status."<br>limit: ".$value->time_limit ?></td>
				      <td><?php echo number_format($value->base_fare)."<br>NTA: ".number_format($value->NTA) ?></td>
				      <td>
				      	<?php echo "A: $value->adult | C: $value->child | I: $value->infant" ?>
				      	
				      </td>
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
          <p class="lead">Payment Time Limit <b><?php echo date("d-m-Y", $data_detail->time_limit); ?> | <?php echo date("H:i", $data_detail->time_limit); ?></b><br>
          Payment Status <b><?php echo $data_detail->payment_status; ?></b></p>

          <div class="table-responsive">
            <table class="table">
              <tr>
                <th style="width:50%">base fare:</th>
                <td>Rp <?php echo number_format($data_detail->base_fare); ?></td>
              </tr>
              <tr>
                <th>Bonus:</th>
                <td>Rp <?php echo number_format($data_detail->base_fare-$data_detail->NTA); ?></td>
              </tr>
              <tr>
                <th>NTA:</th>
                <td>Rp <?php echo number_format($data_detail->NTA); ?></td>
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
    //	$(document).ready(function(){
		    function split( val ) {
		      return val.split( /, / ); // /,\s*/
		    }
		    function extractLast( term ) {
		      return split( term ).pop();
		    }
				    
			$("#booking_code").on( "keydown", function( event ) {
				sourced = ['booking code:','date arrived:','date depature:','date booking:'];
		        if ( event.keyCode === $.ui.keyCode.TAB &&
		            $( this ).autocomplete( "instance" ).menu.active ) {
		          	event.preventDefault();
		        }
		    }).autocomplete({
		        minLength: 0,
		        source: function( request, response ) {
		          // delegate back to autocomplete, but extract the last term
		          response( $.ui.autocomplete.filter(
		            sourced , extractLast( request.term ) ) );
		        },
		        focus: function() {
		          // prevent value inserted on focus
		          $(this).data("autocomplete").search($(this).val());
		          return true;
		        },
		        select: function( event, ui ) {
		          var terms = split( this.value );
		          // remove the current input
		          terms.pop();
		          // add the selected item
		          terms.push( ui.item.value );
		          // add placeholder to get the comma-and-space at the end
		          terms.push( "" );
		          this.value = terms.join( ", " );
		          var length = $(this).val().length-2;
		          $(this).selectRange(length);
		          return false;
		        }
		    });
		
		$.fn.selectRange = function(start) {
		    return this.each(function() {
		        if (this.setSelectionRange) {
		            this.focus();
		            this.setSelectionRange(start, start);
		        } else if (this.createTextRange) {
		            var range = this.createTextRange();
		            range.collapse(true);
		            range.moveEnd('character', start);
		            range.moveStart('character', start);
		            range.select();
		        }
		    });
		};

    	
    	var href = '';
    	var booking_code ='';
    	href = window.location.href;
    	if(href.indexOf("q=")===-1){
			booking_code = href.substr(href.lastIndexOf('/') + 1);
    		if(booking_code !='retrieve')$("#booking_code").val(booking_code);
		}else {
			booking_code = href.substr(href.lastIndexOf('=') + 1);
			$("#booking_code").val(decodeURI(booking_code));
		}    	
    	$("#cek").on("click", function(event) {
    		if($("#booking_code").val().indexOf(",")===-1)
    			window.location = base_url+"airlines/retrieve/"+$("#booking_code").val();
    		else window.location = base_url+"airlines/retrieve?q="+$("#booking_code").val();
    	});
    	
    //	});
    	
    </script>