
function updateDate(body = ["-1", ""]) {
  show_alert({
    title: "Update Incident",
    body: `<div class="d-flex flex-column" style="gap: .5rem;">
           <div class="input-group">
              <span class="input-group-text" id="incident-name">Name</span>
              <input id="update-incident-name" value="${body[1]}" type="text" class="form-control" placeholder="Incident name" aria-describedby="incident-name">
           </div>
        </div>`,
    buttons: ["Save", "Cancel"]
  }, function (ans) {
    if (!ans) return;

    const form = new FormData();
    form.append("uid", USER_DATA.id);
    form.append("id", body[0]);
    form.append("name", $("#update-incident-name").val());

    if (!validForm(form)) return;

    fetch(`${BASE_URL}php/incident_update.php`, {
      method: 'post',
      body: form
    })
      .then(response => response.json())
      .then(response => {
        load_all_incident();
        addNotif("Incident Updated", "Incident updated successfully!", "g");
      })
      .catch(err => console.error("ERROR: " + err));
  });
}

function delete_data(e) {
  const name = String($(e).data("name"));
  const id = String($(e).data("id"));
  show_alert({
    title: `<img src="${BASE_URL}asset/img/sent.png" > Delete record`,
    body: `Are you sure you want to delete <b>${name}</b>?`,
    buttons: ["Yes", "No"]
  }, function (ans) {
    if (!ans) return;

    const form = new FormData();
    form.append("uid", USER_DATA.id);
    form.append("id", id);
    fetch(`${BASE_URL}php/incident_delete.php`, {
      method: 'post',
      body: form
    })
      .then(response => response.json())
      .then(response => {
        load_all_incident();
        addNotif("Incident Deleted", "Incident deleted successfully!", "g");
      })
      .catch(err => console.error("ERROR: " + err));
  });
}

async function load_all_incident() {
  await fetch(BASE_URL + 'php/incident_get_all.php')
    .then(response => response.json())
    .then(response => {
      let result = response.result;

      if ($.fn.DataTable.isDataTable('#incident')) {
        $('#incident').DataTable().destroy();
        $('#incident').html("");
      }

      const thead = `
     <thead>
        <tr>
           <th>Incident Name</th>
           <th class="text-center" style="width: 160px !important;">Action</th>
        </tr>
     </thead>
     `;

      let tbody = '<tbody>';
      for (let row of result) {
        tbody += `<tr>`;
        tbody += `<td>${row.name}</td>`;
        tbody += `<td class="text-center" style="width: 160px !important;">
              <button class="btn btn-sm btn-success" href="#" onclick="updateDate([
                 '${row.id}', '${row.name}'
              ])"><i class="fa fa-edit"></i> Edit</button>`;
        tbody += (USER_DATA.category === "A") ? `
              <button class="btn btn-sm btn-danger" href="#" onclick="delete_data(this)" data-id="${row.id}" data-name="${row.name}">
                 <i class="fa fa-trash-alt"></i> Delete
              </button>
           </td>` : "";
        tbody += `</tr>`;
      }
      tbody += "</tbody>";

      $("#incident").append(thead + tbody);
      $("#incident").DataTable({
        bAutoWidth: false,
        autoWidth: false
      });
    })
    .catch(err => {
      console.error("ERROR: " + err);
    });
}

document.getElementById("incident-add-btn").addEventListener("click", async function (e) {
  e.preventDefault();
  const form = new FormData(document.getElementById("incident-add-form"));
  form.append("uid", USER_DATA.id);

  if (!validForm(form)) return;

  await fetch(BASE_URL + 'php/incident_new.php', {
    method: 'post',
    body: form
  })
    .then(response => response.json())
    .then(response => {
      let result = response.result[0];
      if (!result.result) alert("Something went wrong. Please try again later.");
      else document.getElementById("incident-add-form").reset();

      load_all_incident();
      addNotif("New Incident", "New incident added successfully!", "g");
    })
    .catch(err => {
      console.error("ERROR: " + err);
    });
});

load_all_incident();