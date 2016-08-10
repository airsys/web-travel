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

<body class="skin-blue sidebar-collapse sidebar-mini fixed">
	<!-- Site wrapper -->
	<div class="wrapper">
		<header class="main-header">
			<!-- Logo -->
			<a href="<?php echo base_url(); ?>/assets/index2.html" class="logo">
				<!-- mini logo for sidebar mini 50x50 pixels --><span class="logo-mini"><b>IN</b>TI</span>
				<!-- logo for regular state and mobile devices --><span class="logo-lg"><b>IND</b>SITI</span> </a>
			<!-- Header Navbar: style can be found in header.less -->
			<nav class="navbar navbar-static-top">
				<!-- Sidebar toggle button-->
				<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
				<div class="navbar-custom-menu">
					
				</div>
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
			<div class="pull-right hidden-xs"> <b>Version Pra Alpha</b> 0.0.1 </div> <strong>Copyright &copy; 2016 <a href="http://indsiti.com">INDSITI Studio</a>.</strong> All rights reserved. </footer>
		
		<!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
		<div class="control-sidebar-bg"></div>
	</div>
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
	<script type="text/javascript">var base_url ="<?php echo base_url() ?>"</script>
</body>

</html>