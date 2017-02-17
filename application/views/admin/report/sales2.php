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
 <form method="post" name="form"  >
  <div class="box box-success">
    <div class="box-header with-border">
    </div>
    <div class="box-body">
    	 <!-- Date range -->
          <div class="form-group">
           
            <div class="btn-group col-md-8 col-sm-8 col-xs-12">
                  <button type="button" class="btn btn-default btn-flat dropdown-toggle" data-toggle="dropdown" 
                  style="margin-bottom: 20px;"><i class="fa fa-filter"></i> Filter 
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu" style="top: 40px;width: 100%;padding-left: 20px;padding-right: 20px;">
                     
                   <div class="col-md-6 col-sm-6 col-xs-6">
                    <label>Date Range :</label>
                    <input type="text" class="form-control" id="filter_daterange"  name="filter_daterange" onkeyup='saveValue(this);' value="<?php echo $date_range; ?>"
                     style="margin-bottom: 10px;" placeholder="pick your date" >
                   </div>
                   
                    <div class="col-md-6 col-sm-6 col-xs-6">
                    <label>Airline :</label>
                    <select class="form-control select2" id="filter_airline" name="filter_airline" style="margin-bottom: 10px;" readonly >
                      <option ></option>
                      <option data-sort="1" value="lion">Lion</option>
                      <option data-sort="2" value="garuda">Garuda</option>
                      <option data-sort="3" value="sriwijaya">Sriwijaya</option>
                      <option data-sort="4" value="citylink">Citylink</option>
                      <option data-sort="5" value="airasia">Airasia</option>
                      <option data-sort="6" value="kalstar">Kalstar</option>
                      <option data-sort="7" value="trigana">Trigana</option>
                      <option data-sort="8" value="transnusa">transnusa</option>
                    </select>
                    </div>
                
                   <div class="col-md-12 col-sm-12 col-xs-12">
                    <a href="#" id="search" onclick="filter_cari()" class="btn btn-info btn-flat" style="margin-top:10px;margin-bottom:10px;margin-right: 10px;"><i class="fa fa-search"></i> | Search</a>
                     <button type="button" onclick="clearform()" class="btn btn-warning btn-flat" style="margin-top:10px;margin-bottom:10px;"
                        data-toggle="popover" data-trigger="hover" data-content="you can clear the filter"
                        ><i class="fa fa-eraser"></i> | Clear
                      </button>
                   </div>
                  
                  </ul>
                  
                <div class="form-group" >
                    <input id="reservation" name="reservation" class="form-control" type="text" value="" style="width: 85%;color: rgba(119, 119, 119, 0.76);">
                  
                </div>
                
        </div>
            <div class="col-md-1 col-sm-2 col-xs-2">
                <div class="form-group">
                    <a href="#" id="cek" class="btn btn-info btn-flat">CEK</a>
                </div>
                </div>
         </div>
          
          <!-- /.form group -->
    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.box -->
  </form>
  <?php if($data_airline != NULL || $data_ppob != NULL){?>
  	<div id="result-content" class="box box-primary center-block" style="width: 100%">
		<div class="box-header with-border">
			<h3 class="box-title">Sales List</h3>
		</div><!-- /.box-header -->
		<div class="box-body">
			<div id="alert"></div>
			<div id="">
				<div class="box-body table-responsive no-padding">
				  <table class="table table-hover table-striped">
				    <tr>			      
				      <th>Company</th>
				      <th>Product</th>
				      <th>Code</th>
				      <th>Price</th>
				      <th>Price Agen</th>
				      <th>Info</th>
				      <th>Date</th>
				      <th>Detail</th>
				    </tr>
				    <?php
				    $i=0;
					foreach($data_airline as $v_airline){ $i++;?>
				    <tr>
				      <td><?php echo $v_airline->brand ?></td>
				      <td><?php echo "Airline<br>".$v_airline->airline ?></td>
				      <td><?php echo $v_airline->{'booking code'} ?></td>
				      <td><?php echo number_format($v_airline->{'base fare'}+$v_airline->tax)?></td>				      
				      <td><?php echo number_format($v_airline->NTA)?></td>				      
				      <td><?php echo "<strong>Tujuan:</strong>".$v_airline->{'area depart'}."-".$v_airline->{'area arrive'}.
				      				 "<strong> Contact:</strong> $v_airline->name
				      				 <strong><br>Passanger:</strong> A: $v_airline->adult | C: $v_airline->child | I: $v_airline->infant" ?></td>
				      <td><?php echo date("d-m-Y H:i:s",$v_airline->{'booking time'}) ?></td>
				      
				      <td><a href="<?php echo base_url()."admin/report/retrieve/".$v_airline->{'booking code'} ?>" type="button" class="btn btn-success btn-sm"><li class="fa fa-eye"></li></a></td>
				    </tr>
				    <?php } ?>
				    
				    <?php foreach($data_ppob as $v_ppob){ $i++;?>
				    <tr>
				      <td><?php echo $v_ppob->brand ?></td>
				      <td><?php echo "PPOB<br>".$v_ppob->kode ?></td>
				      <td><?php echo $v_ppob->{'ref trxid'} ?></td>
				      <td><?php echo number_format($v_ppob->{'net price'})?></td>			      
				      <td><?php echo number_format($v_ppob->{'base price'})?></td>			      
				      <td><?php echo "<strong>MSISDN:</strong>".$v_ppob->{'msisdn'}.
				      				 "<strong> TRXID:</strong> $v_ppob->trxid
				      				 <strong><br>SN Operator:</strong>". $v_ppob->{'sn operator'} ?></td>
				      <td><?php echo date("d-m-Y H:i:s",$v_ppob->{'created'}) ?></td>
				      
				      <td><a href="<?php echo base_url()."admin/ppob/finance/".$v_ppob->{'id'} ?>" type="button" class="btn btn-success btn-sm"><li class="fa fa-eye"></li></a></td>
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
	$('#filter_daterange').daterangepicker(
				{
					"opens": "right",
          "autoApply": true,
					locale: {format: 'DD/MM/YYYY'},
            
				}
	);
});
$("#cek").on("click", function(event) {
                window.location = base_url+"report/sales2?q="+$("#reservation").val();
        });
</script>

<SCRIPT TYPE="text/javascript">
//custom
$(document).ready(function(){

	var href = '';
            var reservation ='';
            href = window.location.href;
            if(href.indexOf("q=")===-1){
                reservation = href.substr(href.lastIndexOf('/') + 1);
                if(reservation !='sales_list')$("#reservation").val(reservation.replace('#',''));
            }else {
                reservation = href.substr(href.lastIndexOf('=') + 1);
                $("#reservation").val(decodeURI(reservation.replace('#','')));
            }  



	$("#search").on("click", function(event) {
	                window.location = base_url+"report/sales2?q="+$("#reservation").val();
	});

	$('[data-toggle="popover"]').popover(); 
	$('.dropdown-menu').click(function(event){
     	event.stopPropagation();
 	});
});
	function filter_cari() {
	    var result =document.getElementById("reservation").value;
	    var AL=document.getElementById("filter_airline").value;
	    var DR=document.getElementById("filter_daterange").value;

        if (AL=='' && DR!='') {
          filter='range:'+DR;
        }
        if (AL!='' && DR=='') {
          filter='airline:'+AL;
        }
        if (AL!='' && DR!='') {
          filter='range:'+DR+', '+'airline:'+AL;
        }
        
	    
	    document.getElementById("reservation").value=filter;
	}
  window.onload = function() {
    var selItem1 = sessionStorage.getItem("SelItem1");  
      $('#filter_airline').val(selItem1);
  }
      $('#filter_airline').change(function() { 
          var selVal1 = $(this).val();
          sessionStorage.setItem("SelItem1", selVal1);
      });
  function clearform(){
    var DR=document.getElementById("filter_daterange").value="";
     return window.localStorage.clear();
  }



function saveValue(e){
  document.getElementById("filter_daterange").value = getSavedValue("filter_daterange");
            var id = e.id;  
            var val = e.value; 
            localStorage.setItem(id, val);
        }
function getSavedValue  (v){
            if (localStorage.getItem(v) === null) {
                return "";
            }
            return localStorage.getItem(v);
        }
</SCRIPT>