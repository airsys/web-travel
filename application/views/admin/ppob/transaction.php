<?php //print_r($payfor); 
	  $color = array(
	        'processing'=>'#0145d1', //sedang diproses
	        'succes'=>'#00bd30', //berhasil
	        'waiting SN'=>'#d3ce0a', //menunggu SN operator
	        'refund'=>'#fc2cae', //refund
	        'failed'=>'#ff2025', //faild
	    );
       $tulisan = array(
                '1111'=>'processing', //sedang diproses
                '0'=>'succes', //berhasil
                '2222'=>'waiting SN', //menunggu SN operator
                '1001'=>'refund', //refund
                '999'=>'failed', //faild
            ); 
?>
<!-- Jquery Tag Editor -->
<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/plugins/daterangepicker/daterangepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/daterangepicker/daterangepicker.js"></script>
<script src="<?php echo base_url(); ?>assets/plugins/datepicker/bootstrap-datepicker.js"></script>

  <!-- Form Element sizes -->
  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">Date Filter</h3>
    </div>
    <div class="box-body">
    	 <!-- Date range -->
          <div class="form-group">
            <label>Date range:</label>

            <div class="input-group col-md-3">
              <div class="input-group-addon">
                <i class="fa fa-calendar"></i>
              </div>
              <input type="text" value="<?php echo $date_range; ?>" class="form-control pull-right" id="reservation">
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
				      <th>Company</th>
				      <th>Product</th>
				      <th>MSISDN</th>
				      <th>Note</th>
				      <th>Ref TRXID</th>
				      <th>TRXID</th>
				      <th>Status</th>
				      <th>Date</th>
				      <th>Refund</th>
				    </tr>
				    <?php
				    $i=0;
					foreach($data_table as $value){ $i++;?>
				    <tr id=<?= $value->ref_trxid ?>>
				      <td><?php echo $value->brand ?></td>
				      <td><?php echo $value->product ?></td>
				      <td><?php echo $value->msisdn ?></td>
				      <td><?php echo $value->note ?></td>
				      <td><?php echo $value->ref_trxid ?></td>
				      <td><?php echo $value->trxid ?></td>
				      <td><?php echo "<span class='label' style='background-color:".$color[$value->status]."; font-size:0.9em'>".$value->status."</span>" ?></td>
				      <td><?php echo $value->created2 ?></td>
				      <td>
				      	<?php if($value->status!='refund' && $value->status!='failed'){ ?>
				      	<button class="btn btn-sm btn-danger" onclick="show('<?= $value->ref_trxid ?>');" ><i class="fa fa-minus-square-o"></i></button>
				      	<?php } ?>
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
    <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Note</h4>
      </div>
      <div class="modal-body">
        <textarea id="note" class="form-control"></textarea>
      	<input type="hidden" value="" id="id"/>
      </div>
      <div class="modal-footer">
        <button type="button" onclick="action($('#id').val(),0,$('#note').val());" class="btn btn-danger" data-dismiss="modal">Save</button>
      </div>
    </div>

  </div>
</div>
  
 
<script>
function show(id=0){
		$('#id').val(id)
		$('#note').val('')		
		$('#myModal').modal('show');
	}
function action(id=0,st=1,nt=''){
	//alert(id+'-'+st+'-'+nt);
	
	$.ajax({
	  type: 'POST',
	  url: base_url+'ppob/refund',
	  data: {ref_trxid:id, 
	  		 note : nt,
	  		} ,
	  dataType: 'json',
	  success: function(data) {
	  	$('#'+id).css({"background-color": "#fe5667"});
		$('#'+id).fadeTo("slow",0.02, function(){
	        $(this).remove();
	    });
	  },
	  error: function() {
	    alert('Error confirm');
	  }
	});	
}

$(document).ready(function(){
	
	$('#reservation').daterangepicker(
				{
					"opens": "right",
					'autoApply': true,
					locale: {format: 'DD/MM/YYYY'},
				}
	).on('apply.daterangepicker', function(ev, p){
          window.location = base_url+"ppob/transaction?range="+$(this).val();
    });
});
</script>