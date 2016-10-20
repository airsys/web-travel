<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/iCheck/all.css" />
<script src="<?php echo base_url(); ?>assets/plugins/iCheck/icheck.min.js"></script>

<div class="box" id="booking-form">
  <div id="alert"></div>
  <div class="box-header with-border">
    <h3 class="box-title">Booking Form</h3>
    <div class="box-tools pull-right">
    </div>
  </div>
  <div>
  <form id="form" method="post" role="form" action="">
  <?php if (!$this->ion_auth->logged_in()){ ?> 
  <div class="box-body">
  	<div id="login-warning"></div>
  </div>
  <div id="form-login" style="background-color:#f3f3f3" class="box-body">
  	<div class="col-md-4">
    <div class="form-group">
      <label for="InputEmail1">Email address</label>
      <input name="identity" required type="email" class="form-control" id="InputEmail1" placeholder="Enter email">
    </div>              
    </div>
    <div class="col-md-4">
    <div class="form-group">
      <label for="InputPassword1">Password</label>
      <input name="password" required type="password" class="form-control" id="InputPassword1" placeholder="Password">
    </div>
    </div>
    <div class="col-md-4">
    <div class="form-group">  
	    <label for="remember"></label><br>
	      <label>
	       <input type="checkbox"  class="flat-red"> Remember me
	      </label>
    </div>
  	</div>
  </div>
  <!-- /.box-body -->
  
  <div style="background-color:#f3f3f3" id="form-register" class="box-body">
  	<div class="row">
  	<div class="col-md-4">
	  	<div class="form-group">
	      <label for="full_name" class="col-sm-2 control-label">Full Name</label>
	      <div class="col-sm-10">
	        <input type="text" class="form-control" value="" name="full_name" id="first_name" placeholder="Full Name">
	      </div>
	    </div>
    </div>
    <div class="col-md-4">
	    <div class="form-group">
	      <label for="email" class="col-sm-2 control-label">Email</label>
	      <div class="col-sm-10">
	        <input type="text" class="form-control" value="" name="email" id="email" placeholder="email">
	      </div>
	    </div>
    </div>
    <div class="col-md-4">
	    <div class="form-group">
	      <label for="phone" class="col-sm-2 control-label">Phone</label>
	      <div class="col-sm-10">
	        <input type="text" class="form-control" value="" name="phone" id="phone" placeholder="phone">
	      </div>
	    </div>
    </div>
    </div>
    <div class="row">
    <div class="col-md-4">
	    <div class="form-group">
	      <label for="password" class="col-sm-2 control-label">Password</label>
	      <div class="col-sm-10">
	        <input type="password" class="form-control" name="password_register" id="company" placeholder="Password">
	      </div>
	    </div>
    </div>
    <div class="col-md-4">
	    <div class="form-group">
	      <label for="password_confirm" class="col-sm-2 control-label">Password Confirm</label>
	      <div class="col-sm-10">
	        <input type="password" class="form-control" name="password_confirm" id="password_confirm" placeholder="password confirm">
	      </div>
	    </div>
    </div>
    </div>
  </div>
  
  <div class="box-body">
  	<div class="col-md-4">
	  <div class="form-group">
	    <label>
	      <input type="radio" value="lo" name="position" id="position" class="flat-red" checked>
	      Login &nbsp;&nbsp;&nbsp;
	    </label>
	    <label>
	      <input type="radio" value="re" name="position" id="position" class="flat-red" >
	      Register
	    </label>
	  </div>
	</div>
  </div>
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
      <input type="hidden" name="date" value="<?php echo $data['date']; ?>" />
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
    
     //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass: 'iradio_minimal-blue'
    });
    //Red color scheme for iCheck
    $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
      checkboxClass: 'icheckbox_minimal-red',
      radioClass: 'iradio_minimal-red'
    });
    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
      checkboxClass: 'icheckbox_flat-green',
      radioClass: 'iradio_flat-green'
    });
    
    $('#form-login').show();
	$('#form-register').hide();
    
    $('input[type=radio][name=position]').on('ifToggled', function(event){
    	if(this.value=='lo'){
    		$('#form-login').show();
			$('#form-register').hide();
			$('#form-register').find('input[type=text],input[type=password]').prop('required',false);
			$('#form-login').find('input[type=text],input[type=password]').prop('required',true);
		}else{
			$('#form-login').hide();
			$('#form-register').show();
			$('#form-register').find('input[type=text],input[type=password]').prop('required',true);
			$('#form-login').find('input[type=text],input[type=password]').prop('required',false);
		}
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
					window.location = base_url+"airlines/retrieve/"+d;
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