<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0" style="color: rgb(232, 193, 33);"><span class="fa fa-hotel"></span> Evacuation
          Center</h1>
      </div>
      <!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href=".">Home</a></li>
          <li class="breadcrumb-item active">Evacuation
          </li>
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
          <form class="col-md-3" id="evac-center-add-form">
            <div class="card-header">
              <span class="fa fa-hotel"> Evacuation Center Info</span>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label>Center Name</label>
                  <input name="name" type="text" class="form-control" placeholder="Center Name">
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label>Address</label>
                  <textarea name="address" class="form-control" width="100"></textarea>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label>Contact Info</label>
                  <input name="contact" type="text" class="form-control" placeholder="ex. 09827373213">
                </div>
              </div>
            </div>
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary" id="evac-center-add-btn">Save</button>
              <!-- <button type="submit" class="btn btn-danger">Cancel</button> -->
            </div>
          </form>
          <div class="col-md-9" style="border-left: 1px solid #ddd;">
            <table id="evac_center" class="table table-bordered table-hover">
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>