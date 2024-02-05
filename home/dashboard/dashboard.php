<?= Core::importComponent(['path' => '/components/header']) ?>

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0" style="color: rgb(232, 193, 33);"><span class="fa fa-tachometer-alt"></span>
          Dashboard</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href=".">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">

    <div class="row">
      <!-- fix for small devices only -->
      <div class="clearfix hidden-md-up"></div>

      <div class="col-12 col-sm-6 col-md-6">
        <div class="info-box m-0">
          <span class="info-box-icon bg-info elevation-1"><i class="fas fa-university"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Barangay Population</span>
            <span class="info-box-number" id="number-of-brgy-population">0</span>
          </div>
        </div>
      </div>

      <!-- fix for small devices only -->
      <div class="clearfix hidden-md-up report-generator"></div>

      <div class="col-12 col-sm-6 col-md-6 report-generator">
        <div class="info-box m-0">
          <span class="info-box-icon bg-info elevation-1">
            <i class="fas fa-chart-pie"></i>
          </span>
          <div class="info-box-content">
            <span class="info-box-text d-flex flex-column align-items-start">
              Analytics
              <button class="btn btn-primary" id="btn-gegerateReport">Generate Reports</button>
            </span>
          </div>
        </div>
      </div>
    </div>

    <hr>

    <!-- Small boxes (Stat box) -->
    <div class="row">
      <div class="col-12 col-sm-6 col-md-6">
        <div class="info-box">
          <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Evacuees</span>
            <span class="info-box-number" id="number-of-evacuee">0</span>
          </div>
        </div>
      </div>
      <div class="col-12 col-sm-6 col-md-6">
        <div class="info-box">
          <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Family</span>
            <span class="info-box-number" id="number-of-family">0</span>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-md-6">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-venus"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Female</span>
            <span class="info-box-number" id="number-of-female">0</span>
          </div>
        </div>
      </div>

      <!-- fix for small devices only -->
      <div class="clearfix hidden-md-up"></div>

      <div class="col-12 col-sm-6 col-md-6">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-success elevation-1"><i class="fas fa-mars"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Male</span>
            <span class="info-box-number" id="number-of-male">0</span>
          </div>
        </div>
      </div>

      <!-- fix for small devices only -->
      <div class="clearfix hidden-md-up"></div>

      <div class="col-12 col-sm-6 col-md-6">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-indigo elevation-1"><i class="fas fa-hotel"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">Evacuation Centers</span>
            <span class="info-box-number" id="number-of-evac_center">0</span>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
<!-- /.container-fluid -->

<?= Core::importComponent(['path' => '/components/notification']) ?>
<?= Core::importComponent(['path' => '/components/footer']) ?>

<script type="module" src="./dashboard.js"></script>