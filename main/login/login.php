<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <title>Barangka Ibaba Evacuation Information Management System</title>
   <link rel="shortcut icon" href="<?=Core::base_url();?>/assets/img/logo.png" type="image/x-icon">
   <!-- Google Font: Source Sans Pro -->
   <link rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

   <!-- Theme style -->
   <link rel="stylesheet" href="<?=Core::base_url();?>/assets/css/adminlte.min.css">

   <link rel="stylesheet" href="<?=Core::base_url();?>/assets/css/login.css">
   <link rel="stylesheet" href="<?=Core::base_url();?>/assets/css/font.css">
   <!-- Font Awesome -->
   <link rel="stylesheet" href="<?=Core::base_url();?>/assets/fontawesome/css/all.min.css">

   <!-- <script src="<?=Core::base_url();?>/assets/js/constants.js"></script>
   <script src="<?=Core::base_url();?>/assets/js/redirect_to_admin.js"></script>
   <script src="<?=Core::base_url();?>/assets/js/alert.js"></script> -->
   <script src="<?=Core::base_url();?>/assets/jquery/jquery.min.js"></script>
</head>

<body class="hold-transition login-page">
   <div class="login-box">
      <!-- /.login-logo -->
      <div class="card card-outline card-info">
         <div class="card-header text-center">
            <a href="." class="brand-link">
               <img src="<?=Core::base_url();?>/assets/img/logoa.png" alt="DSMS Logo" width="200">
            </a>
         </div>
         <div class="card-body">
            <form method="post" id="login-form">
               <div class="input-group mb-3">
                  <input name="username" type="text" class="form-control" placeholder="Username">
                  <div class="input-group-append">
                     <div class="input-group-text">
                        <span class="fas fa-user"></span>
                     </div>
                  </div>
               </div>
               <div class="input-group mb-3">
                  <input name="password" type="password" class="form-control" placeholder="Password">
                  <div class="input-group-append">
                     <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-6 offset-3">
                     <button id="login-button" type="submit" class="btn btn-block btn-bg"
                        style="background-color: rgb(116, 152, 87);">Login</button>
                  </div>
               </div>
            </form>
            <div style="font-size: small; text-align: right; margin-top: 1rem;" onclick="privacy()">
               Read <a href="#">Privacy policy</a>.
            </div>
         </div>
         <!-- /.card-body -->
      </div>
      <!-- /.card -->
   </div>
   <!-- /.login-box -->
</body>

</html>

<script type="module" src="./script.js"></script>