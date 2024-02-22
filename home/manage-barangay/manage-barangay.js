import { Core } from "../../core/core.js";
import { CustomNotification } from "../../core/customNotification.js";
import { Helper } from "../../core/helper.js";
import { ModalHandler } from "../../core/modalHandler.js";

const modal_addbarangay = "#add-barangay-modal";
const modal_editbarangay = "#edit-barangay-modal";
const modal_deletebarangay = "#delete-barangay-modal";

(await Load_Barangays)();

async function Load_Barangays() {
  const data = (await Core.fetch_data(`${Core.base_url()}/php/barangay_get_all.php`, "json")).result;

  Helper.DataTable_Reset('#barangay');

  const thead = `
    <thead>
      <tr>
        <th style="width: 40px !important;">#</th>
        <th>Barangay Name</th>
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
          data-target="#edit-barangay-modal"
        >
          <i class="fa fa-edit"></i> Edit
        </button> `;
    tbody += (Core.user_getData().category === "A" ?
      `<button 
        class="btn btn-sm btn-danger btn-open-delete"
        data-binder-id="${row.id}"
        data-binder-name="${row.name}"
        data-toggle="modal"
        data-target="#delete-barangay-modal"
      >
        <i class="fas fa-minus"></i> Hide
      </button>`: '');

    tbody +=
      `</td>`;

    tbody += '</tr>';
  });
  tbody += "</tbody>";


  Helper.DataTable_Init('#barangay', thead + tbody, Load_Functions);
}

let tableToggler = true;
Core.onClick("#btn-toggle-table", async () => {
  if (tableToggler) {
    Core.f("#btn-toggle-table").innerHTML = "Show All Barangay";
    Load_Archived();
  } else {
    Core.f("#btn-toggle-table").innerHTML = "Show Archived";
    Load_Barangays();
  }
  tableToggler = !tableToggler;
});

async function Load_Archived() {
  const archived = (await Core.fetch_data(`${Core.base_url()}/php/barangay_archived.php`, "json")).result;
  Helper.DataTable_Reset('#barangay');

  const thead = `
    <thead>
      <tr>
        <th style="width: 40px !important;">#</th>
        <th>Barangay Name</th>
        <th class="text-center" style="width: 160px !important;">Action</th>
      </tr>
    </thead>
    `;

  let tbody = '<tbody>';
  archived.forEach((row, index) => {
    tbody += '<tr>';

    tbody += `<td class="text-center">${index + 1}</td>`

    tbody += `<td>${row.name}</td>`;
    tbody +=
      `<td class="text-center" style="width: 160px !important;">
        <button 
          class="btn btn-sm btn-primary btn-recover"
          data-binder-id="${row.id}"
          data-binder-name="${row.name}"
        >
          <i class="fas fa-plus"></i> Recover
        </button> `;

    tbody +=
      `</td>`;

    tbody += '</tr>';
  });
  tbody += "</tbody>";


  Helper.DataTable_Init('#barangay', thead + tbody, () => {
    async function recover() {
      const raw_data = { deletedflag: 0, name: Core.data(this, "binder-name"), id: Core.data(this, "binder-id") };
      const form_data = Core.createFormData({ ...raw_data, uid: Core.user_getData().id });
      await Core.fetch_data(`${Core.base_url()}/php/barangay_update.php`, null, form_data).then(async data => {
        CustomNotification.add("Success!", `Item recovered!`, "primary");
        await Load_Archived();
      })
    }

    Core.clearClick(".btn-recover", recover, true);
    Core.onClick(".btn-recover", recover, true);
  });
}

function Load_Functions() {

  Core.clearClick(`#btn-open-add`, open_add_listerner);
  Core.onClick(`#btn-open-add`, open_add_listerner);

  Core.clearClick(`${modal_addbarangay}-btn-add`, submit_add_listener);
  Core.onClick(`${modal_addbarangay}-btn-add`, submit_add_listener);

  Core.clearClick(".btn-open-edit", open_edit_listener, true);
  Core.onClick(".btn-open-edit", open_edit_listener, true);

  Core.clearClick(`${modal_editbarangay}-btn-edit`, submit_edit_listener);
  Core.onClick(`${modal_editbarangay}-btn-edit`, submit_edit_listener);

  Core.clearClick(".btn-open-delete", open_delete_listener, true);
  Core.onClick(".btn-open-delete", open_delete_listener, true);

  Core.clearClick(`${modal_deletebarangay}-btn-delete`, submit_delete_listener);
  Core.onClick(`${modal_deletebarangay}-btn-delete`, submit_delete_listener);

}

// <Add barangay>
async function open_add_listerner(e) {
  let layout = (await Core.fetch_data('modal-add.html', "text"));
  Core.f(`${modal_addbarangay}-body`).innerHTML = layout;
}

async function submit_add_listener(e) {
  const form = Core.createFormData({ uid: Core.user_getData().id }, new FormData(Core.f("#add-form")));

  if (!Core.isValidForm(form, ["name"])) {
    Helper.Promt_Error("Please fill required fields.");
    return;
  }

  await Core.fetch_data(`${Core.base_url()}/php/barangay_new.php`, null, form).then(async data => {
    CustomNotification.add("Success!", `New item added!`, "primary");
    Core.f(`${modal_addbarangay}-hide`).click();
    Helper.Promt_Clear();
    await Load_Barangays();
  });
}
// </Add barangay>

// <Edit barangay>
async function open_edit_listener(e) {
  const replace = { name: Core.data(this, "binder-name"), id: Core.data(this, "binder-id") };
  let layout = (await Core.fetch_data('modal-edit.html', "text"));
  layout = Core.replaceLayout(layout, replace);
  Core.f(`${modal_editbarangay}-body`).innerHTML = layout;
}

async function submit_edit_listener(e) {
  const form = Core.createFormData({ uid: Core.user_getData().id }, new FormData(Core.f("#edit-form")));

  if (!Core.isValidForm(form, ["name"])) {
    Helper.Promt_Error("Please fill required fields.");
    return;
  }

  await Core.fetch_data(`${Core.base_url()}/php/barangay_update.php`, null, form).then(async data => {
    CustomNotification.add("Success!", `Item updated!`, "primary");
    Core.f(`${modal_editbarangay}-hide`).click();
    Helper.Promt_Clear();
    await Load_Barangays();
  });
}
// </Edit barangay>

// </Delete barangay>
async function open_delete_listener(e) {
  const replace = { name: Core.data(this, "binder-name"), id: Core.data(this, "binder-id") };
  let layout = (await Core.fetch_data('modal-delete.html', "text"));
  layout = Core.replaceLayout(layout, replace);
  Core.f(`${modal_deletebarangay}-body`).innerHTML = layout;
}

async function submit_delete_listener(e) {
  const form = Core.createFormData({ uid: Core.user_getData().id }, new FormData(Core.f("#delete-form")));

  await Core.fetch_data(`${Core.base_url()}/php/barangay_delete.php`, null, form).then(async data => {
    CustomNotification.add("Success!", `Item Archived!`, "primary");
    Core.f(`${modal_deletebarangay}-hide`).click();
    Helper.Promt_Clear();
    await Load_Barangays();
  });
}
// </Delete barangay>