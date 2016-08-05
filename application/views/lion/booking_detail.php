 <div class="row">
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3><?php echo $data->booking_code; ?></h3>

          <p>Code Booking</p>
        </div>
        <div class="icon">
          <i class="ion ion-ios-box"></i>
        </div>
        <a href="#" class="small-box-footer">Code Booking <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-red">
        <div class="inner">
          <h3 style="font-size: 20px;"><?php echo date("d-m-Y", $data->time_limit); ?><br><?php echo date("H:i", $data->time_limit); ?></h3>
          <p>Payment Time Limit</p>
        </div>
        <div class="icon">
          <i class="ion ion-android-calendar"></i>
        </div>
        <a href="#" class="small-box-footer">Payment Time Limit <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3><?php echo $data->payment_status; ?></h3>

          <p>Status payment</p>
        </div>
        <div class="icon">
          <i class="ion ion-android-cart"></i>
        </div>
        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-green">
        <div class="inner">
          <h3 style="font-size: 29px;">Rp <?php echo number_format($data->NTA); ?></h3>

          <p style="font-size: 21px;">N T A</p>
        </div>
        <div class="icon">
          <i class="ion ion-bag"></i>
        </div>
        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
  </div>
  <!-- /.row -->