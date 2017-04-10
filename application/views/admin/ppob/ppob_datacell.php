<?php 
	
?>
 <!-- Form Element sizes -->
  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">Cek Saldo di Datacell</h3>
    </div>
    <div class="box-body">
    	  <form action="" method="post">
          <div class="form-group">
            <div class="input-group col-md-12">
              <button type="submit" class="btn btn-lg btn-success" ><i class="fa fa-money"></i> Cek Saldo</button>
            </div>
            <!-- /.input group -->
          </div>
          <!-- /.form group -->
          <input type="hidden" name="saldo" value="1"/>
          </form>
          <?php if($message != NULL){
          	echo '<div id="alertdiv" class="alert alert-success col-md-6"><i class="close" data-dismiss="alert">X</i><span>'.$message.'</span></div>';
          } ?>
    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.box -->
  
  <?php if($data_table != NULL){?>
  	<div id="result-content" class="box box-primary center-block" style="width: 100%">
		<div class="box-header with-border">
			<h3 class="box-title">Saldo List</h3>
		</div><!-- /.box-header -->
		<div class="box-body">
			<div id="alert"></div>
			<div id="">
				<div class="box-body table-responsive no-padding">
				  <table class="table table-hover table-striped">
				    <tr>
				      <th>Saldo</th>
				      <th>Date</th>
				    </tr>
				    <?php
				    $i=0;
					foreach($data_table as $value){ $i++;
					  $date = date("d-m-Y H:i:s",$value->date)
					?>
				    <tr>
				      <td><?php echo number_format($value->saldo) ?></td>
				      <td><?php echo $date ?></td>
				    </tr>
				    <?php } ?>
				    
				  </table>
				</div>
				<!-- /.box-body -->
			</div>
		</div>
	</div><!-- /.box-body -->  
  <?php } ?>