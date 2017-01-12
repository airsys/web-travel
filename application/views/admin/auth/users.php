<style>
	body {
	margin: 0;
	background-color: #F0F0F0;
	font-family: 'Liberation Sans', Arial, sans-serif;
}
#spreadsheet {
	border-collapse: collapse;

	counter-reset: row col;
}

#spreadsheet th,
#spreadsheet td {
	width: 10em;
	min-width: 10em;
	height: 2em;
	border: 1px solid #C5C5C5;
}
.checkbox{
	margin-top: 0px;
    margin-bottom: 0px;
}
</style>
<!-- Horizontal Form -->
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Users List</h3>
    </div>
    	
		<div class="box-body table-responsive ">
			<div class="input-group col-md-6">
		        <div class="input-group-btn">
		          <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown">
		          	<span id="f_type">SEARCH</span>
		            <span class="fa fa-caret-down"></span></button>
		          <ul class="dropdown-menu">
		            <li id="f_all"><a href="#">All</a></li>
		            <li class="divider"></li>
		            <li id="f_email"><a href="#">Email</a></li>
		            <li id="f_fullname"><a href="#">Full name</a></li>
		            <li id="f_registeron"><a href="#">Register on</a></li>
		          </ul>
		        </div>
		        <!-- /btn-group -->
		        <input id="f_text" type="text" readonly placeholder="" class="form-control">
		        <span class="input-group-btn">
                  <button id="go" type="button" class="btn btn-info btn-flat">Go!</button>
                </span>
		    </div><br>
		<table class="table table-hover table-striped" id="spreadsheet">
			<tr>
				<th>Company</th>
				<th>Email</th>
				<th>Full Name</th>
				<th>Phone</th>
				<th>Register On</th>
				<th>Action</th>
			</tr>
			
		</table><br>			
    		<div id="warning"></div>
			<div id="loading" class="form-group">
				<img class="center-block" style="height: 80px;" src="<?= base_url() ?>assets/dist/img/loading.gif">
			</div>
			<div class="form-group text-center">
				<button style="margin: 20px;" class="btn btn-primary btn-sm" id="load-more" >Load More</button>
			</div>
		</div>
  </div>
  <!-- /.box -->
 
 <script>
 	$(document).ready(function() {
		var win = $(window);
		var doc = $(document);
		var limit = 40;
		var offset = 0;
		var load = true;
		// Each time the user scrolls
		win.scroll(function() {
			// Vertical end reached?
			if (doc.height() - win.height() == win.scrollTop()) {
				if(load) ajax_get();
			}
		});
		$('#load-more').click(function() {
			if(load) ajax_get();
		});
		
		$('#go').click(function() {
			$("#spreadsheet").find("tr:gt(0)").remove();
			limit = 2; offset = 0;
			ajax_get();
		});
		$('#f_email').click(function() {
			change_pos('EMAIL',false)
		});
		$('#f_fullname').click(function() {
			change_pos('FULL NAME',false)
		});
		$('#f_registeron').click(function() {			
			change_pos('register on',false)
			$('#f_text').datepicker({
		      autoclose: true,
		      format: 'dd-mm-yyyy'
		    });
		});
		$('#f_all').click(function() {
			change_pos('search',true)
		});
		
		function change_pos(text='', hide=false){
			$('#f_type').text(text.toUpperCase());
			$("#f_text").attr("readonly", hide);
			$("#f_text").datepicker( "remove" );
			$("#f_text").val('');
		}
		
		$(".table-responsive").on("change", ".table .status", function(event){
		    if($(this).is(':checked')){
		    	action($(this).data('id'),1)
			}
			if($(this).is(':checked')==false){
				action($(this).data('id'),0)
			}
		});

		function data_append(s = '',id=''){
			var tr = $('<tr class="tr_'+id+' rowt" />').append(s).appendTo($('#spreadsheet'));
		}
		$('#loading').hide();
		function ajax_get(){
			var s ='';
			load = false;
			$('#load-more').hide();
			$('#loading').show();
			$.ajax({
			  type: 'GET',
			  url: base_url+'auth/get_username/'+limit+'/'+offset,
			  data: {
			  	type:$('#f_type').text().toLowerCase().replace(/\s/g,''),
		  		value : $("#f_text").val(),
		  	  } ,
			  dataType: 'json',
			  success: function(jsonData) {
			  	if(jsonData.length!=0){
					offset = limit+offset;
				     $.each(jsonData, function() {
				     	var checked = '';
				     	s = '<td>'+this.brand+'</td>';
				     	s += '<td>'+this.email+'</td>';
				     	s += '<td>'+this.full_name+'</td>';
				     	s += '<td>'+this.phone+'</td>';
				     	s += '<td>'+this.register_on+'</td>';
				     	if(this.active=='1')checked='checked';
				     	s += '<td>'+
				     			'<div class="checkbox"><label style="margin-right:15px;"><input data-id="'+this.id+'" class="status" type="checkbox" '+checked+'>ACTIVE</label>'+
				     			'<a href="users/'+this.id+'" class="btn btn-success btn-xs"><i class="fa fa-eye"></i></a></div>'+
				     		'</td>';
				        data_append(s,this.id);
				    });
				    load = true;
				    $('#load-more').show();
				    $('#loading').hide();
				} else {
					alert('Sudah Terload semua');
					$('#loading').hide();
				}
			  	
			  },
			  error: function() {
			    alert('Error loading');
			    load = true;
			    $('#load-more').show();
			    $('#loading').hide();
			  }
			});
		}
		ajax_get();
	});
	
	function action(id=0,status=0){
		$.ajax({
		  type: 'GET',
		  url: base_url+'auth/user_status',
		  data: {id:id,
		  		 status : status,
		  		} ,
		  dataType: 'json',
		  success: function(data) {
			showalert(data.message,'success','#warning',3000);
		  },
		  error: function(data) {
		  	showalert('Change status error','danger','#warning',3000);
		  }
		});
	}
 </script>