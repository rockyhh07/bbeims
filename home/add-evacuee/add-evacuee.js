import { Core } from "../../core/core.js";
import { CustomNotification } from "../../core/customNotification.js";
import { Helper } from "../../core/helper.js";
import { ModalHandler } from "../../core/modalHandler.js";

let rep_MODE = true;
let modal_addMember = "#add-modal";
let modal_deleteMember = "#delete-modal";
let modal_editMember = "#edit-modal";
let modal_add_evacuees = "#add_evacuees-modal";
let center_list = (await Core.api('/evac_center_get_all', "json")).result;
let incident_list = (await Core.api('/incident_get_all', "json")).result;

console.log(center_list)
console.log(incident_list)

let selected_rep = {
  id: -1,
  fname: '-1',
  mname: '-1',
  lname: '-1',
  address: '-1',
  mem_ids: [],
}

function resetSelected_rep() {
  selected_rep = {
    ...selected_rep,
    id: -1,
    fname: '-1',
    mname: '-1',
    lname: '-1',
    address: '-1',
    mem_ids: [],
  }
}

function set_repMode(newRepMode = undefined) {
  rep_MODE = newRepMode ?? rep_MODE;
  console.log({ rep_MODE })
  if (rep_MODE) {
    Core.f(".memMode_content", true)().forEach(v => v.style.display = "none")
    Core.f(".repMode_content", true)().forEach(v => v.style.display = "flex")
  } else {
    Core.f(".memMode_content", true)().forEach(v => v.style.display = "flex")
    Core.f(".repMode_content", true)().forEach(v => v.style.display = "none")
  }
}

Core.onClick("#btn-back", async function () {
  resetSelected_rep();
  set_repMode(true)
  Toggler(false);
  await Load_Representatives();
});

Core.onClick("#btn-add_rep", addNewEvacuee, true);
Core.onClick("#btn-add_mem", addNewEvacuee, true);

async function addNewEvacuee() {
  let layout = (await Core.fetch_data('./modal-add_member.html', "text"));
  layout = Core.replaceLayout(layout, {
    title: `Adding new ${rep_MODE ? 'Representative' : 'Member'}.`,
    address: rep_MODE ? '' : selected_rep.address,
  })
  Core.f(`${modal_addMember}-body`).innerHTML = layout;
}

Core.onClick(`${modal_addMember}-btn-add`, async function () {
  const form_data = Core.createFormData(
    {
      rep: rep_MODE ? "rep" : undefined,
      uid: Core.user_getData().id,
      barangay_id: Core.user_getData().barangay_id,
      representative: rep_MODE ? undefined : selected_rep.id,
    },
    new FormData(Core.f("#add_member-form"))
  );

  if (!Core.isValidForm(form_data, ["fname", "mname", "lname", "contact", "birthday", "address", "gender", "civil_status"])) {
    Helper.Promt_Error("Please fill required fields.");
    return;
  }

  if (Core.formValidator(form_data, ["birthday"], v => Helper.getAge(v) >= 0).length > 0) {
    Helper.Promt_Error("Invalid value. Please make sure values are valid.");
    return;
  }

  Helper.Promt_Clear();

  const data = (await Core.api('/evacuee_new', "json", form_data)).result[0];
  if (data.result) {
    CustomNotification.add("Success!", `New Member added!`, "primary");
    Core.f(`${modal_addMember}-hide`).click();
    Helper.Promt_Clear();
    if (rep_MODE) await Load_Representatives();
    else await Load_Members(selected_rep);
  } else {
    CustomNotification.add("Error", "An Error occurred. Try again later.", "danger");
    console.log({ data });
  }
});














Core.onClick("#btn-add-report", async function () {
  const replace = {
    incident: '',
    evac_center: '',
  }

  incident_list.forEach(v => replace.incident += `<option value="${v.id}">${v.name}</option>`);
  center_list.forEach(v => replace.evac_center += `<option value="${v.id}">${v.name}</option>`);

  let layout = (await Core.fetch_data('./modal-add_evacuees.html', "text"));
  layout = Core.replaceLayout(layout, replace);
  Core.f(`${modal_add_evacuees}-body`).innerHTML = layout;
});

Core.onClick(`${modal_add_evacuees}-btn-add_evacuees`, async function () {
  const form_data = Core.createFormData({ uid: Core.user_getData().id, ids: JSON.stringify(selected_rep.mem_ids) }, new FormData(Core.f("#add_evacuees-form")));

  if (!Core.isValidForm(form_data, ["incident_date", "incident", "evac_center"])) {
    Helper.Promt_Error("Please fill required fields.");
    return;
  }

  Helper.Promt_Clear();

  const res = (await Core.api('/evacuee_new_incident', "json", form_data)).result[0][0];
  if (res) {
    CustomNotification.add("Success!", `Members are added to Evacuation Center!`, "primary");
    Core.f(`${modal_add_evacuees}-hide`).click();
    Helper.Promt_Clear();
    await Load_Members(selected_rep);
  } else {
    CustomNotification.add("Error", "An Error occurred. Try again later.", "danger");
    console.log({ res });
  }
});

















async function Load_Members({ id, fname, lname, mname, address }) {
  selected_rep = { id: id, fname: fname, lname: lname, mname: mname, address: address }
  const list = (await Core.api(`/house_member_get`, "json", Core.createFormData({ rep_id: selected_rep.id, uid: Core.user_getData().id }))).result;
  if (!list) { console.error("ERROR OCCURRED!"); return; }
  console.log({ list });

  selected_rep.mem_ids = list.map(v => Number(v.id));

  Helper.DataTable_Reset('#representative_list');

  const thead = `
    <thead>
      <tr>
        <th class="text-center">ID</th>
        <th style="min-width: 225px;">Members</th>
        <th class="text-center">Action</th>
      </tr>
    </thead>
  `;

  let tbody = '';
  list.forEach((v, i) => {
    tbody += `
    <tr>
      <td class="text-center text-nowrap">${Helper.AsID(v.id, 6, '0', v.id == v.representative ? "H-" : "M-")}</td>
      <td>
        <button class="btn btn-sm btn-link" data-toggle="collapse" data-target="#collapse-${i}" aria-expanded="false" aria-controls="collapse-${i}">
          ${v.lname}, ${v.fname} ${v.mname}
        </button>

        <div id="collapse-${i}" class="collapse p-2">
          <div class="row">
            <section class="col-sm-3 font-weight-bold" >Age</section>
            <section class="col-sm-9">${Helper.getAge(v.birthday)}</section>
          </div>
          <div class="row">
            <section class="col-sm-3 font-weight-bold" >Gender</section>
            <section class="col-sm-9">${Helper.getGender(v.gender)}</section>
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
      <td class="text-center">
        ${v.id != selected_rep.id ? `<button 
          class="btn btn-success btn-sm btn-open-edit"
          data-binder-id="${v.id}"
          data-binder-fname="${v.fname}"
          data-binder-mname="${v.mname}"
          data-binder-lname="${v.lname}"
          data-binder-contact="${v.contact}"
          data-binder-birthday="${v.birthday}"
          data-binder-address="${v.address}"
          data-binder-gender="${v.gender}"
          data-binder-civil_status="${v.civil_status}"
          data-toggle="modal"
          data-target="#edit-modal"
        >
          <i class="fa fa-edit"></i> Edit
        </button>
        ${Core.user_getData().category === "A" ? `
        <button 
          class="btn btn-danger btn-sm btn-open-delete"
          data-binder-id="${v.id}"
          data-binder-name="${v.lname}, ${v.fname} ${v.mname}"
          data-toggle="modal"
          data-target="#delete-modal"
        >
          <i class="fas fa-minus"></i> Hide
        </button>` : ''}` : ''}
      </td>
    </tr>
  `});


  Helper.DataTable_Init('#representative_list', thead + tbody, Load_Functions);
}































async function Load_Representatives() {
  const list = (await Core.api(`/representative_get_all`, "json")).result;
  if (!list) { console.error("ERROR OCCURRED!"); return; }
  console.log({ list });

  Helper.DataTable_Reset('#representative_list');

  const thead = `
    <thead>
      <tr>
        <th class="text-center">ID</th>
        <th style="min-width: 225px;">Representative</th>
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
            <section class="col-sm-3 font-weight-bold" >Age</section>
            <section class="col-sm-9">${Helper.getAge(v.birthday)}</section>
          </div>
          <div class="row">
            <section class="col-sm-3 font-weight-bold" >Gender</section>
            <section class="col-sm-9">${Helper.getGender(v.gender)}</section>
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
      <td class="text-center" style="width: 60px !important;">
        <div class="btn-group dropleft">
          <button type="button" class="btn btn-sm btn-link text-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-v"></i>
          </button>
          <div class="dropdown-menu">
            <button 
              class="dropdown-item btn btn-link btn-sm text-secondary btn-open-edit"
              data-binder-id="${v.id}"
              data-binder-fname="${v.fname}"
              data-binder-mname="${v.mname}"
              data-binder-lname="${v.lname}"
              data-binder-contact="${v.contact}"
              data-binder-birthday="${v.birthday}"
              data-binder-address="${v.address}"
              data-binder-gender="${v.gender}"
              data-binder-civil_status="${v.civil_status}"
              data-toggle="modal"
              data-target="#edit-modal"
            >
              <i class="fas fa-edit"></i> Edit Representative
            </button>
            <button 
              class="dropdown-item btn btn-link btn-sm text-secondary btn-open-memberList"
              data-binder-id="${v.id}"
              data-binder-fname="${v.fname}"
              data-binder-mname="${v.mname}"
              data-binder-lname="${v.lname}"
              data-binder-address="${v.address}"
            >
              <i class="fas fa-user-check"></i> Manage Members
            </button>
            <div class="dropdown-divider"></div>
            <button 
              class="dropdown-item btn btn-link btn-sm text-danger btn-open-delete"
              data-binder-id="${v.id}"
              data-binder-name="${v.lname}, ${v.fname} ${v.mname}"
              data-toggle="modal"
              data-target="#delete-modal"
            >
              <i class="fas fa-minus"></i> Hide
            </button>
          </div>
        </div>
      </td>
    </tr>
  `);


  // <button
  //   class="dropdown-item btn btn-link btn-sm text-secondary btn-open-memberList"
  //   data-binder-id="${v.id}"
  //   data-binder-fname="${v.fname}"
  //   data-binder-mname="${v.mname}"
  //   data-binder-lname="${v.lname}"
  //   data-binder-address="${v.address}"
  // >
  //   <i class="fas fa-pencil-alt"></i> Edit Members
  // </button>

  Helper.DataTable_Init('#representative_list', thead + tbody, Load_Functions);
}

function Load_Functions() {

  Core.clearClick('.btn-open-delete', open_delete_listener, true);
  Core.onClick('.btn-open-delete', open_delete_listener, true);

  Core.clearClick(`${modal_deleteMember}-btn-delete`, submit_delete_listener);
  Core.onClick(`${modal_deleteMember}-btn-delete`, submit_delete_listener);

  Core.clearClick('.btn-open-edit', open_edit_listener, true);
  Core.onClick('.btn-open-edit', open_edit_listener, true);

  Core.clearClick(`${modal_editMember}-btn-edit`, submit_edit_listener);
  Core.onClick(`${modal_editMember}-btn-edit`, submit_edit_listener);

  Core.clearClick('.btn-open-memberList', open_memberList_listener, true);
  Core.onClick('.btn-open-memberList', open_memberList_listener, true);

}

// -- < Open Member > --
async function open_memberList_listener() {
  console.log("HELLO  ")
  await Load_Members({
    id: Core.data(this, "binder-id"),
    fname: Core.data(this, "binder-fname"),
    mname: Core.data(this, "binder-mname"),
    lname: Core.data(this, "binder-lname"),
    address: Core.data(this, "binder-address"),
  });
  set_repMode(false);
}
// -- < /Open Member > --


// -- < Edit > --
async function open_edit_listener() {
  const replace = {
    title: `Updating a ${rep_MODE ? 'Representative' : 'Member'}`,
    id: Core.data(this, "binder-id"),
    fname: Core.data(this, "binder-fname"),
    mname: Core.data(this, "binder-mname"),
    lname: Core.data(this, "binder-lname"),
    contact: Core.data(this, "binder-contact"),
    birthday: Helper.toInputDate(Core.data(this, "binder-birthday")),
    address: Core.data(this, "binder-address"),
    gender: Core.data(this, "binder-gender"),
    civil_status: Core.data(this, "binder-civil_status"),
  }
  let layout = (await Core.fetch_data('./modal-edit_member.html', "text"));
  layout = Core.replaceLayout(layout, replace);
  Core.f(`${modal_editMember}-body`).innerHTML = layout;
  Core.f('#gender').value = replace.gender;
  Core.f('#civil_status').value = replace.civil_status;
}

async function submit_edit_listener() {
  const form_data = Core.createFormData({ uid: Core.user_getData().id }, new FormData(Core.f("#edit_member-form")));

  if (!Core.isValidForm(form_data, ["fname", "mname", "lname", "contact", "birthday", "address", "gender", "civil_status"])) {
    Helper.Promt_Error("Please fill required fields.");
    return;
  }

  if (Core.formValidator(form_data, ["birthday"], v => Helper.getAge(v) >= 0).length > 0) {
    Helper.Promt_Error("Invalid value. Please make sure values are valid.");
    return;
  }

  Helper.Promt_Clear();

  const res = (await Core.api('/evacuee_update', "json", form_data)).result[0];
  if (res.result) {
    CustomNotification.add("Success!", `Member updated!`, "primary");
    Core.f(`${modal_editMember}-hide`).click();
    Helper.Promt_Clear();
    if (rep_MODE) await Load_Representatives();
    else await Load_Members(selected_rep);
  } else {
    CustomNotification.add("Error", "An Error occurred. Try again later.", "danger");
    console.log({ res });
  }
}
// -- < /Edit > --


// -- < Delete > --
async function open_delete_listener() {
  const replace = {
    id: Core.data(this, "binder-id"),
    name: Core.data(this, "binder-name"),
  }
  let layout = (await Core.fetch_data('./modal-delete.html', "text"));
  layout = Core.replaceLayout(layout, replace);
  Core.f(`${modal_deleteMember}-body`).innerHTML = layout;
}

async function submit_delete_listener() {
  const form_data = Core.createFormData({ uid: Core.user_getData().id }, new FormData(Core.f("#delete-form")));


  const res = (await Core.api('/evacuee_delete', "json", form_data)).result[0];
  if (res.result) {
    CustomNotification.add("Success!", `New Member added!`, "primary");
    Core.f(`${modal_deleteMember}-hide`).click();
    Helper.Promt_Clear();
    if (rep_MODE) await Load_Representatives();
    else await Load_Members(selected_rep);
  } else {
    CustomNotification.add("Error", "An Error occurred. Try again later.", "danger");
    console.log({ res });
  }
}
// -- < /Delete > --



















let mainList = true;
async function Toggler(newmainList = undefined) {
  mainList = newmainList ?? mainList;

  if (mainList) {
    if (rep_MODE) {
      Core.f("#btn-toggle-table").innerHTML = "Show Representative List";
      await Load_ArchivedRep();
    } else {
      Core.f("#btn-toggle-table").innerHTML = "Show Member List";
      await Load_ArchivedMem();
    }
  } else {
    Core.f("#btn-toggle-table").innerHTML = "Show Archived";
    if (rep_MODE) await Load_Representatives();
    else await Load_Members(selected_rep);
  }
  mainList = !mainList;
}
Core.onClick("#btn-toggle-table", () => {
  Toggler();
});

async function Load_ArchivedRep() {
  const data = (await Core.api(`/representative_archived`, "json")).result;

  Helper.DataTable_Reset('#representative_list');

  const thead = `
    <thead>
      <tr>
        <th>ID</th>
        <th style="min-width: 225px;">Evacuee's Information</th>
        <th class="text-center">Action</th>
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
            <section class="col-sm-3 font-weight-bold" >Age</section>
            <section class="col-sm-9">${Helper.getAge(v.birthday)}</section>
          </div>
          <div class="row">
            <section class="col-sm-3 font-weight-bold" >Gender</section>
            <section class="col-sm-9">${Helper.getGender(v.gender)}</section>
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
      <td class="text-center">
        <button 
          class="btn btn-primary btn-sm btn-recover"
          data-binder-id="${v.id}"
          data-binder-name="${v.lname}, ${v.fname} ${v.mname}"
        >
        <i class="fas fa-plus"></i> Recover
        </button>
      </td>
    </tr>
  `);


  Helper.DataTable_Init('#representative_list', thead + tbody, async () => {
    Core.clearClick('.btn-recover', recoverMember, true);
    Core.onClick('.btn-recover', recoverMember, true);
  });
}

async function recoverMember() {
  const raw_data = { deletedflag: 0, id: Core.data(this, "binder-id") };
  const form_data = Core.createFormData({ ...raw_data, uid: Core.user_getData().id });
  const resp = (await Core.api('/evacuee_update', "json", form_data,));
  console.log({ resp })

  if (resp.result) {
    CustomNotification.add("Success!", `Item recovered!`, "primary");
    if (rep_MODE) await Load_ArchivedRep();
    else await Load_ArchivedMem();
  } else {
    CustomNotification.add("Error!", `Error occurred. Try again later.`, "danger");
    console.log({ resp })
  }
}

async function Load_ArchivedMem() {

  const data = (await Core.api(`/house_member_archived`, "json", Core.createFormData({ rep_id: selected_rep.id }))).result;

  Helper.DataTable_Reset('#representative_list');

  const thead = `
    <thead>
      <tr>
        <th>ID</th>
        <th style="min-width: 225px;">Evacuee's Information</th>
        <th class="text-center">Action</th>
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
            <section class="col-sm-3 font-weight-bold" >Age</section>
            <section class="col-sm-9">${Helper.getAge(v.birthday)}</section>
          </div>
          <div class="row">
            <section class="col-sm-3 font-weight-bold" >Gender</section>
            <section class="col-sm-9">${Helper.getGender(v.gender)}</section>
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
      <td class="text-center">
        <button 
          class="btn btn-primary btn-sm btn-recover"
          data-binder-id="${v.id}"
          data-binder-name="${v.lname}, ${v.fname} ${v.mname}"
        >
        <i class="fas fa-plus"></i> Recover
        </button>
      </td>
    </tr>
  `);


  Helper.DataTable_Init('#representative_list', thead + tbody, async () => {
    Core.clearClick('.btn-recover', recoverMember, true);
    Core.onClick('.btn-recover', recoverMember, true);
  });

}









(async () => {
  await Load_Representatives();

  set_repMode();
})();