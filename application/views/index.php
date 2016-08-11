<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>INDSITI | a ticketing system</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.6 -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<!-- bootstrap datepicker -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/plugins/datepicker/datepicker3.css">
	<!-- Select2 -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/plugins/select2/select2.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/dist/css/AdminLTE.min.css">
	<!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/dist/css/skins/_all-skins.min.css">
	<!-- jQuery 2.2.3 -->
	<script src="<?php echo base_url(); ?>/assets/plugins/jQuery/jquery-2.2.3.min.js"></script>
	<!-- Bootstrap 3.3.6 -->
	<script src="<?php echo base_url(); ?>/assets/bootstrap/js/bootstrap.min.js"></script>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
	<style>
		.panel-body {
			padding: 4px 0 0px 0px;
		}
		.row {
			padding: 10px 10px 10px 10px;
		}
	</style>
</head>

<body class="hold-transition skin-black layout-top-nav">
	<!-- Site wrapper -->
	<div class="wrapper">
		<header class="main-header">
			<nav class="navbar navbar-static-top">
		      <div class="container">
		        <div class="navbar-header">
		          <a href="../../index2.html" class="navbar-brand"><b>IND</b>SITI</a>
		          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
		            <i class="fa fa-bars"></i>
		          </button>
		        </div>

		        <!-- Collect the nav links, forms, and other content for toggling -->
		        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
		          <ul class="nav navbar-nav">
		            <li class="active"><a href="<?php echo base_url() ?>">Search Ticket <span class="sr-only">(current)</span></a></li>
		            <li class=""><a href="<?php echo base_url().'lion/booking_detail' ?>">Cek Booking <span class="sr-only">(current)</span></a></li>
		          </ul>
		        </div>
		        <!-- /.navbar-collapse -->
		        <!-- Navbar Right Menu -->
		        <div class="navbar-custom-menu">
		          <ul class="nav navbar-nav">
		            <!-- User Account Menu -->
		            <li id="login-header" class="show-modal dropdown user user-menu">
		              <!-- Menu Toggle Button -->
		              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
		                <!-- hidden-xs hides the username on small devices so only the image appears. -->
		                <span class="hidden-xs">
		                	<?php 
		                		if($this->ion_auth->logged_in()){
									echo "<span id='logout-btn-header'>Logout</span>";
								}else {
									echo "<span id='login-btn-header'>Login</span>";
								}
		                	?>
		                </span>
		              </a>
		            </li>
		          </ul>
		        </div>
		        <!-- /.navbar-custom-menu -->
		      </div>
		      <!-- /.container-fluid -->
		    </nav>
		</header>
		<!-- =============================================== ASIDE -->
		<!-- Left side column. contains the sidebar -->
		
		<!-- =============================================== -->
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>
			        <?php echo $title ?>
			        <small></small>
			    </h1>
			</section>
			<!-- Main content -->
			<section class="content">
				<?php $this->load->view($content); ?> </section>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->
		<footer class="main-footer">
	    </footer>
	</div>
	
	<?php if($this->ion_auth->logged_in()==0){ ?>
	<!-- Modal -->
	<div id="modal-content" class="modal fade modal-info" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	  <div class="modal-dialog modal-sm" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <p class="modal-title" id="myModalLabel">Sign in to start your session</p>
	      </div>
	          <!-- form start -->
          <form id="form-login" role="form">
		      <div class="modal-body">
	              <div class="box-body">
	              	<div id="login-warning"></div>
	                <div class="form-group">
	                  <label for="InputEmail1">Email address</label>
	                  <input name="identity" type="email" class="form-control" id="InputEmail1" placeholder="Enter email">
	                </div>
	                <div class="form-group">
	                  <label for="InputPassword1">Password</label>
	                  <input name="password" type="password" class="form-control" id="InputPassword1" placeholder="Password">
	                </div>
	                <div class="checkbox">
	                  <label>
	                    <input type="checkbox" name="remember" value="1"> Remember me
	                  </label>
	                </div>
	              </div>
	              <!-- /.box-body -->
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        <button id="login" type="button" class="btn btn-success">Sign in</button>
		      </div>
	      </form>
	    </div>
	  </div>
	</div>
	<script>
		$('.show-modal').on('click', function() {
		    callmodal();
		});	
		function callmodal(){
			if(login==0) $('#modal-content').modal('show');
		}
		$('#login').on('click', function() {
		    $.ajax({
                url:  base_url+"auth/login_ajax",
                type: "post",
                data: $("#form-login").serialize(),
                success: function(d,textStatus, xhr) {
                   if(xhr.status==200 && d.data==1){
				   	 login = 1;
				   	 $('#login-btn-header').text('Logout');
				   	 showalert(d.message,'success','#login-warning');
				   	 setTimeout(function() {
					     $('#modal-content').modal('hide');
					 }, 5000);
				   }
                },
                 error: function (request, status, error) {
                    showalert(error,'danger','#login-warning');
                }
            });
		});			
	</script>
	<?php } else {?>
		<script>
			login = 1;
		</script>
	<?php } ?>
	
	<!-- ./wrapper -->
	<!-- SlimScroll -->
	<script src="<?php echo base_url(); ?>/assets/plugins/slimScroll/jquery.slimscroll.min.js"></script>
	<!-- FastClick -->
	<script src="<?php echo base_url(); ?>/assets/plugins/fastclick/fastclick.js"></script>
	<!-- AdminLTE App -->
	<script src="<?php echo base_url(); ?>/assets/dist/js/app.min.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="<?php echo base_url(); ?>/assets/dist/js/demo.js"></script>
	<!-- InputMask -->
	<script src="<?php echo base_url(); ?>/assets/plugins/input-mask/jquery.inputmask.js"></script>
	<script src="<?php echo base_url(); ?>/assets/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
	<!-- bootstrap datepicker -->
	<script src="<?php echo base_url(); ?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script>
	<!-- Select2 -->
	<script src="<?php echo base_url(); ?>/assets/plugins/select2/select2.full.min.js"></script>
	<!-- BaseUrl -->
	<script type="text/javascript">
		$('#login-header').on('click', function() {
			if(login==1){
				$.get( base_url+'auth/logout', function(data) {
			         location.reload();
			    });
			    login =0;
			}
			
		});
		var base_url ="<?php echo base_url() ?>";
		<?php if($this->ion_auth->logged_in()==0){ 
			echo "var login = 0;";
		} else {
			echo "var login = 1;";
		}?>
	</script>
</body>

</html>