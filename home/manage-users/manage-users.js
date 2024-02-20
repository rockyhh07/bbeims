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
      users = users.filter(user => user.id != Core.user_getData().id)

      if ($.fn.DataTable.isDataTable('#users')) {
        $('#users').DataTable().destroy();
        $('#users').html("");
      }

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
              data-binder-username="${user.username}"
              data-binder-birthday="${user.birthday}"
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
              <i class="fa fa-trash-alt"></i> Delete
            </button>`: '');
        }

        tbody += `</td>`;

        // </Body>

        tbody += "</tr>";
      });
      tbody += "</tbody>";


      $('#users').html("");
      $('#users').append(thead + tbody);

      Load_Functions();

      $("#users").DataTable({
        bAutoWidth: false,
        autoWidth: false
      });
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
    id: Core.data(this, "binder-id"),
  };
  let layout = (await Core.fetch_data('modal-edit.html', "text"));
  layout = Core.replaceLayout(layout, replace);
  Core.f(`${modal_editUser}-body`).innerHTML = layout;
  Core.onClick("#reset-password", open_resetPassword);
}

async function submit_edit_listener() {
  console.log("edit-submit")
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
    CustomNotification.add("Success!", `Item deleted!`, "primary");
    Core.f(`${modal_resetPassword}-hide`).click();
    Helper.Promt_Clear();
    await Load_Incidents();
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

/*

function updateDate(body = {}) {
  show_alert({
    title : "Update User",
    body : `<form id="user-update-form" class="d-flex flex-column" style="gap: .5rem;">
          <div class="input-group">
              <span class="input-group-text" id="user-username">Username</span>
              <input name="username" value="${body.username}" type="text" class="form-control" placeholder="Username" aria-describedby="user-username" readonly>
          </div>
          <div class="input-group">
              <span class="input-group-text" id="user-fullname">Full name</span>
              <input name="fullname" value="${body.fullname}" type="text" class="form-control" placeholder="Fullname" aria-describedby="user-fullname">
          </div>
          <div class="input-group">
              <span class="input-group-text" id="user-contact">Contact</span>
              <input name="contact" value="${body.contact}" type="text" class="form-control" placeholder="Contact" aria-describedby="user-contact">
          </div>
          <div class="input-group">
              <span class="input-group-text" id="user-birthday">Birthday</span>
              <input name="birthday" value="${body.birthday}" type="date" class="form-control" placeholder="Contact" aria-describedby="user-birthday">
          </div>
          <hr>
          <div class="input-group">
              <span class="input-group-text" id="user-category">Category</span>
              <select name="category" class="form-control">
                <option value="A" ${body.category == "A" ? "selected" : ""}>Admin</option>   
                <option value="S" ${body.category == "S" ? "selected" : ""}>Staff</option>   
              </select>
          </div>
          <div class="custom-control custom-switch">
              <input type="checkbox" class="custom-control-input" name="active" id="active-switch" ${(body.active === "1") ? "checked" : ""}>
              <label class="custom-control-label" for="active-switch">Active</label>
          </div>
          <hr>
          <div class="input-group">
              <span class="input-group-text" id="user-o_password">Old password</span>
              <input name="o_password" type="password" class="form-control" placeholder="Password" aria-describedby="user-o_password" autocomplete>
          </div>
          <div class="input-group">
              <span class="input-group-text" id="user-n_password">New password</span>
              <input id="n_password" name="n_password" type="password" class="form-control" placeholder="Password" aria-describedby="user-n_password" autocomplete>
          </div>
        </form>`,
    buttons : ["Save", "Cancel"]
  }, function(ans){
    if(!ans) return;

    const form = new FormData(document.getElementById("user-update-form"));
    form.append("uid", USER_DATA.id);
    form.append("id", body.id);

    // if(!validForm(form)) return;

    const n_pass = $("#n_password").val();

    // form.forEach((val, key) => { console.log({key, val}); });

    fetch(`${BASE_URL}php/user_update.php`,{
        method : 'post',
        body : form
    })
    .then(response => response.json())
    .then(response => {
        const res1 = response.result[0].result;
        const res2 = response.result[1].result;
        
        if(res1) {
          show_alert({
              title : "Account updated!",
              body : "Successfully updated user information.",
              buttons : ["Okay"]
          });
        } else {
          show_alert({
              title : `<i class="fas fa-exclamation-triangle"></i> Error updating information`,
              body : "Error occured while updating user information.",
              buttons : ["Okay"]
          });
        }

        if(n_pass != "") {
          if(res2) {
              show_alert({
                title : "Password updated!",
                body : "Successfully updated user password.",
                buttons : ["Okay"]
              });
          } else {
              show_alert({
                title : `<i class="fas fa-exclamation-triangle"></i> Error updating password`,
                body : "Password did not matched.",
                buttons : ["Okay"]
              });
          }  
        }

        loadUsers();

    })
    .catch(err => console.error("ERROR: " + err));
  });
}

async function loadUsers() {
  const form = new FormData();
  form.append('username', USER_DATA.username);
  fetch(BASE_URL + 'php/user_get_all.php',{
    method : 'post',
    body : form
  })
  .then(response => response.json())
  .then(response => {
    if (!response.result) {console.error("Result not defined"); return; };
    let result = response.result;

    if ($.fn.DataTable.isDataTable('#users')) {
        $('#users').DataTable().destroy();
        $('#users').html("");
    }

    const thead = `
        <thead>
          <tr>
              <th>ID</th>
              <th>Users info</th>
              <th>Account Category</th>
              <th>Account Status</th>
              <th class="text-center" style="width: 160px !important;">Action</th>
          </tr>
        </thead>
    `;
    
    let tbody = `<tbody>`;
    for(let row of result){
        let active = row.active === "1";
        let categ = undefined;
        let age = getAge(row.birthday)

        switch (row.category){
          case 'A' : categ = 'Admin'; break;
          case 'S' : categ = 'Staff'; break;
          default : categ = ''; break;
        }

        tbody += `<tr>`;
        {  
          tbody += `<td>${"BB-"+row.id.padStart(6, "0")}</td>`;
          tbody += `<td>
                <p class="info">Name: <b>${row.fullname}</b></p>
                <p class="info"><small>Contact: <b>${row.contact}</b></small></p>
                <p class="info"><small>Age: <b>${age ? age : 0}</b></small></p>
              </td>`;
          tbody += `<td>${categ}</td>`;
          tbody += `<td><span class="badge bg-${active?'success':'danger'}">${active?'Active':'Inactive'}</span></td>`;
          tbody += `<td class="text-center" style="width: 160px !important;">
                <button class="btn btn-sm btn-success" href="#" onclick="updateDate({
                    username : '${row.username}',
                    id : '${row.id}', 
                    fullname : '${row.fullname}', 
                    birthday : '${row.birthday}', 
                    category : '${row.category}', 
                    contact : '${row.contact}', 
                    active : '${row.active}'
                })"><i class="fa fa-edit"></i> Edit</button>
                <button class="btn btn-sm btn-danger" href="#" onclick="delete_data(this)" data-id="${row.id}" data-name="${row.fullname}">
                    <i class="fa fa-trash-alt"></i> Delete
                </button>
              </td>`;
        }
        tbody += `</tr>`;
    }
    tbody += `</tbody>`;
    
    $('#users').append(thead + tbody);
    $("#users").DataTable({
        bAutoWidth : false,
        autoWidth: false
    });
  });
}

loadUsers();

function delete_data(e) {
  const name = String($(e).data("name"));
  const id = String($(e).data("id"));
  show_alert({
    title : `<img src="${BASE_URL}asset/img/sent.png" > Delete record`,
    body : `Are you sure you want to delete <b>${name}</b>?`,
    buttons : ["Yes", "No"]
  }, function(ans) {
    if(!ans) return;

    const form = new FormData();
    form.append("id", id);
    form.append("uid", USER_DATA.id);

    if(!validForm(form)) return;

    fetch(`${BASE_URL}php/user_delete.php`, {
        method : 'post',
        body : form
    })
    .then(response => response.json())
    .then(response => {
        loadUsers();
        addNotif("User Deleted", "User deleted successfully!", "g");
    })
    .catch(err => console.log("ERROR" + err));
  });
}

*/