<?php 
 	$color = array(
 				'pending'=>'#636c70',
 				'submit'=>'#186bd1',
 				'confirm'=>'#00bd30',
 				'reject'=>'#ec1e23',
 				'cancel'=>'#d3ce0a',
 				'timeup'=>'#e7bd41',
 			);
?>
<div class="box box-primary center-block" style="width: 100%">
    <div class="box-header with-border">
      <h3 class="box-title">Topup List</h3>
    </div>
    <!-- /.box-header -->
    
  <div class="box-body">
  	<div class="table-responsive no-padding col-md-6">
	  <table class="table table-hover table-striped">
	  	<thead>
	    <tr>
	      <th class="text-center">Name</th>
	      <th class="text-center">Nominal</th>
	      <th class="text-center">Bank</th>
	      <th class="text-center">Status</th>
	    </tr>
	    </thead>
	    <?php
		foreach($data_table as $value){ ?>
	    <tr>
	      <td class="text-center"><?php echo ucwords($value->full_name) ?></td>
	      <td class='pull-right'><?php echo "nominal : ".number_format($value->nominal)."<br> unique &nbsp;&nbsp;&nbsp;: <span class='pull-right'>".$value->unique."</span> <br> TOTAL &nbsp;&nbsp;&nbsp;&nbsp;: ".number_format($value->unique+$value->nominal); ?></td>
	      <td class="text-center">
			 <?php echo $bank[$value->id_bank]->bank."-".$bank[$value->id_bank]->rek_number."-".$bank[$value->id_bank]->account_name."<br> to <br>".
			       $bank[$value->id_bank_to]->bank."-".$bank[$value->id_bank_to]->rek_number."-".$bank[$value->id_bank_to]->account_name ?>
	      </td>
	      <td class="text-center">
	      	 <?php echo "<span class='label' style='background-color:".$color[$value->status]."; font-size:0.9em'>".$value->status."</span><br>"; ?>
	      	 <?php echo "<a href='$value->id'>view<br>Detail</a>"; ?>
	      </td>
	    </tr>
	    <?php } ?>
	    
	  </table>
	</div>
  </div>
  <div class="box-footer">
  </div>
</div>