<div class="box" id="booking-form">
  <div id="alert"></div>
  <div class="box-header with-border">
    <h3 class="box-title">Booking Form</h3>
    <div class="box-tools pull-right">
      <button class="btn btn-box-tool" data-toggle="tooltip" data-widget="collapse" title="Collapse" type="button"><i class="fa fa-minus"></i>
      </button>
      <button class="btn btn-box-tool" data-toggle="tooltip" data-widget="remove" title="Remove" type="button"><i class="fa fa-times"></i>
      </button>
    </div>
  </div>
  <form id="form" method="post" action="">
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
      <button class="btn btn-flat btn-success btn-lg pull-right"><i class="fa fa-paper-plane"></i> | BOOKING</button>
    </div>
  </form>
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
            }
        });
        
  });
});
</script>