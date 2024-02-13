<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">

      <div class="col-sm-6 animated bounceInRight">
        <h1 class="m-0" style="color: rgb(232, 193, 33);">
          <span class="fa fa-user-lock"></span> Add Users
        </h1>
      </div>

      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href=".">Home</a></li>
          <li class="breadcrumb-item active">Add Users</li>
        </ol>
      </div>

    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">
    <div class="card">

      <div class="card-header bg-primary">
        <h5>Users Information</h5>
      </div>

      <form class="card-body" id="new-user-form">

        <div class="row">

          <div class="col-md-4">
            <div class="form-group">
              <label>Full Name <small class="text-danger">*</small></label>
              <input name="fullname" type="text" class="form-control" placeholder="Enter Name">
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label>Contact No. <small class="text-danger">*</small></label>
              <input name="contact" type="text" class="form-control" placeholder="Enter Contact No.">
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label>Birthday <small class="text-danger">*</small></label>
              <input name="birthday" type="date" class="form-control">
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label>Account Category <small class="text-danger">*</small></label>
              <select name="category" class="form-control">
                <option value="" selected>-- Select --</option>
                <option value="A">Admin</option>
                <option value="S">Staff</option>
              </select>
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label>Username <small class="text-danger">*</small></label>
              <input name="username" type="text" class="form-control" placeholder="Enter Username">
            </div>
          </div>

          <div class="col-md-4">
            <div class="form-group">
              <label>Password <small class="text-danger">*</small></label>
              <div class="input-group">
                <input name="password" type="password" class="form-control" placeholder="Enter Password" id="input-password">
                <span class="input-group-text">
                  <label for="input-show-pass" class="d-flex justify-content-center align-items-center mb-0" style="cursor: pointer;">
                    <i class="fas fa-eye" id="show-pass"></i>
                    <i class="fas fa-eye-slash" id="hide-pass" style="display: none;"></i>
                  </label>
                  <input type="checkbox" id="input-show-pass" style="display: none;">
                </span>
              </div>
            </div>
          </div>

        </div>

        <div class="row">
          <div class="col-sm-4"></div>
          <div class="col-sm-4">
            <div class="d-flex flex-column">
              <button type="button" class="btn btn-warning text-white" id="new-user-save">
                <i class="fas fa-save"></i> Save
              </button>
            </div>
          </div>
          <div class="col-sm-4"></div>
        </div>

        <div class="row">
          <div class="col-lg-12">
            <small class="error-msg text-danger"></small>
          </div>
        </div>

      </form>
      
    </div>
  </div>

</section>