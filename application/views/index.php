<?php //date_default_timezone_set('Asia/Jakarta'); ?>
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
	<link rel="stylesheet" href="<?php echo base_url(); ?>/assets/plugins/jQueryUI/jquery-ui.css">
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
		<header class="main-header" style="z-index: 1;">
			<nav class="navbar navbar-static-top">
		      <div class="container">
		        <div class="navbar-header">
		          <a href="#" class="navbar-brand"><b>IND</b>SITI<sup>beta</sup></a>
		          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
		            <i class="fa fa-bars"></i>
		          </button>
		        </div>

		        <!-- Collect the nav links, forms, and other content for toggling -->
		        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
		          <ul class="nav navbar-nav">
		            <li class="menu-bar"><a href="<?php echo base_url() ?>">Airlines <span class="sr-only">(current)</span></a></li>
		             <li class="dropdown">
		              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Market <span class="caret"></span></a>
		              <ul class="dropdown-menu" role="menu">
		                <li><a href="<?php echo base_url().'ppob/tagihan' ?>">Cek Tagihan</a></li>
		                <li><a href="<?php echo base_url().'ppob/pulsa' ?>">Pulsa HP & PLN</a></li>
		              </ul>
		            </li>
		            <?php if($this->ion_auth->logged_in()){ ?>
		            <!--<li class="menu-bar"><a href="<?php //echo base_url()./*'airlines/retrieve?q=status:booking'*/ ?>">Cek Booking <span class="sr-only">(current)</span></a></li>-->
		           
		            <li class="dropdown">
		              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Topup <span class="caret"></span></a>
		              <ul class="dropdown-menu" role="menu">
		                <li><a href="<?php echo base_url().'payment/topup' ?>">Input Topup</a></li>
		                <li><a href="<?php echo base_url().'payment/topup_list' ?>">List Topup</a></li>
		              </ul>
		            </li>
		            <li class="dropdown">
		              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Report <span class="caret"></span></a>
		              <ul class="dropdown-menu" role="menu">
		                <li><a href="<?php echo base_url().'report/sales' ?>">Sales</a></li>
		                <li><a href="<?php echo base_url().'report/finance' ?>">Finance</a></li>
		                <li><a href="<?php echo base_url().'airlines/retrieve?q=status:booking' ?>">Check Booking</a></li>
		              </ul>
		            </li>
		         	<?php } ?>
		          </ul>
		        </div>
		        <!-- /.navbar-collapse -->
		        <!-- Navbar Right Menu -->
		        <div class="navbar-custom-menu">
		          <ul class="nav navbar-nav">
		            <!-- User Account Menu -->
		            <li class="dropdown user user-menu">
		              <!-- Menu Toggle Button -->
		              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
		                <!-- The user image in the navbar-->
		                <?php 
		                	if(!$this->ion_auth->logged_in()){
		                		echo "<div id='user-header'><i class='fa fa-lock fa-lg'></i>&nbsp;<span>Login</span></div>";
		                	} else{
								echo "<div id='user-header'><i class='fa fa-user fa-lg'></i>&nbsp;<span>".$this->session->userdata('identity')."</span></div>";
							}
						?>
		                <!-- hidden-xs hides the username on small devices so only the image appears. -->
		                <span class="hidden-xs">
		                	
		                </span>
		              </a>
		              <ul class="dropdown-menu">
		              <?php 
		                if($this->ion_auth->logged_in()){ ?>
			              <!-- Menu Body -->
			              <li class="user-body">
			                <div class="row">
			                  <div class="col-xs-8 text-center">
			                    <a href="#">Saldo: Rp <?php echo number_format(saldo()); ?></a>
			                  </div>
			                  <div class="col-xs-4 text-center">
			                    <a href="#"></a>
			                  </div>
			                </div>
			                <!-- /.row -->
			              </li>
		               <?php } ?>
		                <!-- Menu Footer-->
		                <li class="user-footer">
		                  <div class="pull-left">
		                  	<?php 
		                		if(!$this->ion_auth->logged_in()){
									echo "<a href='#' class='pull-left btn btn-danger btn-flat' id='register-header'>Register</a>";
								}else{
									echo "<a href='#' class='pull-left btn btn-primary btn-flat' id='register-header'>Profile</a>";
								}
		                	?>
		                  </div>
		                  <div class="pull-right">
		                    	<?php 
		                		if($this->ion_auth->logged_in()){
									echo "<a href='#' class='btn btn-warning btn-flat' id='login-header'>Logout</a>";
								}else {
									echo "<a href='#' class='show-modal btn btn-success btn-flat' id='login-header'>Login</a>";
								}
		                	?>
		                  </div>
		                </li>
		              </ul>
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
          <form id="form-login-header" action="" method="post" role="form">
		      <div class="modal-body">
	              <div class="box-body">
	              	<div id="login-warning"></div>
	                <div class="form-group">
	                  <label for="InputEmail1">Email address</label>
	                  <input name="identity" type="email" class="form-control" id="InputEmail1" placeholder="Enter email">
	                </div>
	                <div class="form-group">
	                  <label for="InputPassword1">Password</label>
	                  <div class="input-group">
			            <input id="InputPassword1" name="password" type="password" class="form-control" placeholder="Password">
			            <span id="show-password" class="input-group-addon"><i id="eye" class="fa fa-eye-slash"></i></span>
				      </div>
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
		      	<a class="pull-left" style="color:#ffffff;  text-decoration: underline; margin-top: 5px;" href="<?php echo base_url() ?>auth2/forgot_password" > Forgot Password</a>
		      	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		        <button id="login" type="submit" class="btn btn-success">Sign in</button>
		      </div>
	      </form>
	    </div>
	  </div>
	</div>
	<script>
		$(document).ready(function(){
			<?php 				
			if($this->session->flashdata('message')){
				echo 'callmodal();';
				echo "showalert('".$this->session->flashdata('message')."','warning','#login-warning',10000);";
			} 
			?>
			$('.show-modal').on('click', function() {
			    callmodal();
			});
			var sh_pass = 0;
			$('#show-password').on('click', function() {
			    sh_pass++;
			    if(sh_pass%2==0){
					$('#InputPassword1').get(0).setAttribute('type', 'password');
					 $("#eye").removeClass("fa-eye");
					 $("#eye").addClass("fa-eye-slash");
				}else{
					$('#InputPassword1').get(0).setAttribute('type', 'text');
					$("#eye").removeClass("fa-eye-slash");
					$("#eye").addClass("fa-eye");
				}
			});
			function callmodal(){
				if(login==0) $('#modal-content').modal('show');
			}
			 $('#form-login-header').submit(function( event ) {
				event.preventDefault();
			    $.ajax({
	                url:  base_url+"auth2/login_ajax",
	                type: "post",
	                data: $("#form-login-header").serialize(),
	                success: function(d,textStatus, xhr) {
	                   if(xhr.status==200 && d.data==1){
					   	 login = 1;
					   	$('#login-header').text('Logout');
					   	$('#register-header').text('Profile');
					   	$("#user-header").children("span").text(d.user);
					   	$("#user-header").children("i").removeClass('fa-lock');
		    			$("#user-header").children("i").addClass('fa-user');
					   	 showalert(d.message,'success','#login-warning');
					   	 
					   	 	var url = window.location.href;
               				window.location.reload(true) = url;
					   	 
					   	 setTimeout(function() {
						     $('#modal-content').modal('hide');
						 }, 2000);
					   }
	                },
	                 error: function (request, status, error) {
	                 	  var err = eval("(" + request.responseText + ")");
	                      showalert(err.message,'danger','#login-warning');
	                }
	            });
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
	<!-- JqueryUI -->
	<script src="<?php echo base_url(); ?>assets/plugins/jQueryUI/jquery-ui.min.js"></script>
	<!-- Select2 -->
	<script src="<?php echo base_url(); ?>/assets/plugins/select2/select2.full.min.js"></script>
	<!-- Validation -->
	<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
	<!-- BaseUrl -->
	<script type="text/javascript">
		$('.menu-bar').find('a').each(function() {
			var url = window.location.href;
			var mlink = $(this).attr('href');
			if(mlink==url || mlink+'/'==url) $(this).parent("li").addClass('active');
		});
		
		$('#login-header').on('click', function() {
			setTimeout(function() { $('input[name="identity"]').focus() }, 1100);
			$('input[name="identity"]').val('');
			if(login==1){
				$.get( base_url+'auth2/logout', function(data) {
			         window.location = base_url+"airlines";
			    });
			    login =0;
			}			
		});
		$('#register-header').on('click', function() {
			if(login==1){
				window.location = base_url+"auth2/profile/";
			}else{
				window.location = base_url+"auth2/register/";
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