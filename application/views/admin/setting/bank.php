<div class="box box-primary" style="width: 100%">
    <div class="box-header with-border">
      <h3 class="box-title">Bank Account</h3>
    </div>
    <!-- /.box-header -->
    
  <div class="box-body">
  	<div id="warning"></div>
  	<div class="form-group">
  		<a href="<?php echo base_url().'admin/setting/bank_add' ?>" type="button" class="btn btn-success"><li class="fa fa-plus"></li>&nbsp;ADD</a>
  	</div>
  	<div class="table-responsive no-padding">
	  <table class="table table-hover table-striped">
	  	<thead>
	    <tr>
	      <th class="text-center">Account name</th>
	      <th class="text-center">Rek number</th>
	      <th class="text-center">Bank</th>
	      <th class="text-center">Status</th>
	      <th class="text-center">Action</th>
	    </tr>
	    </thead>
	    <?php
		foreach($bank as $key => $value){ ?>
	    <tr>
	      <td class="text-center"><?php echo ucwords($value['account name']) ?></td>
	      <td class='text-center'><?php echo $value['rek number'] ?></td>
	      <td class='text-center'><?php echo $value['bank'] ?></td>
	      <td class='text-center'><?php echo ($value['enable']==1 ? "ENABLE" : "DISABLE"); ?></td>
	      <td class='text-center'><a href="<?php echo base_url()."admin/setting/bank_detail/".$key ?>" type="button" class="btn btn-success btn-sm"><li class="fa fa-eye"></li></a></td>
	    </tr>
	    <?php } ?>	    
	  </table>
	</div>
  </div>
  <div class="box-footer">
  </div>
</div>