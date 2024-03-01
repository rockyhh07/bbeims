<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0" style="color: rgb(232, 193, 33);"><span class="fas fa-city"></span> Barangay</h1>
      </div>
      <!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href=".">Home</a></li>
          <li class="breadcrumb-item active">Barangay</li>
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
          <div class="col-md-4 col-sm-6 align-middle">
            <h5 class="mt-2">Barangays Information</h5>
          </div>
          <div class="col-md-4 col-sm-6 text-center">
            <button class="btn btn-primary mb-3" id="btn-open-add" data-toggle="modal" data-target="#add-barangay-modal">
              <i class="fas fa-plus"></i> Add Barangay
            </button>
          </div>
          <div class="col-md-4 col-sm-12 text-center admin-dashboard-only">
            <button id="btn-toggle-table" class="btn btn-secondary mb-3" href="#">
              Show Archived
            </button>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <table id="barangay" class="table table-bordered table-hover">
              <tr>
                <th>No Data Found</th>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>