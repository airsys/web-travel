
<div id="result-content" class="box box-primary center-block" style="width: 100%">
	<div class="box-header with-border">
		<h3 class="box-title">Retrieve List</h3>
	</div><!-- /.box-header -->
	<div class="box-body">
		<div id="alert"></div>
		<div id="result">
			<div class="box-body table-responsive no-padding">
			  <table class="table table-hover table-striped">
			    <tr>
			      <th>#</th>
			      <th>select</th>
			      <th>airline</th>
			      <th>booking_code</th>
			      <th>area_depart</th>
			      <th>area_arrive</th>
			      <th>name</th>
			      <th>phone</th>
			      <th>booking_time</th>
			      <th>time_limit</th>
			      <th>payment_status</th>
			      <th>base_fare</th>
			      <th>NTA</th>
			      <th>adult</th>
			      <th>child</th>
			      <th>infant</th>
			    </tr>
			    <?php
			    $i=0;
				foreach($data as $value){ $i++;?>
			    <tr>
			      <td><?php echo $i; ?></td>
			      <td><a href="<?php echo (base_url().'airlines/retrieve/'. $value->booking_code) ?>" class="btn btn-social-icon btn-twitter"><i class="fa fa-eye"></i></a></td>
			      <td><?php echo $value->airline; ?></td>
			      <td><?php echo $value->booking_code ?></td>
			      <td><?php echo $value->area_depart ?></td>
			      <td><?php echo $value->area_arrive ?></td>
			      <td><?php echo $value->name ?></td>
			      <td><?php echo $value->phone ?></td>
			      <td><?php echo $value->booking_time ?></td>
			      <td><?php echo $value->time_limit ?></td>
			      <td><?php echo $value->payment_status ?></td>
			      <td><?php echo $value->base_fare ?></td>
			      <td><?php echo $value->NTA ?></td>      
			      <td><?php echo $value->adult ?></td>
			      <td><?php echo $value->child ?></td>
			      <td><?php echo $value->infant ?></td>
			    </tr>
			    <?php } ?>
			    
			  </table>
			</div>
			<!-- /.box-body -->
		</div>
	</div>
</div><!-- /.box-body -->

<script>
$(document).ready(function(){
	$("#form").on("submit", function(event) {
		$("#result").empty();
		$(over).appendTo("#cari");
		event.preventDefault(); 
		$.ajax({
            url:  base_url+"airlines/retrieve_list_table",
            type: "post",
            data: $(this).serialize(),
            success: function(d) {
                $('#overlay').remove();
                $(d).appendTo($("#result"));
            },
             error: function (request, status, error) {
                $('#overlay').remove();
                showalert(request.responseText,'warning');
            },
             complete: function() {
        		$("#result-content").show();
            }
        });
	});
});
</script>