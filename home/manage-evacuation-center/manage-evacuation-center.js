import { Core } from "../../core/core.js";
import { CustomNotification } from "../../core/customNotification.js";
import { Helper } from "../../core/helper.js";
import { ModalHandler } from "../../core/modalHandler.js";

const modal_addEvacCenter = "#add-evac-center-modal";
const modal_editEvacCenter = "#edit-evac-center-modal";
const modal_deleteEvacCenter = "#delete-evac-center-modal";

(await Load_EvacCenters)();

async function Load_EvacCenters() {
  const data = (await Core.fetch_data(`${Core.base_url()}/php/evac_center_get_all.php`, "json")).result;
  console.log({ data })

  Helper.DataTable_Reset('#evac_center');

  const thead = `
    <thead>
      <tr>
        <th>#</th>
        <th>Center Name</th>
        <th>Contact</th>
        <th>Address</th>
        <th class="text-center" style="width: 160px !important;">Action</th>
      </tr>
    </thead>
  `;

  let tbody = "<tbody>";
  data.forEach((row, index) => {
    tbody += "<tr>";

    tbody += `<td class="text-center">${index + 1}</td>`;
    tbody += `<td>${row.name}</td>`;
    tbody += `<td>${row.contact}</td>`;
    tbody += `<td>${row.address}</td>`;

    tbody += `<td class="text-center" style="width: 160px !important;">`;
    tbody +=
      `<button
        class="btn btn-sm btn-success btn-open-edit"
        data-toggle="modal"
        data-target="#edit-evac-center-modal"
        data-binder-id="${row.id}"
        data-binder-name="${row.name}"
        data-binder-address="${row.address}"
        data-binder-contact="${row.contact}"
        >
        <i class="fa fa-edit"></i> Edit
      </button> `

    if (Core.user_getData().category === "A")
      tbody +=
        `<button
          class="btn btn-sm btn-danger btn-open-delete"
          data-toggle="modal"
          data-target="#delete-evac-center-modal"
          data-binder-id="${row.id}"
          data-binder-name="${row.name}"
          >
            <i class="fas fa-minus"></i> Hide
        </button> `
    tbody += "</td>";

    tbody += "</tr>";
  });
  tbody += "</tbody>";

  Helper.DataTable_Init('#evac_center', thead + tbody, Load_Functions);
}

let tableToggler = true;
Core.onClick("#btn-toggle-table", async () => {
  if (tableToggler) {
    Core.f("#btn-toggle-table").innerHTML = "Show All Evacuation Center";
    Load_Archived();
  } else {
    Core.f("#btn-toggle-table").innerHTML = "Show Archived";
    Load_EvacCenters();
  }
  tableToggler = !tableToggler;
});

async function Load_Archived() {
  const archived = (await Core.fetch_data(`${Core.base_url()}/php/evac_center_archived.php`, "json")).result;
  Helper.DataTable_Reset('#evac_center');

  const thead = `
    <thead>
      <tr>
        <th style="width: 40px !important;">#</th>
        <th>Evacuation Center Name</th>
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


  Helper.DataTable_Init('#evac_center', thead + tbody, () => {
    async function recover() {
      const raw_data = { deletedflag: 0, name: Core.data(this, "binder-name"), id: Core.data(this, "binder-id") };
      const form_data = Core.createFormData({ ...raw_data, uid: Core.user_getData().id });
      await Core.fetch_data(`${Core.base_url()}/php/evac_center_update.php`, null, form_data).then(async data => {
        CustomNotification.add("Success!", `Item recovered!`, "primary");
        await Load_Archived();
      })
    }

    Core.clearClick(".btn-recover", recover, true);
    Core.onClick(".btn-recover", recover, true);
  });
}



function Load_Functions() {

  Core.clearClick("#btn-open-add", open_add_listener);
  Core.onClick("#btn-open-add", open_add_listener);

  Core.clearClick(`${modal_addEvacCenter}-btn-add`, submit_add_listener);
  Core.onClick(`${modal_addEvacCenter}-btn-add`, submit_add_listener);

  Core.clearClick(".btn-open-edit", open_edit_listener, true);
  Core.onClick(".btn-open-edit", open_edit_listener, true);

  Core.clearClick(`${modal_editEvacCenter}-btn-edit`, submit_edit_listener);
  Core.onClick(`${modal_editEvacCenter}-btn-edit`, submit_edit_listener);

  Core.clearClick(".btn-open-delete", open_delete_listener, true);
  Core.onClick(".btn-open-delete", open_delete_listener, true);

  Core.clearClick(`${modal_deleteEvacCenter}-btn-delete`, submit_delete_listener);
  Core.onClick(`${modal_deleteEvacCenter}-btn-delete`, submit_delete_listener);

}

// <Add Evacation Center>
async function open_add_listener() {
  let layout = (await Core.fetch_data('modal-add.html', "text"));
  Core.f(`${modal_addEvacCenter}-body`).innerHTML = layout;
}

async function submit_add_listener() {
  const form = Core.createFormData({ uid: Core.user_getData().id }, new FormData(Core.f("#evac-center-add-form")));

  if (!Core.isValidForm(form, ["name", "address", "contact"])) {
    Helper.Promt_Error("Please fill required fields.");
    return;
  }

  await Core.fetch_data(`${Core.base_url()}/php/evac_center_new.php`, null, form).then(async data => {
    CustomNotification.add("Success!", `New item added!`, "primary");
    Core.f(`${modal_addEvacCenter}-hide`).click();
    Helper.Promt_Clear();
    await Load_EvacCenters();
  })

}
// </Add Evacation Center>

// <Delete Evacation Center>
async function open_delete_listener() {
  const replace = { name: Core.data(this, "binder-name"), id: Core.data(this, "binder-id") };
  let layout = (await Core.fetch_data('modal-delete.html', "text"));
  layout = Core.replaceLayout(layout, replace);
  Core.f(`${modal_deleteEvacCenter}-body`).innerHTML = layout;
}

async function submit_delete_listener() {
  const form = Core.createFormData({ uid: Core.user_getData().id }, new FormData(Core.f("#evac-center-delete-form")));

  await Core.fetch_data(`${Core.base_url()}/php/evac_center_delete.php`, null, form).then(async data => {
    CustomNotification.add("Success!", `Item archived!`, "primary");
    Core.f(`${modal_deleteEvacCenter}-hide`).click();
    Helper.Promt_Clear();
    await Load_EvacCenters();
  });
}
// </Delete Evacation Center>

// <Edit Evacuation Center>
async function open_edit_listener() {
  const replace = {
    id: Core.data(this, "binder-id"),
    name: Core.data(this, "binder-name"),
    address: Core.data(this, "binder-address"),
    contact: Core.data(this, "binder-contact"),
  };
  let layout = (await Core.fetch_data('modal-edit.html', "text"));
  layout = Core.replaceLayout(layout, replace);
  Core.f(`${modal_editEvacCenter}-body`).innerHTML = layout;
}

async function submit_edit_listener() {
  const form = Core.createFormData({ uid: Core.user_getData().id }, new FormData(Core.f("#evac-center-edit-form")));

  if (!Core.isValidForm(form, ["name", "address", "contact"])) {
    Helper.Promt_Error("Please fill required fields.");
    return;
  }

  await Core.fetch_data(`${Core.base_url()}/php/evac_center_update.php`, null, form).then(async data => {
    CustomNotification.add("Success!", `Item updated!`, "primary");
    Core.f(`${modal_editEvacCenter}-hide`).click();
    Helper.Promt_Clear();
    await Load_EvacCenters();
  });
}
// <Edit Evacuation Center>
