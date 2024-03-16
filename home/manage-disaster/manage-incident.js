import { Core } from "../../core/core.js";
import { CustomNotification } from "../../core/customNotification.js";
import { Helper } from "../../core/helper.js";
import { ModalHandler } from "../../core/modalHandler.js";

(await Load_Disasters)();

async function Load_Disasters() {
  const data = (await Core.fetch_data(`${Core.base_url()}/php/disaster_get_all.php`, "json")).result;

  Helper.DataTable_Reset('#disaster');

  const thead = `
    <thead>
      <tr>
        <th style="width: 40px !important;">#</th>
        <th>Disaster Name</th>
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
          data-target="#edit-disaster-modal"
        >
          <i class="fa fa-edit"></i> Edit
        </button> `;
    tbody += (Core.user_getData().category === "A" ?
      `<button 
        class="btn btn-sm btn-danger btn-open-delete"
        data-binder-id="${row.id}"
        data-binder-name="${row.name}"
        data-toggle="modal"
        data-target="#delete-disaster-modal"
      >
        <i class="fas fa-minus"></i> Hide
      </button>`: '');

    tbody +=
      `</td>`;

    tbody += '</tr>';
  });
  tbody += "</tbody>";

  Helper.DataTable_Init('#disaster', thead + tbody, Load_Functions);
}

let tableToggler = true;
Core.onClick("#btn-toggle-table", async () => {
  if (tableToggler) {
    Core.f("#btn-toggle-table").innerHTML = "Show All Disaster";
    Load_Archived();
  } else {
    Core.f("#btn-toggle-table").innerHTML = "Show Archived";
    Load_Disasters();
  }
  tableToggler = !tableToggler;
});

async function Load_Archived() {
  const data = (await Core.fetch_data(`${Core.base_url()}/php/disaster_archived.php`, "json")).result;

  Helper.DataTable_Reset('#disaster');

  const thead = `
    <thead>
      <tr>
        <th style="width: 40px !important;">#</th>
        <th>Disaster Name</th>
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

  Helper.DataTable_Init('#disaster', thead + tbody, () => {
    async function recover() {
      const raw_data = { deletedflag: 0, name: Core.data(this, "binder-name"), id: Core.data(this, "binder-id") };
      const form_data = Core.createFormData({ ...raw_data, uid: Core.user_getData().id });
      await Core.fetch_data(`${Core.base_url()}/php/disaster_update.php`, null, form_data).then(async data => {
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

  Core.clearClick(`${modal_adddisaster}-btn-add`, submit_add_listener);
  Core.onClick(`${modal_adddisaster}-btn-add`, submit_add_listener);

  Core.clearClick(".btn-open-edit", open_edit_listener, true);
  Core.onClick(".btn-open-edit", open_edit_listener, true);

  Core.clearClick(`${modal_editdisaster}-btn-edit`, submit_edit_listener);
  Core.onClick(`${modal_editdisaster}-btn-edit`, submit_edit_listener);

  Core.clearClick(".btn-open-delete", open_delete_listener, true);
  Core.onClick(".btn-open-delete", open_delete_listener, true);

  Core.clearClick(`${modal_deletedisaster}-btn-delete`, submit_delete_listener);
  Core.onClick(`${modal_deletedisaster}-btn-delete`, submit_delete_listener);

}

const modal_adddisaster = "#add-disaster-modal";
const modal_editdisaster = "#edit-disaster-modal";
const modal_deletedisaster = "#delete-disaster-modal";

// <Add disaster>
const open_add_listerner = async function (e) {
  let layout = (await Core.fetch_data('modal-add-disaster.html', "text"));
  Core.f(`${modal_adddisaster}-body`).innerHTML = layout;
}

const submit_add_listener = async function (e) {
  const form = Core.createFormData({ uid: Core.user_getData().id }, new FormData(Core.f("#disaster-add-form")));

  if (!Core.isValidForm(form, ["name"])) {
    Helper.Promt_Error("Please fill required fields.");
    return;
  }

  await Core.fetch_data(`${Core.base_url()}/php/disaster_new.php`, null, form).then(async data => {
    CustomNotification.add("Success!", `New item added!`, "primary");
    Core.f(`${modal_adddisaster}-hide`).click();
    Helper.Promt_Clear();
    await Load_Disasters();
  });
}
// </Add disaster>

// <Edit disaster>
const open_edit_listener = async function (e) {
  const replace = { name: Core.data(this, "binder-name"), id: Core.data(this, "binder-id") };
  let layout = (await Core.fetch_data('modal-edit-disaster.html', "text"));
  layout = Core.replaceLayout(layout, replace);
  Core.f(`${modal_editdisaster}-body`).innerHTML = layout;
}

const submit_edit_listener = async function (e) {
  const form = Core.createFormData({ uid: Core.user_getData().id }, new FormData(Core.f("#disaster-edit-form")));

  if (!Core.isValidForm(form, ["name"])) {
    Helper.Promt_Error("Please fill required fields.");
    return;
  }

  await Core.fetch_data(`${Core.base_url()}/php/disaster_update.php`, null, form).then(async data => {
    CustomNotification.add("Success!", `Item updated!`, "primary");
    Core.f(`${modal_editdisaster}-hide`).click();
    Helper.Promt_Clear();
    await Load_Disasters();
  });
}
// </Edit disaster>

// </Delete disaster>
const open_delete_listener = async function (e) {
  const replace = { name: Core.data(this, "binder-name"), id: Core.data(this, "binder-id") };
  let layout = (await Core.fetch_data('modal-delete-disaster.html', "text"));
  layout = Core.replaceLayout(layout, replace);
  Core.f(`${modal_deletedisaster}-body`).innerHTML = layout;
}

const submit_delete_listener = async function (e) {
  const form = Core.createFormData({ uid: Core.user_getData().id }, new FormData(Core.f("#disaster-delete-form")));

  await Core.fetch_data(`${Core.base_url()}/php/disaster_delete.php`, null, form).then(async data => {
    CustomNotification.add("Success!", `Item archived!`, "primary");
    Core.f(`${modal_deletedisaster}-hide`).click();
    Helper.Promt_Clear();
    await Load_Disasters();
  });
}
// </Delete disaster>