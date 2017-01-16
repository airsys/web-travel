<?php //print_r($payfor); ?>
<!-- Jquery Tag Editor -->
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/plugins/daterangepicker/daterangepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>

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
                    <input type="text" class="form-control" id="filter_daterange" value="<?php echo $date_range; ?>" name="filter_daterange" 
                     style="margin-bottom: 10px;" placeholder="pick your date" >
                   </div>
                   
                   <div class="col-md-12 col-sm-12 col-xs-12">
                    <a href="#" id="search" onclick="filter_cari()" class="btn btn-info btn-flat" style="margin-top:10px;margin-bottom:10px;margin-right: 10px;"><i class="fa fa-search"></i> | Search</a>
                    
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
			<h3 class="box-title">Transaction List</h3>
		</div><!-- /.box-header -->
		<div class="box-body">
			<div id="alert"></div>
			<div id="">
				<div class="box-body table-responsive no-padding">
				  <table class="table table-hover table-striped">
				    <tr>
				      <th>Code</th>
				      <th>Credit</th>
				      <th>Debet</th>
				      <th>Pay for</th>
				      <th>Date</th>
				      <th>Detail</th>
				    </tr>
				    <?php
				    $i=0;
					foreach($data_table as $value){ $i++;?>
				    <tr>
				      <td><?php echo $value->code ?></td>
				      <td><?php echo number_format($value->credit) ?></td>
				      <td><?php echo number_format($value->debet) ?></td>
				      <td><?php echo $value->{'payfor'}; ?></td>
				      <td><?php echo $value->created ?></td>
				      <td>
				      	<?php 
				      		$link = '';
				      		if($value->code=='CT') $link = base_url()."payment/topup_list/".$value->{'pay for'}."/finance";
				      		if($value->code=='DI') $link = base_url()."airlines/retrieve/".$value->{'payfor'}."/finance";
				      	?>
				      	<a href="<?php echo $link ;?>" type="button" class="btn btn-success btn-sm"><li class="fa fa-eye"></li></a>
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
  
<script>
$(document).ready(function(){
	$('#filter_daterange').daterangepicker(
				{
					"opens": "right",
					'autoApply': true,
					locale: {format: 'DD/MM/YYYY'},
				}
	);

	var href = '';
            var reservation ='';
            href = window.location.href;
            if(href.indexOf("range=")===-1){
                reservation = href.substr(href.lastIndexOf('/') + 1);
                if(reservation !='finance')$("#reservation").val(reservation.replace('#',''));
            }else {
                reservation = href.substr(href.lastIndexOf('=') + 1);
                $("#reservation").val(decodeURI(reservation.replace('#','')));
            }  



	$("#search, #cek").on("click", function(event) {
	                window.location = base_url+"report/finance?range="+$("#reservation").val();
	});

	
	$('.dropdown-menu').click(function(event){
     	event.stopPropagation();
 	});
});
	function filter_cari() {
	    var result =document.getElementById("reservation").value;
	  
	    var DR=document.getElementById("filter_daterange").value;

        if (DR!='') {
          filter='range:'+DR;
        }
	    
	    document.getElementById("reservation").value=filter;
	}
 
</script>