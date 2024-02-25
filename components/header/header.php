<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Barangka Ibaba Evacuation Information Management System</title>
  <link rel="shortcut icon" href="<?= Core::base_url() ?>/assets/img/logo-a.png" type="image/x-icon">

  <!-- AdminLTE -->
  <link rel="stylesheet" href="<?= Core::base_url() ?>/assets/css/adminlte.min.css">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= Core::base_url() ?>/assets/fontawesome/css/all.min.css">

  <!-- jQuery -->
  <script src="<?= Core::base_url() ?>/assets/jquery/jquery.min.js" defer></script>

  <!-- bootrap -->
  <script src="<?= Core::base_url() ?>/assets/js/bootstrap.bundle.min.js" defer></script>

  <!-- DataTables  & Plugins -->
  <script src="<?= Core::base_url() ?>/assets/tables/datatables/jquery.dataTables.min.js" defer></script>
  <script src="<?= Core::base_url() ?>/assets/tables/datatables-bs4/js/dataTables.bootstrap4.min.js" defer></script>
  <script src="<?= Core::base_url() ?>/assets/tables/datatables-responsive/js/responsive.bootstrap4.min.js" defer></script>
  <script src="<?= Core::base_url() ?>/assets/tables/datatables-buttons/js/buttons.bootstrap4.min.js" defer></script>

  <!-- AdminLTE -->
  <script src="<?= Core::base_url() ?>/assets/js/adminlte.js" defer></script>

  <link rel="stylesheet" href="<?= Core::base_url() ?>/assets/css/dashboard.css">
  <script type="module" src="<?= Core::base_url() ?>/assets/components/js/header.js" defer></script>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="background-color: #0D356B;">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- <li class="nav-item">
          <a class="nav-link text-white" data-widget="fullscreen" href="#" role="button">
            <i class="fas fa-expand-arrows-alt"></i>
          </a>
        </li> -->
        <li class="nav-item">
          <a class="nav-link text-white" href="#" role="button" id="username"><i class="fas fa-user"></i> </a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-white" id="btn-logout" href="#logout">
            <i class="fas fa-power-off"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: #FEC437;">
      <!-- Brand Logo -->
      <a href="." class="brand-link animated swing">
        <img src="<?= Core::base_url() ?>/assets/img/logo-c.png" alt="DSMS Logo" width="200" style="margin-top: -20px;margin-bottom: -60px;">
      </a>
      <!-- Sidebar -->
      <div class="sidebar" style="background-color: #0D356B;">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

            <li class="nav-item">
              <a href="<?= Core::base_url() ?>/home/dashboard" class="nav-link">
                <i class="nav-icon fa fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>

            <li class="nav-item admin-dashboard-only">
              <a href="<?= Core::base_url() ?>/home/manage-barangay" class="nav-link">
                <i class="nav-icon fas fa-city"></i>
                <p>
                  Barangay
                </p>
              </a>
            </li>
            
            <li class="nav-item admin-dashboard-only">
              <a href="<?= Core::base_url() ?>/home/manage-disaster" class="nav-link">
                <i class="nav-icon fa fa-globe-asia"></i>
                <p>
                  Type of Disaster
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="<?= Core::base_url() ?>/home/manage-evacuation-center" class="nav-link">
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
                  <a href="<?= Core::base_url() ?>/home/add-evacuee" class="nav-link">
                    <i class="nav-icon fa fa-address-book"></i>
                    <p>Manage</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= Core::base_url() ?>/home/manage-evacuees" class="nav-link">
                    <i class="nav-icon fas fa-list-ol"></i>
                    <p>List</p>
                  </a>
                </li>
              </ul>
            </li>


            <li class="nav-item admin-dashboard-only" id="user-management">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-user-lock"></i>
                <p>
                  Users
                </p>
                <i class="right fas fa-angle-left"></i>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= Core::base_url() ?>/home/add-user" class="nav-link">
                    <i class="nav-icon fa fa-plus"></i>
                    <p>New</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= Core::base_url() ?>/home/manage-users" class="nav-link">
                    <i class="nav-icon fa fa-address-book"></i>
                    <p>Manage</p>
                  </a>
                </li>
              </ul>
            </li>


            <!-- <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-chart-bar"></i>
                <p>
                  Reports
                </p>
                <i class="right fas fa-angle-left"></i>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="<?= Core::base_url() ?>/home/report-evacuees" class="nav-link">
                    <i class="nav-icon fa fa-users"></i>
                    <p>Evacuees</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= Core::base_url() ?>/home/report-gender" class="nav-link">
                    <i class="nav-icon fa fa-venus-mars"></i>
                    <p>Evacuees by Gender</p>
                  </a>
                </li>

                <li class="nav-item">
                  <a href="<?= Core::base_url() ?>/home/report-age" class="nav-link">
                    <i class="nav-icon fa fa-sort-numeric-up-alt"></i>
                    <p>Evacuees by Age</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= Core::base_url() ?>/home/report-incident" class="nav-link">
                    <i class="nav-icon fa fa-globe-asia"></i>
                    <p>Evacuees by Incident</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="<?= Core::base_url() ?>/home/report-evacuation-center" class="nav-link">
                    <i class="nav-icon fa fa-hospital-alt"></i>
                    <p>Evacuees by Center</p>
                  </a>
                </li>
              </ul>
            </li> -->

          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">