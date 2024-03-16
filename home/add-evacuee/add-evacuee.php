<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 animated bounceInRight">
        <h1 id="title" class="m-0" style="color: rgb(232, 193, 33);"><span class="fa fa-address-card"></span>
          Residents
        </h1>
      </div>
      <!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href=".">Home</a></li>
          <li class="breadcrumb-item active">Add Evacuees</li>
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
          <div class="col-md-4 col-sm-6">
            <h4 id="table-title">Overall Population</h4>
          </div>
          <div class="col-md-4 col-sm-6 text-center">
            <span class="repMode_content" style="gap: 1rem; justify-content: center;">
              <button id="btn-add_rep" class="btn btn-primary mb-3" data-toggle="modal" data-target="#add-modal">
                <i class="fas fa-plus"></i> Add Representative
              </button>
            </span>
          </div>
          <div class="col-md-4 col-sm-12 text-center admin-dashboard-only">
            <button id="btn-toggle-table" class="btn btn-secondary mb-3" href="#">
              Show Archived
            </button>
          </div>
        </div>
      </div>
      <div class="card-body">

        <table id="representative_list" class="table table-bordered table-striped"></table>

      </div>
      <div class="card-footer">

        <div class="flex-row-reverse justify-content-between memMode_content" style="gap: 1rem;">
          <span class="d-flex align-items-end p-2" style="gap: 1rem; justify-self: flex-end;">

            <button id="btn-add-report" class="btn btn-warning" data-toggle="modal" data-target="#add_evacuees-modal">
              <i class="fas fa-plus"></i> Add to Evacuees
            </button>

            <button id="btn-add_mem" class="btn btn-success hidden" data-toggle="modal" data-target="#add-modal">
              <i class="fas fa-plus"></i> Add Member
            </button>

            <button id="btn-back" class="btn btn-primary hidden">
              Back
            </button>

          </span>
          <!-- <form id="add-disaster-form" method="post" class="d-flex align-items-center p-2 hidden justify-content-between" style="gap: 1rem; flex-grow: 1;">
            <div class="d-flex flex-column" style="gap: .5rem;">
              <span class="m-0" style="font-weight: bold;" id="basic-addon1">Date</span>
              <input name="disaster_date" type="datetime-local" class="form-control" required>
            </div>
            <div class="d-flex flex-column" style="gap: .5rem;">
              <span class="m-0" style="font-weight: bold;" id="basic-addon1">disaster</span>
              <select name="disaster" class="form-control" id="select-disaster" required>
                <option value="" selected>-- Select --</option>
              </select>
            </div>
            <div class="d-flex flex-column" style="gap: .5rem;">
              <span class="m-0" style="font-weight: bold;" id="basic-addon1">Evacuation Center</span>
              <select name="evac_center" class="form-control" id="select-evac_center" required>
                <option value="" selected>-- Select --</option>
              </select>
            </div>
            <button id="btn-add-report" class="btn btn-warning align-self-end" style="white-space: nowrap;">
              <i class="fas fa-plus"></i> Add Evacuees
            </button>
          </form> -->
        </div>

      </div>
    </div>

  </div>
</section>