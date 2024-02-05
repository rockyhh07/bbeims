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
  <div class="container-fluid card p-3">
    <div class="d-flex align-items-center justify-content-between">
      <h4 id="table-title">Overall Population</h4>
      <button id="btn-add-house" class="btn btn-primary" onclick="openModal('add-house')">
        <i class="fas fa-plus"></i> Add Representative
      </button>
    </div>
    <hr>
    <table id="dataTable" class="table table-bordered table-striped"></table>
    <div class="d-flex flex-row-reverse justify-content-between" style="gap: 1rem;">
      <span class="d-flex align-items-end p-2" style="gap: 1rem; justify-self: flex-end;">
        <button id="btn-add-evacuee" class="btn btn-success hidden" onclick="openModal('add-evacuee')">
          <i class="fas fa-plus"></i> Add Member
        </button>
        <button id="btn-cancel-view" class="btn btn-primary hidden" onclick="loadAllRepresentative()">
          Back
        </button>
      </span>
      <form id="add-incident-form" method="post" class="d-flex align-items-center p-2 hidden justify-content-between" style="gap: 1rem; flex-grow: 1;">
        <div class="d-flex flex-column" style="gap: .5rem;">
          <span class="m-0" style="font-weight: bold;" id="basic-addon1">Date</span>
          <input name="incident_date" type="datetime-local" class="form-control" required>
        </div>
        <div class="d-flex flex-column" style="gap: .5rem;">
          <span class="m-0" style="font-weight: bold;" id="basic-addon1">Incident</span>
          <select name="incident" class="form-control" id="select-incident" required>
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
      </form>
    </div>
  </div>
  <!-- /.container-fluid -->
</section>