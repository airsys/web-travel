<?php 
	//print_r($data);
?>
<div class="search-box-wrapper">
    <div class="search-box container">
        <ul class="search-tabs clearfix">
            <li class="active"><a href="#flights-tab" data-toggle="tab">FLIGHTS</a></li>
        </ul>
        <div class="visible-mobile">
            <ul id="mobile-search-tabs" class="search-tabs clearfix">
                <li class="active"><a href="#flights-tab">FLIGHTS</a></li>
            </ul>
        </div>
        
        <div class="search-tab-content" id='cari'>
            <div class="tab-pane fade active in" id="flights-tab">
            	 <form id='form' class="contact-form" action="" method="post">
                    <div class="row form-group">
                        <div class="col-md-3">
                            <label>Code Booking</label>
                            <input required type="text" value="<?php echo $data->booking_code; ?>" name="code_booking" id="code_booking" class="input-text full-width">
                        </div>
						<div class="col-md-3">
							<label>.</label>
							<button type="submit" class="btn">SEND</button>
						</div>
                    </div>	                            
                </form>

            </div>
        </div>
    </div>
</div>

<div class="container sections">
    <div class="blog-infinite">
		<div class="post">
			<div class="post-content-wrapper">
				<div class="details" id="dashboard">
				<div class="row block">
					<div class="col-sm-6 col-md-3">
                            <div class="fact blue">
                                <div class="numbers counters-box">
                                    <dl>
                                        <dt id="codeboking"><?php echo $data->booking_code; ?></dt>
                                        <dd>Code Booking</dd>
                                    </dl>
                                    <i class="icon soap-icon-card"></i>
                                </div>
                                <div class="description">
                                    <i class="icon soap-icon-longarrow-right"></i>
                                    <span>View Hotels</span>
                                </div>
                            </div>
                    </div>
					<div class="col-sm-6 col-md-3">
                            <div class="fact red">
                                <div class="numbers counters-box">
                                    <dl>
                                    	<?php 
                                    		
                                    	?>
                                        <dt><?php echo date("d-m-Y", $data->time_limit); ?><br><?php echo date("H:i", $data->time_limit); ?></dt>
                                        <dd>Payment Time Limit</dd>
                                    </dl>
                                    <i class="icon soap-icon-calendar"></i>
                                </div>
                                <div class="description">
                                    <i class="icon soap-icon-longarrow-right"></i>
                                    <span>Status payment: <?php echo $data->payment_status; ?></span>
                                </div>
                            </div>
                    </div>
					<div class="col-sm-6 col-md-3">
                            <div class="fact green">
                                <div class="numbers counters-box">
                                    <dl>
                                        <dt>Rp. <?php echo number_format($data->NTA); ?></dt>
                                        <dd>NTA</dd>
                                    </dl>
                                    <i class="icon soap-icon-features"></i>
                                </div>
                                <div class="description">
                                    <i class="icon soap-icon-longarrow-right"></i>
                                    <span>View Hotels</span>
                                </div>
                            </div>
                    </div>
					<div class="col-sm-6 col-md-3">
                            <div class="fact yellow">
                                <div class="numbers counters-box">
                                    <dl>
                                        <dt><?php echo $data->area_depart; ?> - <?php echo $data->area_arrive; ?></dt>
                                        <dd>A:<?php echo $data->adult; ?> | C:<?php echo $data->child; ?> | I:<?php echo $data->infant; ?></dd>
                                    </dl>
                                    <i class="icon soap-icon-plane"></i>
                                </div>
                                <div class="description">
                                    <i class="icon soap-icon-friends"></i>
                                    <span>.</span>
                                </div>
                            </div>
                    </div>
				</div>

				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
		
    tjq(document).ready(function($) {		
		var over = '<div id="overlay">' +
        '<img id="loading" src="http://sstravelhouse.com/blog/wp-content/uploads/2015/05/airoplane.gif"/>' +
        '</div>';
		 $("#form").on("submit", function(event) {
				$(over).appendTo("#cari");
                event.preventDefault(); 
                $.ajax({
                    url: base_url()+"Lion/booking_detail",
                    type: "post",
                    data: {
						code : $('#code_booking').val()
						},
                    success: function(d) {
						$('#overlay').remove();
						window.location = base_url()+"Lion/booking_detail/"+$('#code_booking').val()
                    },
					 error: function (request, status, error) {
						$('#overlay').remove();
						toastpesan(request.responseText);
					}
                });
				
          });
		  
		function toastpesan(pesan){
			toastr.options = {
			  "closeButton": true,
			  "debug": false,
			  "newestOnTop": false,
			  "progressBar": false,
			  "positionClass": "toast-bottom-center",
			  "preventDuplicates": false,
			  "onclick": null,
			  "showDuration": "300",
			  "hideDuration": "1000",
			  "timeOut": "5000",
			  "extendedTimeOut": "1000",
			  "showEasing": "swing",
			  "hideEasing": "linear",
			  "showMethod": "fadeIn",
			  "hideMethod": "fadeOut"
			}
			toastr["info"](pesan);
		}
    });
	
</script>