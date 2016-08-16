<div class="box" id="booking-form">
  <div id="alert"></div>
  <div class="box-header with-border">
    <h3 class="box-title">Booking Form</h3>
    <div class="box-tools pull-right">
    </div>
  </div>
   <style>
    .example-modal .modal {
      position: relative;
      top: auto;
      bottom: auto;
      right: auto;
      left: auto;
      display: block;
      z-index: 1;
    }

    .example-modal .modal {
      background: transparent !important;
    }
  </style>
  <div>
  <form id="form" method="post" role="form" action="">
  <?php if (!$this->ion_auth->logged_in()){ ?>
  <div class="example-modal">
    <div class="modal modal-primary">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <p class="modal-title">Sign in to start your session</p>
          </div>
		      <div class="modal-body">
	              <div class="box-body">
	              	<div id="login-warning"></div>
	              	<div class="col-md-6">
	                <div class="form-group">
	                  <label for="InputEmail1">Email address</label>
	                  <input name="identity" required type="email" class="form-control" id="InputEmail1" placeholder="Enter email">
	                </div>              
	                </div>
	                <div class="col-md-6">
	                <div class="form-group">
	                  <label for="InputPassword1">Password</label>
	                  <input name="password" required type="password" class="form-control" id="InputPassword1" placeholder="Password">
	                </div>
	                </div>
	                <div class="col-md-6">
	                <div class="checkbox">
	                  <label>
	                    <input type="checkbox" name="remember" value="1"> Remember me
	                  </label>
	                </div>
	              	</div>
	              </div>
	              <!-- /.box-body -->
		      </div>
		      <div class="modal-footer">
		      	
		      </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
  </div>
  <!-- /.example-modal -->
  <?php } ?>
  
    <div class="box-body">
      <h3>Passenger</h3>
      <!-- >Adult</!-->
      <?php for($i = 1; $i <= $data['adult'] ; $i++){ ?>
      <div class="row">
        <div class="col-md-2 col-sm-3 col-xs-3">
          <div class="form-group">
            <label>Adult Title <?php echo $i; ?></label>
            <select required id='adult_title_<?php echo $i; ?>' name='adult_title_<?php echo $i; ?>' class="form-control">
              <option value="Mr">Mr</option>
              <option value="Mrs">Mrs</option>
              <option value="Ms">Ms</option>
            </select>
          </div>
        </div>
        <div class="col-md-5 col-sm-9 col-xs-9">
          <div class="form-group">
            <label>Adult Name <?php echo $i; ?></label>
            <input required type='text' id='adult_name_<?php echo $i; ?>' name='adult_name_<?php echo $i; ?>' class="form-control" />
          </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
          <div class="form-group">
            <label>Adult Special Request <?php echo $i; ?></label>
            <input type='text' id='adult_special_request_<?php echo $i; ?>' name='adult_special_request_<?php echo $i; ?>' class="form-control" />
          </div>
        </div>
      </div>
      <?php } ?>
      <!-- >Chlid</!-->
      <?php for($i = 1; $i <= $data['child'] ; $i++){ ?>
      <div class="row">
        <div class="col-md-2 col-sm-3 col-xs-3">
          <div class="form-group">
            <label>Child Title <?php echo $i; ?></label>
            <select required id='chlid_title_<?php echo $i; ?>' name='chlid_title_<?php echo $i; ?>' class="form-control">
              <option value="Mstr">Mstr</option>
              <option value="Miss">Miss</option>
            </select>
          </div>
        </div>
        <div class="col-md-5 col-sm-9 col-xs-9">
          <div class="form-group">
            <label>Child Name <?php echo $i; ?></label>
            <input required type='text' id='chlid_name_<?php echo $i; ?>' name='chlid_name_<?php echo $i; ?>' class="form-control" />
          </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
          <div class="form-group">
            <label>Child Special Request <?php echo $i; ?></label>
            <input type='text' id='chlid_special_request_<?php echo $i; ?>' name='chlid_special_request_<?php echo $i; ?>' class="form-control" />
          </div>
        </div>
      </div>
      <?php } ?>
      <!-- >Infant</!-->
      <?php for($i = 1; $i <= $data['infant'] ; $i++){ ?>
      <div class="row">
        <div class="col-md-2 col-sm-3 col-xs-3">
          <div class="form-group">
            <label>Infant Title <?php echo $i; ?></label>
            <select required id='infant_title_<?php echo $i; ?>' name='infant_title_<?php echo $i; ?>' class="form-control">
              <option value="Mstr">Mstr</option>
              <option value="Miss">Miss</option>
            </select>
          </div>
        </div>
        <div class="col-md-5 col-sm-9 col-xs-9">
          <div class="form-group">
            <label>Infant Name <?php echo $i; ?></label>
            <input required type='text' id='infant_name_<?php echo $i; ?>' name='infant_name_<?php echo $i; ?>' class="form-control" />
          </div>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-6">
          <div class="form-group">
            <label>Infant Birth Date <?php echo $i; ?></label>
            <div class="input-group">
              <div class="input-group date">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input required id='infant_birth_date_<?php echo $i; ?>' name='infant_birth_date_<?php echo $i; ?>' type="text" class="datepicker form-control pull-right">
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
          <div class="form-group">
            <label>Infant Special Request <?php echo $i; ?></label>
            <input type='text' id='infant_special_request_<?php echo $i; ?>' name='infant_special_request_<?php echo $i; ?>' class="form-control" />
          </div>
        </div>
      </div>
      <?php } ?>
      <hr />
      <h3>Contact Person</h3>
      <div class="row">
        <div class="col-md-2 col-sm-3 col-xs-3">
          <div class="form-group">
            <label>Title</label>
            <select required id='contact_title' name='contact_title' class="form-control">
              <option value="Mr">Mr</option>
              <option value="Mrs">Mrs</option>
              <option value="Ms">Ms</option>
            </select>
          </div>
        </div>
        <div class="col-md-5 col-sm-9 col-xs-9">
          <div class="form-group">
            <label>Name</label>
            <input required type='text' id='contact_name' name='contact_name' class="form-control" />
          </div>
        </div>
        <div class="col-md-3 col-sm-6 col-xs-6">
          <div class="form-group">
            <label>CONTACT PHONE</label>
            <input required type='text' id='contact_phone' name='contact_phone' class="form-control" />
          </div>
        </div>
        <div class="col-md-2 col-sm-6 col-xs-6">
          <div class="form-group">
            <label>CONTACT PHONE OTHER</label>
            <input type='text' id='contact_phone_other' name='contact_phone_other' class="form-control" />
          </div>
        </div>
      </div>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      <input type="hidden" name="flight_key" value="<?php echo $data['key']; ?>" />
      <button id="btn-booking" class="btn btn-flat btn-success btn-lg pull-right"><i class="fa fa-paper-plane"></i> | BOOKING</button>
    </div>
  </form>
  </div>
  <!-- /.box-footer-->
</div>
<!-- /.box -->

<script>
$(document).ready(function(){
    $('.datepicker').datepicker({
      autoclose: true,
	  format: 'dd-mm-yyyy', 
	   todayHighlight: true,
    });
    
    $("#form").on("submit", function(event) {
    	$("#btn-booking").removeClass('btn-success');
        $("#btn-booking").addClass('btn-warning');
        $("#btn-booking").children("i").removeClass('fa-paper-plane');
        $("#btn-booking").children("i").addClass('fa-refresh fa-spin');
        
        $(over).appendTo("#booking-form");
        event.preventDefault(); 
        $.ajax({
            url:  base_url+"airlines/booking_save",
            type: "post",
            data: $(this).serialize(),
            success: function(d, textStatus, xhr) {
            	if(d !== undefined && d !== null && d !== "" && xhr.status==200){
					window.location = base_url+"airlines/booking_detail/"+d;
				} else{
					showalert(d,'warning')
				}         	
                $('#overlay').remove();
            },
             error: function (request, status, error) {
                $('#overlay').remove();
                showalert(request.responseText,'warning');
                $("#btn-booking").addClass('btn-success');
		        $("#btn-booking").removeClass('btn-warning');
		        $("#btn-booking").children("i").addClass('fa-paper-plane');
		        $("#btn-booking").children("i").removeClass('fa-refresh fa-spin');
            }
        });
        
  });
});
</script>