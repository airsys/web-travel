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
            	 <form class="contact-form" action="" id="form" method="post">
                    <h3>Contact Person</h3>
                    <div class="row form-group">
                        <div class="col-xs-2">
                            <label>Title</label>
                            <select required name='contact_title' id="contact_title" class="full-width">
								<option value='Mr'>Mr</option>
								<option value='Mrs'>Mrs</option>
								<option value='Ms'>Ms</option>
							</select>
                        </div>
                        <div class="col-xs-4">
                            <label>Name</label>
                            <input required type="text" name="contact_name" id="contact_name" class="input-text full-width">
                        </div>
                        <div class="col-xs-3">
                            <label>contact phone</label>
                            <input required type="text" name="contact_phone" id="contact_phone" class="input-text full-width">
                        </div>
                        <div class="col-xs-3">
                            <label>contact phone other</label>
                            <input type="text" name="contact_phone_other" id="contact_phone_other" class="input-text full-width">
                        </div>
                    </div>
                    <hr>
                    <h3>Passenger</h3>
                    
                    <?php //ADULT
                    for($i = 1; $i <= $data['adult'] ; $i++){ ?>
						<div class="row form-group">
	                        <div class="col-xs-2">
	                            <label>adult title <?php echo $i; ?></label>
	                            <select required name='adult_title_<?php echo $i; ?>' id="adult_title_<?php echo $i; ?>" class="full-width">
									<option value='Mr'>Mr</option>
									<option value='Mrs'>Mrs</option>
									<option value='Ms'>Ms</option>
								</select>
	                        </div>
	                        <div class="col-xs-4">
	                            <label>adult name <?php echo $i; ?></label>
	                            <input required type="text" name="adult_name_<?php echo $i; ?>" id="adult_name_<?php echo $i; ?>" class="input-text full-width">
	                        </div>
	                        <div class="col-xs-3">
	                            <label>adult special request <?php echo $i; ?></label>
	                            <input type="text" name="adult_special_request_<?php echo $i; ?>" id="adult_special_request_<?php echo $i; ?>" class="input-text full-width">
	                        </div>
	                    </div>	
					<?php } ?>
                    
                    <?php //CHILD
                    for($i = 1; $i <= $data['child'] ; $i++){ ?>
                    <div class="row form-group">
                        <div class="col-xs-2">
                            <label>child title <?php echo $i; ?></label>
                            <select required name='child_title_<?php echo $i; ?>' id="child_title_<?php echo $i; ?>" class="full-width">
								<option value='Mstr'>Mstr</option>
								<option value='Miss'>Miss</option>
							</select>
                        </div>
                        <div class="col-xs-4">
                            <label>child name <?php echo $i; ?></label>
                            <input required type="text" name="child_name_<?php echo $i; ?>" id="child_name_<?php echo $i; ?>" class="input-text full-width">
                        </div>
                        <div class="col-xs-3">
                            <label>child special request <?php echo $i; ?></label>
                            <input type="text" name="child_special_request_<?php echo $i; ?>" id="child_special_request_<?php echo $i; ?>" class="input-text full-width">
                        </div>
                    </div>
                    <?php } ?>
                    
                    <?php //INFANT
                    for($i = 1; $i <= $data['infant'] ; $i++){ ?>
                    <div class="row form-group">
                        <div class="col-xs-2">
                            <label>infant title <?php echo $i; ?></label>
                            <select required name='infant_title_<?php echo $i; ?>' id="infant_title_<?php echo $i; ?>" class="full-width">
								<option value='Mstr'>Mstr</option>
								<option value='Miss'>Miss</option>
							</select>
                        </div>
                        <div class="col-xs-4">
                            <label>infant name <?php echo $i; ?></label>
                            <input required type="text" name="infant_name_<?php echo $i; ?>" id="infant_name_<?php echo $i; ?>" class="input-text full-width">
                        </div>
                        <div class="col-xs-3">
                            <label>infant birth date <?php echo $i; ?></label>
                            <input required type="text" name="infant_birth_date_<?php echo $i; ?>" id="infant_birth_date_<?php echo $i; ?>" class="date-picker input-text full-width">
                        </div>
                        <div class="col-xs-3">
                            <label>child special request <?php echo $i; ?></label>
                            <input type="text" name="infant_special_request_<?php echo $i; ?>" id="infant_special_request_<?php echo $i; ?>" class="input-text full-width">
                        </div>
                    </div>
                    <?php } ?>
                    <hr>
                    
                    <input type="hidden" name="flight_key" value="<?php echo $data['key']; ?>" />
                    <button type="submit" class="btn-large full-width">SEND</button>
            </form>

            </div>
        </div>
    </div>
</div>

<script>
tjq(document).ready(function($) {
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
	
	$("#form").on("submit", function(event) {
		$(over).appendTo("#cari");
        event.preventDefault(); 
        $.ajax({
            url:  base_url()+"Lion/booking_save",
            type: "post",
            data: $(this).serialize(),
            success: function(d) {
				$('#overlay').remove();
				window.location = base_url()+"Lion/booking_detail/"+d;
            },
			 error: function (request, status, error) {
				$('#overlay').remove();
				toastpesan(request.responseText);
			}
        });
		
  });
});
</script>