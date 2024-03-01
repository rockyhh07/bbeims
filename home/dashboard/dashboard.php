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

      <div class="col-md-6">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-city"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Total Family Records</span>
            <span class="info-box-number" id="number-of-brgy-population">0</span>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-indigo elevation-1"><i class="fas fa-hotel"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Evacuation Centers</span>
            <span class="info-box-number" id="number-of-evac_center">0</span>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="info-box">
          <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Evacuees</span>
            <span class="info-box-number" id="number-of-evacuee">0</span>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Family</span>
            <span class="info-box-number" id="number-of-family">0</span>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-venus"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Female</span>
            <span class="info-box-number" id="number-of-female">0</span>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="info-box mb-3">
          <span class="info-box-icon bg-success elevation-1"><i class="fas fa-mars"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">Male</span>
            <span class="info-box-number" id="number-of-male">0</span>
          </div>
        </div>
      </div>

    </div>

    <div class="row">

      <!-- <div class="col-lg-12 report-generator">
        <div class="info-box">
          <span class="info-box-icon bg-info elevation-1">
            <i class="fas fa-chart-pie"></i>
          </span>
          <div class="info-box-content">
            <span class="info-box-text d-flex flex-column align-items-start">
              Analytics
              <button class="btn btn-primary" id="btn-gegerateReport" data-toggle="modal" data-target="#generate-report-modal">
                Generate Reports
              </button>
            </span>
          </div>
        </div>
      </div> -->

      <div class="col-lg-12">
        <div class="card">
          <div class="card-header p-0">
            <div class="row">
              <nav class="col-lg-12">
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                  <a class="nav-item nav-link p-3 disabled">
                    <h5><i class="fas fa-chart-pie"></i> Reports</h5>
                  </a>
                  <a class="nav-item nav-link p-3 active" id="nav-age-tab" data-toggle="tab" href="#report-age" role="tab" aria-controls="nav-age" aria-selected="true">
                    Age
                  </a>
                  <a class="nav-item nav-link p-3" id="nav-gender-tab" data-toggle="tab" href="#report-gender" role="tab" aria-controls="nav-gender" aria-selected="false">
                    Gender
                  </a>
                  <a class="nav-item nav-link p-3" id="nav-incident-tab" data-toggle="tab" href="#report-incident" role="tab" aria-controls="nav-incident" aria-selected="false">
                    Disaster
                  </a>
                  <a class="nav-item nav-link p-3" id="nav-center-tab" data-toggle="tab" href="#report-center" role="tab" aria-controls="nav-center" aria-selected="false">
                    Evacuation Center
                  </a>
                  <a class="nav-item nav-link p-3" id="nav-evacuee-tab" data-toggle="tab" href="#report-evacuee" role="tab" aria-controls="nav-evacuee" aria-selected="false">
                    Evacuees
                  </a>
                </div>
              </nav>
            </div>
          </div>
          <div class="card-body">


            <div class="tab-content" id="nav-tabContent">
              <!-- Age -->
              <div class="tab-pane fade show active" id="report-age" role="tabpanel" aria-labelledby="nav-age-tab">

                <div class="row">
                  <div class="col-md-6">

                    <table class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>Age Bracket</th>
                          <th>No. of Evacuee</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>0 to 5</td>
                          <td id="00-05">0</td>
                        </tr>
                        <tr>
                          <td>6 to 10</td>
                          <td id="06-10">0</td>
                        </tr>
                        <tr>
                          <td>11 to 20</td>
                          <td id="11-20">0</td>
                        </tr>
                        <tr>
                          <td>21 to 30</td>
                          <td id="21-30">0</td>
                        </tr>
                        <tr>
                          <td>31 to 40</td>
                          <td id="31-40">0</td>
                        </tr>
                        <tr>
                          <td>41 to 50</td>
                          <td id="41-50">0</td>
                        </tr>
                        <tr>
                          <td>51 to 60</td>
                          <td id="51-60">0</td>
                        </tr>
                        <tr>
                          <td>60 up</td>
                          <td id="60 up">0</td>
                        </tr>
                      </tbody>
                    </table>

                  </div>
                  <div class="col-md-6">
                    <canvas id="age-graph"></canvas>
                  </div>
                </div>

              </div>
              <!-- / Age  -->

              <!-- Gender -->
              <div class="tab-pane fade" id="report-gender" role="tabpanel" aria-labelledby="nav-gender-tab">

                <div class="row">
                  <div class="col-md-6">

                    <table class="table table-bordered table-hover">
                      <thead>
                        <tr>
                          <th>Gender Bracket</th>
                          <th>No. of Evacuee</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><i class="fas fa-mars"></i> Male</td>
                          <td id="gender-graph-male">0</td>
                        </tr>
                        <tr>
                          <td><i class="fas fa-venus"></i> Female</td>
                          <td id="gender-graph-female">0</td>
                        </tr>
                      </tbody>
                    </table>

                  </div>
                  <div class="col-md-6">
                    <canvas id="gender-graph"></canvas>
                  </div>
                </div>

              </div>
              <!-- / Gender -->

              <!-- Incident -->
              <div class="tab-pane fade" id="report-incident" role="tabpanel" aria-labelledby="nav-incident-tab">

                <div class="row">
                  <div class="col-md-4">
                    <table class="table table-bordered table-hover" id="incident-graph-table">
                      <thead>
                        <tr>
                          <th>Disaster</th>
                          <th>Occurrence</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                  <div class="col-md-8">
                    <canvas id="incident-graph"></canvas>
                  </div>
                </div>

              </div>
              <!-- / Incident -->

              <!-- Center -->
              <div class="tab-pane fade" id="report-center" role="tabpanel" aria-labelledby="nav-center-tab">

                <div class="row">
                  <div class="col-md-4">
                    <table class="table table-bordered table-hover" id="center-graph-table">
                      <thead>
                        <tr>
                          <th>Evacuation Center</th>
                          <th>Occurrence</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                  <div class="col-md-8">
                    <canvas id="center-graph"></canvas>
                  </div>
                </div>

              </div>
              <!-- /Center -->

              <!-- Evacuee -->
              <div class="tab-pane fade" id="report-evacuee" role="tabpanel" aria-labelledby="nav-evacuee-tab">

                <div class="row">
                  <div class="col-lg-12">
                    <table class="table table-bordered table-hover" id="evacuee-graph-table">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Name</th>
                          <th>Address</th>
                          <th>Age</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>

              </div>
              <!-- /Evacuee -->

            </div>

          </div>
          <div class="card-footer">
            <button class="btn btn-primary admin-dashboard-only" id="btn-generateReport" data-toggle="modal" data-target="#generate-report-modal">
              <i class="fas fa-print"></i> Generate Reports
            </button>
          </div>
        </div>
      </div>

    </div>

  </div>
</section>
<!-- /.container-fluid -->

<div class="container-fluid" id="report-container">
</div>

<button id="btn-setPassword" data-toggle="modal" data-target="#setPassword" style="display: none;"></button>