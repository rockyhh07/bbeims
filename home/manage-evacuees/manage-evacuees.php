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
    <div class="card card-info p-3">

      <div class="col-md-12">
        <div class="d-flex flex-row-reverse">
          <button class="btn btn-primary" onclick="archive()">
            <i class="fas fa-archive"></i> Archive
          </button>
        </div>
        <table id="evacuee" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th style="min-width: 225px;">Evacuee's Information</th>
              <th>Address</th>
              <th>Representative</th>
              <th>Evacuation Center</th>
              <th>Incident</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>

      </div>
    </div>
  </div>

  <!-- /.container-fluid -->
</section>