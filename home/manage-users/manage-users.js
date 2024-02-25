import { Core } from "../../core/core.js";
import { CustomNotification } from "../../core/customNotification.js";
import { Helper } from "../../core/helper.js";
import { ModalHandler } from "../../core/modalHandler.js";

(await Load_Users)();

async function Load_Users() {
  await Core.fetch_data(
    `${Core.base_url()}/php/user_get_all.php`, "json",
    Core.createFormData({ username: Core.user_getData().username }))
    .then(async data => {
      let users = data.result;
      users = users.filter(user => user.id != Core.user_getData().id);

      Helper.DataTable_Reset('#users');

      const thead = `
        <thead>
          <tr>
            <th>ID</th>
            <th>Information</th>
            <th>Category</th>
            <th>Status</th>
            <th class="text-center" style="width: 160px !important;">Action</th>
          </tr>
        </thead>
      `;

      let tbody = "<tbody>";
      users.forEach((user, index) => {
        let active = user.active === "1";
        let categ = '';
        let age = Helper.getAge(user.birthday)

        switch (user.category) {
          case 'A': categ = 'Admin'; break;
          case 'S': categ = 'Staff'; break;
          default: categ = ''; break;
        }

        tbody += "<tr>";

        // <Body>
        tbody += `<td>${"BB-" + user.id.padStart(6, "0")}</td>`;

        tbody += `
          <td>
            <p class="info">Name: <b>${user.fullname}</b></p>
            <p class="info"><small>Contact: <b>${user.contact}</b></small></p>
            <p class="info"><small>Age: <b>${age ? age : 0}</b></small></p>
          </td>
        `;

        tbody += `<td>${categ}</td>`;
        tbody += `
          <td>
            <span class="badge bg-${active ? 'success' : 'danger'}">${active ? 'Active' : 'Inactive'}</span>
          </td>
        `;

        tbody += `<td class="text-center" style="width: 160px !important;">`;
        {
          tbody +=
            `<button 
              class="btn btn-sm btn-success btn-open-edit"
              data-binder-id="${user.id}"
              data-binder-fullname="${user.fullname}"
              data-binder-contact="${user.contact}"
              data-binder-category="${user.category}"
              data-binder-barangay_id="${user.barangay_id}"
              data-binder-username="${user.username}"
              data-binder-birthday="${user.birthday}"
              data-binder-active="${user.active}"
              data-toggle="modal" 
              data-target="#edit-user-modal"
            >
              <i class="fa fa-edit"></i> Edit
            </button> `;

          tbody += (Core.user_getData().category === "A" ?
            `<button 
              class="btn btn-sm btn-danger btn-open-delete"
              data-binder-id="${user.id}"
              data-binder-fullname="${user.fullname}"
              data-toggle="modal"
              data-target="#delete-user-modal"
            >
              <i class="fas fa-minus"></i> Hide
            </button>`: '');
        }

        tbody += `</td>`;

        // </Body>

        tbody += "</tr>";
      });
      tbody += "</tbody>";

      Helper.DataTable_Init('#users', thead + tbody, Load_Functions);
    });
}

let tableToggler = true;
Core.onClick("#btn-toggle-table", async () => {
  if (tableToggler) {
    Core.f("#btn-toggle-table").innerHTML = "Show All Users";
    Load_Archived();
  } else {
    Core.f("#btn-toggle-table").innerHTML = "Show Archived";
    Load_Users();
  }
  tableToggler = !tableToggler;
});
async function Load_Archived() {
  const archived = (await Core.fetch_data(`${Core.base_url()}/php/user_archived.php`, "json")).result;
  Helper.DataTable_Reset('#users');

  const thead = `
    <thead>
      <tr>
        <th style="width: 40px !important;">#</th>
        <th>User Fullname</th>
        <th class="text-center" style="width: 160px !important;">Action</th>
      </tr>
    </thead>
    `;

  let tbody = '<tbody>';
  archived.forEach((row, index) => {
    tbody += '<tr>';

    tbody += `<td class="text-center">${index + 1}</td>`

    tbody += `<td>${row.fullname}</td>`;
    tbody +=
      `<td class="text-center" style="width: 160px !important;">
        <button 
          class="btn btn-sm btn-primary btn-recover"
          data-binder-id="${row.id}"
          data-binder-fullname="${row.fullname}"
        >
          <i class="fas fa-plus"></i> Recover
        </button> `;

    tbody +=
      `</td>`;

    tbody += '</tr>';
  });
  tbody += "</tbody>";


  Helper.DataTable_Init('#users', thead + tbody, () => {
    async function recover() {
      const raw_data = { deletedflag: 0, fullname: Core.data(this, "binder-fullname"), id: Core.data(this, "binder-id") };
      const form_data = Core.createFormData({ ...raw_data, uid: Core.user_getData().id });
      await Core.fetch_data(`${Core.base_url()}/php/user_update.php`, null, form_data).then(async data => {
        CustomNotification.add("Success!", `Item recovered!`, "primary");
        await Load_Archived();
      })
    }

    Core.clearClick(".btn-recover", recover, true);
    Core.onClick(".btn-recover", recover, true);
  });
}

const modal_editUser = "#edit-user-modal";
const modal_deleteUser = "#delete-user-modal";
const modal_resetPassword = "#reset-password-user-modal";

function Load_Functions() {

  Core.clearClick(".btn-open-edit", open_edit_listener, true);
  Core.onClick(".btn-open-edit", open_edit_listener, true);

  Core.clearClick(`${modal_editUser}-btn-edit`, submit_edit_listener);
  Core.onClick(`${modal_editUser}-btn-edit`, submit_edit_listener);

  Core.clearClick(".btn-open-delete", open_delete_listener, true);
  Core.onClick(".btn-open-delete", open_delete_listener, true);

  Core.clearClick(`${modal_deleteUser}-btn-delete`, submit_delete_listener);
  Core.onClick(`${modal_deleteUser}-btn-delete`, submit_delete_listener);

}

// <Edit>
async function open_edit_listener() {
  const replace = {
    fullname: Core.data(this, "binder-fullname"),
    contact: Core.data(this, "binder-contact"),
    username: Core.data(this, "binder-username"),
    birthday: Core.data(this, "binder-birthday"),
    category: Core.data(this, "binder-category"),
    barangay_id: Core.data(this, "binder-barangay_id"),
    active: Core.data(this, "binder-active"),
    barangay_list: (await Load_BarangayList()),
    id: Core.data(this, "binder-id"),
  };
  let layout = (await Core.fetch_data('modal-edit.html', "text"));
  layout = Core.replaceLayout(layout, replace);

  Core.f(`${modal_editUser}-body`).innerHTML = layout;
  Core.f(`select[name="category"]`).value = replace.category;
  Core.f(`select[name="barangay_id"]`).value = replace.barangay_id;
  Core.f(`input[name="active"]`).checked = replace.active == "1";

  Core.onClick("#reset-password", open_resetPassword);
}

async function Load_BarangayList() {
  const barangay_list = (await Core.fetch_data(`${Core.base_url()}/php/barangay_get_all.php`, "json")).result;
  let barangay_selectTag = '';
  barangay_list.forEach(v => barangay_selectTag += `<option value="${v.id}">${v.name}</option>`);
  return barangay_selectTag;
}

async function submit_edit_listener() {
  const form_data = Core.createFormData({ uid: Core.user_getData().id }, new FormData(Core.f("#user-edit-form")));
  await Core.fetch_data(`${Core.base_url()}/php/user_update.php`, null, form_data).then(async data => {
    CustomNotification.add("Success!", `User Updated!`, "primary");
    Core.f(`${modal_editUser}-hide`).click();
    Helper.Promt_Clear();
    await Load_Users();
  });
}

async function open_resetPassword() {
  Core.f(`${modal_editUser}-hide`).click();

  const replace = {
    fullname: Core.data(this, "binder-fullname"),
    id: Core.data(this, "binder-id"),
  };
  let layout = (await Core.fetch_data('modal-reset-password.html', "text"));
  layout = Core.replaceLayout(layout, replace);
  Core.f(`${modal_resetPassword}-body`).innerHTML = layout;
  Core.onClick(`${modal_resetPassword}-btn-reset-password`, submit_resetPassword)
}

async function submit_resetPassword() {
  const form_data = Core.createFormData({ uid: Core.user_getData().id }, new FormData(Core.f("#user-reset-password-form")));

  await Core.fetch_data(`${Core.base_url()}/php/user_resetpassword.php`, null, form_data).then(async data => {
    CustomNotification.add("Success!", `User password has been reset!`, "primary");
    Core.f(`${modal_resetPassword}-hide`).click();
    Helper.Promt_Clear();
    await Load_Users();
  }).catch(err => {
    CustomNotification.add("Error!", `Error occurred. Try again later.`, "danger");
    Core.f(`${modal_resetPassword}-hide`).click();
    Helper.Promt_Clear();
  });

}
// </Edit>


// <Delete>
async function open_delete_listener() {
  const replace = {
    fullname: Core.data(this, "binder-fullname"),
    id: Core.data(this, "binder-id"),
  };
  let layout = (await Core.fetch_data('modal-delete.html', "text"));
  layout = Core.replaceLayout(layout, replace);
  Core.f(`${modal_deleteUser}-body`).innerHTML = layout;
}

async function submit_delete_listener() {
  const form = Core.createFormData({ uid: Core.user_getData().id }, new FormData(Core.f("#user-delete-form")));

  await Core.fetch_data(`${Core.base_url()}/php/user_delete.php`, null, form).then(async data => {
    CustomNotification.add("Success!", `Item deleted!`, "primary");
    Core.f(`${modal_deleteUser}-hide`).click();
    Helper.Promt_Clear();
    await Load_Users();
  });
}
// </Delete>
