<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Student Tracking System</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- <link href="<?php echo base_url('css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <link href="<?php echo base_url('css/font-awesome.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('css/AdminLTE.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('css/ionicons.min.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('css/_all-skins.min.css'); ?>" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" integrity="sha256-MeSf8Rmg3b5qLFlijnpxk6l+IJkiR91//YGPCrCmogU=" crossorigin="anonymous" />
    <link href="<?php echo base_url('css/styles.css'); ?>" rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <!-- Google Font -->
    <link href="<?php echo base_url('css/css'); ?>" rel="stylesheet">
</head>
    <?php
    $session_id        = $this->session->userdata('access_token');
    if($this->session->userdata('access_token'))
    {
    ?>
    <body class="skin-blue sidebar-mini" style="height: auto; min-height: 100%;">
    <div class="wrapper" style="height: auto; min-height: 100%;">
      <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo base_url();?>admin/dashboard" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>STS</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>Student Tracking System</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="<?php echo base_url();?>admin/dashboard" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="<?php echo base_url();?>admin/dashboard#" class="dropdown-toggle" data-toggle="dropdown">

                  <img src="<?php echo base_url('images/avatar3.png'); ?>" class="user-image" alt="User Image">
                  <span class="hidden-xs">Admin</span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="<?php echo base_url('images/avatar3.png'); ?>" class="img-circle" alt="User Image">
                       <p>Logged in as admin</p>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <!--<a href="http://localhost/ems/profile" class="btn btn-warning btn-flat"><i class="fa fa-user-circle"></i> Profile</a>-->
                    </div>
                    <div class="pull-right">
                      <a href="<?php echo base_url("admin/logout")?>" class="btn btn-primary btn-flat"><i class="fa fa-sign-out"></i>Sign out</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>        
      </header>
    <?php    
    }
    ?>
  