import { Core } from "../../core/core.js";
import { CustomNotification } from "../../core/customNotification.js";
import { Helper } from "../../core/helper.js";
import { ModalHandler } from "../../core/modalHandler.js";

const modal_edit = "#edit-modal";
const modal_archive = "#archive-modal";

let evacuee_list_avail = [];
let center_list = (await Core.fetch_data(`${Core.base_url()}/php/evac_center_get_all.php`, "json")).result;

(async () => {
  await Load_EvacueeList();
})();

async function Load_EvacueeList() {
  evacuee_list_avail = [];

  const list = (await Core.fetch_data(`${Core.base_url()}/php/evacuee_get_all.php`, "json")).result;
  if (!list) { console.error("ERROR OCCURRED!"); return; }

  evacuee_list_avail = list.map(v => v.id);

  Helper.DataTable_Reset('#evacuee_list');

  const thead = `
    <thead>
      <tr>
        <th class="text-center">ID</th>
        <th style="min-width: 225px;">Evacuee's Information</th>
        <th>Disaster</th>
        <th>Date</th>
        <th class="text-center">Action</th>
      </tr>
    </thead>
  `;

  let tbody = '';
  list.forEach((v, i) => tbody += `
    <tr>
      <td class="text-center text-nowrap">${Helper.AsID(v.id, 6, '0', v.id == v.representative ? "H-" : "M-")}</td>
      <td>
        <button class="btn btn-sm btn-link" data-toggle="collapse" data-target="#collapse-${i}" aria-expanded="false" aria-controls="collapse-${i}">
          ${v.lname}, ${v.fname} ${v.mname}
        </button>

        <div id="collapse-${i}" class="collapse">
          <div class="row pl-2">
            <section class="col-sm-3 font-weight-bold" >Representative</section>
            <section class="col-sm-9">${v.rep_lname}, ${v.rep_fname} ${v.rep_mname}</section>
          </div>
          <div class="row pl-2">
            <section class="col-sm-3 font-weight-bold" >Age</section>
            <section class="col-sm-9">${Helper.getAge(v.birthday)}</section>
          </div>
          <div class="row pl-2">
            <section class="col-sm-3 font-weight-bold" >Gender</section>
            <section class="col-sm-9">${v.gender}</section>
          </div>
          <div class="row pl-2">
            <section class="col-sm-3 font-weight-bold" >Contact</section>
            <section class="col-sm-9">${v.contact}</section>
          </div>
          <div class="row pl-2">
            <section class="col-sm-3 font-weight-bold" >Civil status</section>
            <section class="col-sm-9">${Helper.getCivilStatus(v.civil_status)}</section>
          </div>
          <div class="row pl-2">
            <section class="col-sm-3 font-weight-bold" >Address</section>
            <section class="col-sm-9">${v.address}</section>
          </div>
        </div>
      </td>
      <td>${v.incident_name}</td>
      <td>${new Date(v.incident_date).toDateString()}</td>
      <td class="text-center" style="width: 160px !important;">
        <button 
          class="btn btn-sm btn-success btn-open-edit"
          data-binder-id="${v.id}"
          data-binder-representative="${v.representative}"
          data-binder-rep_lname="${v.rep_lname}"
          data-binder-rep_fname="${v.rep_fname}"
          data-binder-rep_mname="${v.rep_mname}"
          data-binder-incident_name="${v.incident_name}"
          data-binder-evac_id="${v.evac_id}"
          data-binder-incident_date="${v.incident_date}"
          data-toggle="modal"
          data-target="#edit-modal"
        > 
          <i class="fa fa-edit"></i> Edit
        </button>
      </td>
    </tr>
  `);

  Helper.DataTable_Init('#evacuee_list', thead + tbody, Load_Functions);
}

function Load_Functions() {
  Core.clearClick('.btn-open-edit', open_edit_listener, true);
  Core.onClick('.btn-open-edit', open_edit_listener, true);

  Core.clearClick(`${modal_edit}-btn-edit`, submit_edit_listener, true);
  Core.onClick(`${modal_edit}-btn-edit`, submit_edit_listener, true);
}

async function open_edit_listener() {
  const replace = {
    representative: Core.data(this, "binder-representative"),
    rep_name: `${Core.data(this, "binder-rep_lname")}, ${Core.data(this, "binder-rep_fname")} ${Core.data(this, "binder-rep_mname")}`,
    incident_name: Core.data(this, "binder-incident_name"),
    incident_date: Helper.toInputDate(Core.data(this, "binder-incident_date")),
    evac_id: Core.data(this, "binder-evac_id"),
  };
  let layout = (await Core.fetch_data('./modal-edit.html', "text"));
  layout = Core.replaceLayout(layout, replace);
  Core.f(`${modal_edit}-body`).innerHTML = layout;
  center_list.forEach(v => Core.f("#evac_id").innerHTML += `<option value="${v.id}">${v.name}</option>`);
  Core.f("#evac_id").value = replace.evac_id;
}

async function submit_edit_listener() {
  const form_data = Core.createFormData({ uid: Core.user_getData().id }, new FormData(Core.f("#edit-form")));
  if (!Core.isValidForm(form_data, ["incident_date", "evac_id"])) {
    Helper.Promt_Error("Please fill required fields.");
    return;
  }

  await Core.api('/evacuee_update_incident', null, form_data).then(async data => {
    CustomNotification.add("Success!", `Item updated!`, "primary");
    Core.f(`${modal_edit}-hide`).click();
    Helper.Promt_Clear();
    await Load_EvacueeList();
  });
}


let tableToggler = true;
Core.onClick("#btn-toggle-table", async () => {
  if (tableToggler) {
    Core.f("#btn-toggle-table").innerHTML = "Show Evacuee Lists";
    Core.f('#btn-archive').style.display = "none"
    Load_Archived();
  } else {
    Core.f("#btn-toggle-table").innerHTML = "Show Archived";
    Core.f('#btn-archive').style.display = "inline-block"
    Load_EvacueeList();
  }
  tableToggler = !tableToggler;
});

async function Load_Archived() {
  const data = (await Core.fetch_data(`${Core.base_url()}/php/evacuee_archived.php`, "json")).result;

  Helper.DataTable_Reset('#evacuee_list');

  const thead = `
    <thead>
      <tr>
        <th>ID</th>
        <th style="min-width: 225px;">Evacuee's Information</th>
        <th>Incident</th>
        <th>Date</th>
      </tr>
    </thead>
  `;

  let tbody = '';
  data.forEach((v, i) => tbody += `
    <tr>
      <td class="text-center text-nowrap">${Helper.AsID(v.id, 6, '0', v.id == v.representative ? "H-" : "M-")}</td>
      <td>
        <button class="btn btn-sm btn-link" data-toggle="collapse" data-target="#collapse-${i}" aria-expanded="false" aria-controls="collapse-${i}">
          ${v.lname}, ${v.fname} ${v.mname}
        </button>

        <div id="collapse-${i}" class="collapse p-2">
          <div class="row">
            <section class="col-sm-3 font-weight-bold" >Representative</section>
            <section class="col-sm-9">${v.rep_lname}, ${v.rep_fname} ${v.rep_mname}</section>
          </div>
          <div class="row">
            <section class="col-sm-3 font-weight-bold" >Age</section>
            <section class="col-sm-9">${Helper.getAge(v.birthday)}</section>
          </div>
          <div class="row">
            <section class="col-sm-3 font-weight-bold" >Gender</section>
            <section class="col-sm-9">${v.gender}</section>
          </div>
          <div class="row">
            <section class="col-sm-3 font-weight-bold" >Contact</section>
            <section class="col-sm-9">${v.contact}</section>
          </div>
          <div class="row">
            <section class="col-sm-3 font-weight-bold" >Civil status</section>
            <section class="col-sm-9">${Helper.getCivilStatus(v.civil_status)}</section>
          </div>
          <div class="row">
            <section class="col-sm-3 font-weight-bold" >Address</section>
            <section class="col-sm-9">${v.address}</section>
          </div>
        </div>
      </td>
      <td>${v.incident_name}</td>
      <td>${new Date(v.incident_date).toDateString()}</td>
    </tr>
  `);


  Helper.DataTable_Init('#evacuee_list', thead + tbody);
}

Core.onClick('#btn-archive', async function () {
  let layout = (await Core.fetch_data('./modal-archive.html', "text"));
  Core.f(`${modal_archive}-body`).innerHTML = layout;
});

Core.onClick(`${modal_archive}-btn-archive`, async function () {
  if (evacuee_list_avail.length > 0)
    (await Core.api('/archive', "json", Core.createFormData({ uid: Core.user_getData().id })));

  CustomNotification.add("Success!", `(${evacuee_list_avail.length}) Evacuees are archived!`, "primary");
  Core.f(`${modal_archive}-hide`).click();
  Helper.Promt_Clear();
  await Load_EvacueeList();
});