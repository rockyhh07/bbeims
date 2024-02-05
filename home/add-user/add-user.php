<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 animated bounceInRight">
        <h1 class="m-0" style="color: rgb(232, 193, 33);"><span class="fa fa-user-lock"></span> Add Users
        </h1>
      </div>
      <!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href=".">Home</a></li>
          <li class="breadcrumb-item active">Add Users</li>
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

      <div class="card-header bg-primary">
        <h3 class="card-title">Users Information</h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form method="post" class="card-body" id="new-user-form">
        <div class="row">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-4">
                <div class="form-group">
                  <label>Full Name</label>
                  <input name="fullname" type="text" class="form-control" placeholder="Full Name">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Contact Info</label>
                  <input name="contact" type="text" class="form-control" placeholder="ex. 09876534764">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Birthday</label>
                  <input name="birthday" type="date" class="form-control">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Account Category</label>
                  <select name="category" class="form-control">
                    <option value="" selected>-- Select --</option>
                    <option value="A">Admin</option>
                    <option value="S">Staff</option>
                  </select>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Username</label>
                  <input name="username" type="text" class="form-control" placeholder="Username">
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <label>Password</label>
                  <div class="input-group">
                    <input name="password" type="password" class="form-control" placeholder="**********" id="input-password">
                    <span class="input-group-text">
                      <label for="input-show-pass" class="d-flex justify-content-center align-items-center mb-0" onclick="togglePass()" style="cursor: pointer;">
                        <i class="fas fa-eye" id="show-pass"></i>
                        <i class="fas fa-eye-slash" id="hide-pass" style="display: none;"></i>
                      </label>
                      <input type="checkbox" id="input-show-pass" style="display: none;">
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <!-- /.card-body -->

        <div class="card-footer">
          <button type="submit" class="btn btn-primary" id="new-user-save">Save</button>
        </div>
      </form>
    </div>
  </div>
  <!-- /.container-fluid -->
</section>