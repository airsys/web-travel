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
</style>
<!-- Horizontal Form -->
  <div class="box box-info">
    <div class="box-header with-border">
      <h3 class="box-title">Cek Nama</h3>
    </div>
		<div class="box-body table-responsive ">
		<table class="table table-hover table-striped" id="spreadsheet">
			<tr>
				<th>Airline</th>
				<th>Booking Code</th>
				<th>Route</th>
				<th>Passenger</th>
				<th>Action</th>
			</tr>
			
		</table><br>
			<div id="loading" class="form-group">
				<img class="center-block" style="height: 80px;" src="<?= base_url() ?>assets/dist/img/loading.gif">
			</div>
			<div class="form-group text-center">
				<button style="margin: 20px;" class="btn btn-primary btn-sm" id="load-more" >Load More</button>
			</div>
		</div>
  </div>
  <!-- /.box -->
  
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
		
		function data_append(s = '',id=''){
			var tr = $('<tr class="tr_'+id+'" />').append(s).appendTo($('#spreadsheet'));
		}
		$('#loading').hide();
		function ajax_get(){
			var s ='';
			load = false;
			$('#load-more').hide();
			$('#loading').show();
			$.ajax({
			  type: 'GET',
			  url: base_url+'check/get_name2/'+limit+'/'+offset,
			  dataType: 'json',
			  success: function(jsonData) {
			  	if(jsonData.length!=0){
					offset = limit+offset;
				     $.each(jsonData, function() {
				     	var p = '';
				     	s = '<td>'+this.airline+'</td>';
				     	s += '<td>'+this.booking_code+'</td>';
				     	s += '<td>'+this.from+'-'+this.to+'</td>';
				     	$.each(this.passenger, function() {
				     		p += this+'<br>';
				     	})
				     	s += '<td>'+p+'</td>';
				     	s += '<td>'+
				     			'<button style="margin-right:5px" class="btn btn-sm btn-success" onclick="action('+this.id+',1);" ><i class="fa fa-check-square-o" ></i></button>'+
				     			'<button class="btn btn-sm btn-danger" onclick="show('+this.id+');" ><i class="fa fa-minus-square-o"></i></button>'+
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
	
	function show(id=0){
		$('#id').val(id)
		$('#note').val('')		
		$('#myModal').modal('show');
	}
	
	function action(id=0,st=1,nt=''){
		$('.tr_'+id).css({"background-color": "#4afb57"});
		var status = 'verified';
		if(st == 0) {
			status = 'unverified'
			$('.tr_'+id).css({"background-color": "#fe5667"});
		};
		$.ajax({
		  type: 'POST',
		  url: base_url+'check/valid',
		  data: {id:id, 
		  		 note : nt,
		  		 status : status,
		  		} ,
		  dataType: 'json',
		  success: function(data) {
			$('.tr_'+id).fadeTo("slow",0.01, function(){
		        $(this).remove();
		    });
		  },
		  error: function() {
		    alert('Error confirm');
		  }
		});
	}
 </script>