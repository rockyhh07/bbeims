<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0" style="color: rgb(232, 193, 33);"><span class="fa fa-address-card"></span>
          Evacuees</h1>
      </div>
      <!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href=".">Home</a></li>
          <li class="breadcrumb-item active">Evacuees</li>
        </ol>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <div class="row">
          <div class="col-md-6 col-sm-6">
            <h5 class="mt-2 mb-3"><i class="fas fa-stream"></i>
              Evacuee List
            </h5>
          </div>
          <div class="col-md-6 col-sm-6 text-center admin-dashboard-only">
            <button id="btn-toggle-table" class="btn btn-secondary mb-3" href="#">
              Show Archived
            </button>
          </div>
        </div>
        <div class="d-flex align-items-center justify-content-between mb-3">
        </div>
      </div>
      <div class="card-body">
        <table id="evacuee_list" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th style="min-width: 225px;">Evacuee's Information</th>
              <th>Disaster</th>
              <th>Date</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="card-footer">
        <button class="btn btn-primary" id="btn-archive" data-toggle="modal" data-target="#archive-modal">
          <i class="fas fa-archive"></i> Archive Evacuees
        </button>
      </div>
    </div>
  </div>

  <!-- /.container-fluid -->
</section>