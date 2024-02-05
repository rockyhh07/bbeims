<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0" style="color: rgb(232, 193, 33);"><span class="fa fa-globe-asia"></span> Incident
          Type</h1>
      </div>
      <!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href=".">Home</a></li>
          <li class="breadcrumb-item active">Incident Type</li>
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
      <!-- form start -->
      <div class="card-body">
        <div class="row">
          <form class="col-md-3" id="incident-add-form">
            <div class="card-header">
              <span class="fa fa-globe-asia"> Incident Information</span>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <!-- <label>Incident Name</label> -->
                  <input type="text" class="form-control mt-3" name="name" placeholder="Incident name">
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary" id="incident-add-btn">Save</button>
              <!-- <button type="submit" class="btn btn-danger">Cancel</button> -->
            </div>
          </form>
          <div class="col-md-9" style="border-left: 1px solid #ddd;">
            <table id="incident" class="table table-bordered table-hover">
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>