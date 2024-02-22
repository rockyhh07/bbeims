import { Core } from "../../core/core.js";
import { CustomNotification } from "../../core/customNotification.js";
import { Helper } from "../../core/helper.js";
import { ModalHandler } from "../../core/modalHandler.js";

let center_list = (await Core.fetch_data(`${Core.base_url()}/php/evac_center_get_all.php`, "json")).result;
console.log({ center_list });

(async () => {
  await Load_EvacueeList();
})();

async function Load_EvacueeList() {
  const list = (await Core.fetch_data(`${Core.base_url()}/php/evacuee_get_all.php`, "json")).result;
  if (!list) { console.error("ERROR OCCURRED!"); return; }

  console.log({ list })

  Helper.DataTable_Reset('#evacuee_list');

  const thead = `
    <thead>
      <tr>
        <th class="text-center">ID</th>
        <th style="min-width: 225px;">Evacuee's Information</th>
        <th>Incident</th>
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
      <td>

      </td>
    </tr>
  `);

  Helper.DataTable_Init('#evacuee_list', thead + tbody, async () => {

  });
}

let tableToggler = true;
Core.onClick("#btn-toggle-table", async () => {
  if (tableToggler) {
    Core.f("#btn-toggle-table").innerHTML = "Show Evacuee Lists";
    Load_Archived();
  } else {
    Core.f("#btn-toggle-table").innerHTML = "Show Archived";
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

