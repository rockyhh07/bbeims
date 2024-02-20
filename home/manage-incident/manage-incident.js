import { Core } from "../../core/core.js";
import { CustomNotification } from "../../core/customNotification.js";
import { Helper } from "../../core/helper.js";
import { ModalHandler } from "../../core/modalHandler.js";

(await Load_Incidents)();

async function Load_Incidents() {
  const data = (await Core.fetch_data(`${Core.base_url()}/php/incident_get_all.php`, "json")).result;

  if ($.fn.DataTable.isDataTable('#incident')) {
    $('#incident').DataTable().destroy();
    $('#incident').html("");
  }

  const thead = `
    <thead>
      <tr>
        <th style="width: 40px !important;">#</th>
        <th>Incident Name</th>
        <th class="text-center" style="width: 160px !important;">Action</th>
      </tr>
    </thead>
    `;

  let tbody = '<tbody>';
  data.forEach((row, index) => {
    tbody += '<tr>';

    tbody += `<td class="text-center">${index + 1}</td>`

    tbody += `<td>${row.name}</td>`;
    tbody +=
      `<td class="text-center" style="width: 160px !important;">
        <button 
          class="btn btn-sm btn-success btn-open-edit"
          data-binder-id="${row.id}"
          data-binder-name="${row.name}"
          data-toggle="modal" 
          data-target="#edit-incident-modal"
        >
          <i class="fa fa-edit"></i> Edit
        </button> `;
    tbody += (Core.user_getData().category === "A" ?
      `<button 
        class="btn btn-sm btn-danger btn-open-delete"
        data-binder-id="${row.id}"
        data-binder-name="${row.name}"
        data-toggle="modal"
        data-target="#delete-incident-modal"
      >
        <i class="fa fa-trash-alt"></i> Delete
      </button>`: '');

    tbody +=
      `</td>`;

    tbody += '</tr>';
  });
  tbody += "</tbody>";

  $("#incident").html('');
  $("#incident").append(thead + tbody);

  Load_Functions();

  $("#incident").DataTable({
    bAutoWidth: false,
    autoWidth: false
  });

}

function Load_Functions() {

  Core.clearClick(`#btn-open-add`, open_add_listerner);
  Core.onClick(`#btn-open-add`, open_add_listerner);

  Core.clearClick(`${modal_addIncident}-btn-add`, submit_add_listener);
  Core.onClick(`${modal_addIncident}-btn-add`, submit_add_listener);

  Core.clearClick(".btn-open-edit", open_edit_listener, true);
  Core.onClick(".btn-open-edit", open_edit_listener, true);

  Core.clearClick(`${modal_editIncident}-btn-edit`, submit_edit_listener);
  Core.onClick(`${modal_editIncident}-btn-edit`, submit_edit_listener);

  Core.clearClick(".btn-open-delete", open_delete_listener, true);
  Core.onClick(".btn-open-delete", open_delete_listener, true);

  Core.clearClick(`${modal_deleteIncident}-btn-delete`, submit_delete_listener);
  Core.onClick(`${modal_deleteIncident}-btn-delete`, submit_delete_listener);

}

const modal_addIncident = "#add-incident-modal";
const modal_editIncident = "#edit-incident-modal";
const modal_deleteIncident = "#delete-incident-modal";

// <Add Incident>
const open_add_listerner = async function (e) {
  let layout = (await Core.fetch_data('modal-add-incident.html', "text"));
  Core.f(`${modal_addIncident}-body`).innerHTML = layout;
}

const submit_add_listener = async function (e) {
  const form = Core.createFormData({ uid: Core.user_getData().id }, new FormData(Core.f("#incident-add-form")));

  if (!Core.isValidForm(form, ["name"])) {
    Helper.Promt_Error("Please fill required fields.");
    return;
  }

  await Core.fetch_data(`${Core.base_url()}/php/incident_new.php`, null, form).then(async data => {
    CustomNotification.add("Success!", `New item added!`, "primary");
    Core.f(`${modal_addIncident}-hide`).click();
    Helper.Promt_Clear();
    await Load_Incidents();
  });
}
// </Add Incident>

// <Edit Incident>
const open_edit_listener = async function (e) {
  const replace = { name: Core.data(this, "binder-name"), id: Core.data(this, "binder-id") };
  let layout = (await Core.fetch_data('modal-edit-incident.html', "text"));
  layout = Core.replaceLayout(layout, replace);
  Core.f(`${modal_editIncident}-body`).innerHTML = layout;
}

const submit_edit_listener = async function (e) {
  const form = Core.createFormData({ uid: Core.user_getData().id }, new FormData(Core.f("#incident-edit-form")));

  if (!Core.isValidForm(form, ["name"])) {
    Helper.Promt_Error("Please fill required fields.");
    return;
  }

  await Core.fetch_data(`${Core.base_url()}/php/incident_update.php`, null, form).then(async data => {
    CustomNotification.add("Success!", `Item updated!`, "primary");
    Core.f(`${modal_editIncident}-hide`).click();
    Helper.Promt_Clear();
    await Load_Incidents();
  });
}
// </Edit Incident>

// </Delete Incident>
const open_delete_listener = async function (e) {
  const replace = { name: Core.data(this, "binder-name"), id: Core.data(this, "binder-id") };
  let layout = (await Core.fetch_data('modal-delete-incident.html', "text"));
  layout = Core.replaceLayout(layout, replace);
  Core.f(`${modal_deleteIncident}-body`).innerHTML = layout;
}

const submit_delete_listener = async function (e) {
  const form = Core.createFormData({ uid: Core.user_getData().id }, new FormData(Core.f("#incident-delete-form")));

  await Core.fetch_data(`${Core.base_url()}/php/incident_delete.php`, null, form).then(async data => {
    CustomNotification.add("Success!", `Item deleted!`, "primary");
    Core.f(`${modal_deleteIncident}-hide`).click();
    Helper.Promt_Clear();
    await Load_Incidents();
  });
}
// </Delete Incident>