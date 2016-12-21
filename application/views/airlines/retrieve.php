<?php 
    $color = array(
                'booking'=>'#636c70',
                'issued'=>'#00bd30',
                'cancel'=>'#d3ce0a',
                'expired'=>'#e7bd41',
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
.tag-editor{
  height: 34px;
}

</style>
<!--
 <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">Search Code Booking</h3>
    </div>
    <div class="box-body">
        <div class="btn-group col-md-8 col-sm-8 col-xs-8">
                  <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown"><i class="fa fa-search"></i> Filter 
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Status</a></li>
                    <li><a href="#" onclick="$('#booking_code').tagEditor('addTag', 'booking code:');" >Code Booking</a></li>
                    <li><a href="#" >Airlines</a></li>
                    <li><a href="#">Leaving From</a></li>
                    <li><a href="#">Going To</a></li>
                    <li><a href="#" onclick="$('#booking_code').tagEditor('addTag', 'date booking:');">Date Booking</a></li>
                    <li><a href="#">Date Depature</a></li>
                    
                  </ul>
        
                <div class="form-group" style="height: 34px;">
                    <input id="booking_code" class="form-control" type="text" placeholder="Search anything: booking code, date booking, date departure" >
                    <input type="hidden" id="dp" />
                </div>
                
        </div>
        <div class="col-md-1 col-sm-2 col-xs-2">
            <div class="form-group">
                <a href="#" id="cek" class="btn btn-info btn-flat">CEK</a>
            </div>
        </div>
        </div>
    
   
  </div> --> <!-- /.box-body -->
  <!-- /.box -->

<!-- Search for date booking -->
  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">Search </h3>
    </div>
    <div class="box-body">
        <div class="btn-group col-md-8 col-sm-8 col-xs-12">
                  <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" 
                  style="margin-bottom: 20px;"><i class="fa fa-search"></i> Filter 
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu" style="top: 40px;">
                    <li id="filterStatus"><a href="#">Your Status</a></li> 
                    <li><a href="#" onclick="addfilterAirline()">Your Airlline</a></li>
                    <li><a href="#" onclick="addfilterBookingcode()">Your Booking Code</a></li>
                    <li><a href="#" onclick="date_booking()">Your Date Booking</a></li>
                    <li><a href="#" onclick="date_depart()">Your Date Depart</a></li>
                    <li class="divider"></li>
                    <li> <center><strong>Flight Route</strong></center></li>
                    <li><a href="#" onclick="addfilterAreadepart()">Leaving From</a></li>
                    <li><a href="#" onclick="addfilterAreaarrive()">Going To</a></li>
                   
                  </ul>
        
                <div class="form-group" >
                    <input id="booking_code" class="form-control" type="text" value="status:booking" style="width: 85%;color: rgba(119, 119, 119, 0.76);">
                    <input type="hidden" id="dp" />
                </div>
                
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
          <a href='<?php echo base_url()."airlines/invoice/$data_detail->booking_code" ?>' target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print Invoice</a>
          <?php if($status[0]->status=='booking'){ ?>
          <button id="issued" type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment </button>
          <?php }else{ ?>
          <a href='<?php echo base_url()."airlines/eticket/$data_detail->booking_code" ?>' target="_blank" class="btn btn-primary pull-right"><i class="fa fa-print"></i> Print Ticket</a>
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
            /*
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
                    removeDuplicates:true,
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
            */
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

<!--costum-->
 <script type="text/javascript">
$(document).ready(function(){
    $("#filter a").click(function(){
        var value = $(this).html();
        var def = document.getElementById("booking_code").value;
        var input = $('#booking_code');
        input.val(def+','+' '+value+':');
    });
})
function addfilterAreaarrive() {
    var filter=document.getElementById("booking_code").value;
    filter=filter+',' + ' ' + 'area arrive:';
    document.getElementById("booking_code").value=filter;
    }
$(document).ready(function(){
    $("#filterStatus a").click(function(){
        var value = $(this).html();
        var input = $('#booking_code');
        input.val('status:booking' );
    });
})
function addfilterAirline() {
    var filter=document.getElementById("booking_code").value;
    filter=filter+',' + ' ' + 'airline:';
    document.getElementById("booking_code").value=filter;
    }
function addfilterBookingcode() {
    var filter=document.getElementById("booking_code").value;
    filter=filter+',' + ' ' + 'booking code:';
    document.getElementById("booking_code").value=filter;
    }
function addfilterAreadepart() {
    var filter=document.getElementById("booking_code").value;
    filter=filter+',' + ' ' + 'area depart:';
    document.getElementById("booking_code").value=filter;
    }
function date_booking() {
    $('#dp').datepicker({
            changeMonth: true,
            changeYear: true,
            showOn: 'both',
            dateFormat: 'dd-mm-yy',
             onSelect: function (date) {                              
                      var value=document.getElementById("booking_code").value;
                      var dp=document.getElementById("dp").value;
                      datedp=value+', '+'date booking:'+dp ;
                      document.getElementById("booking_code").value=datedp;
                      },
    });
    $('#dp').datepicker('show');
                          
    }
function date_depart() {
    $('#dp').datepicker({
            changeMonth: true,
            changeYear: true,
            showOn: 'both',
            dateFormat: 'dd-mm-yy',
             onSelect: function (date) {                              
                      var value=document.getElementById("booking_code").value;
                      var dp=document.getElementById("dp").value;
                      datedp=value+', '+'date depart:'+dp ;
                      document.getElementById("booking_code").value=datedp;
                      },
    });
    $('#dp').datepicker('show');
                          
    }
</script>