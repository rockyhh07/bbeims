<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BBEIMS</title>


  <link rel="shortcut icon" href="<?= Core::base_url() ?>/asset/img/logo.png" type="image/x-icon">


  <link rel="stylesheet" href="<?= Core::base_url() ?>/asset/css/adminlte.min.css">


  <?php if (!empty($css)) foreach ($css as $v) echo " <link rel='stylesheet' href='<?=Core::base_url()?>/asset/css/{$v}.css'>"; ?>
  <!-- <link rel="stylesheet" href="<?= Core::base_url() ?>/asset/css/dashboard.css">
  <link rel="stylesheet" href="<?= Core::base_url() ?>/asset/css/add-evacuees.css">
  <link rel="stylesheet" href="<?= Core::base_url() ?>/asset/css/font.css"> -->



  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= Core::base_url() ?>/asset/fontawesome/css/all.min.css">

  <?php if (!empty($js)) foreach ($js as $v) echo "<script src='<?=Core::base_url()?>/asset/js/{$v}.js'></script>"; ?>
  <!-- <script src="<?= Core::base_url() ?>/asset/js/constants.js"></script>
  <script src="<?= Core::base_url() ?>/asset/js/redirect_to_login.js"></script>
  <script src="<?= Core::base_url() ?>/asset/js/alert.js"></script> -->

  <?php echo "<script>const base_url = () => '" . Core::base_url() . "'</script>"; ?>

</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color: rgb(51, 99, 38);">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" onclick="logout()" href="#logout">
            <i class="fas fa-power-off"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: rgb(68, 126, 52);">
      <!-- Brand Logo -->
      <a href="index.html" class="brand-link animated swing">
        <img src="../asset/img/logo2.png" alt="DSMS Logo" width="200" style="margin-top: -20px;margin-bottom: -60px;">
      </a>
      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="index.html" class="nav-link">
                <i class="nav-icon fa fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="incident-type.html" class="nav-link">
                <i class="nav-icon fa fa-globe-asia"></i>
                <p>
                  Type of Incident
                </p>
              </a>
            </li>


            <li class="nav-item">
              <a href="evacuation-center.html" class="nav-link">
                <i class="nav-icon fa fa-hotel"></i>
                <p>
                  Evacuation Center
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-address-card"></i>
                <p>
                  Evacuee Information
                </p>
                <i class="right fas fa-angle-left"></i>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="add-evacuees.html" class="nav-link">
                    <i class="nav-icon fa fa-address-book"></i>
                    <p>Manage</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="manage-evacuees.html" class="nav-link">
                    <i class="nav-icon fas fa-list-ol"></i>
                    <p>List</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item" id="user-management">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-user-lock"></i>
                <p>
                  Users
                </p>
                <i class="right fas fa-angle-left"></i>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="add-user.html" class="nav-link">
                    <i class="nav-icon fa fa-plus"></i>
                    <p>New</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="manage-user.html" class="nav-link">
                    <i class="nav-icon fa fa-address-book"></i>
                    <p>Manage</p>
                  </a>
                </li>
              </ul>
            </li>

            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-chart-bar"></i>
                <p>
                  Reports
                </p>
                <i class="right fas fa-angle-left"></i>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="evacuees-report.html" class="nav-link">
                    <i class="nav-icon fa fa-users"></i>
                    <p>Evacuees</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="gender-report.html" class="nav-link">
                    <i class="nav-icon fa fa-venus-mars"></i>
                    <p>Evacuees by Gender</p>
                  </a>
                </li>

                <li class="nav-item">
                  <a href="age-report.html" class="nav-link">
                    <i class="nav-icon fa fa-sort-numeric-up-alt"></i>
                    <p>Evacuees by Age</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="incident-report.html" class="nav-link">
                    <i class="nav-icon fa fa-globe-asia"></i>
                    <p>Evacuees by Incident</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="center-report.html" class="nav-link">
                    <i class="nav-icon fa fa-hospital-alt"></i>
                    <p>Evacuees by Center</p>
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0" style="color: rgb(232, 193, 33);"><span class="fa fa-tachometer-alt"></span>
                Dashboard</h1>
            </div>
            <!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href=".">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
              </ol>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->