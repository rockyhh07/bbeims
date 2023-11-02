$("#modal-footer-add-evacuee").on("click", async function(e){
    const form = new FormData(document.getElementById("modal-body-add-evacuee"));
    form.append("uid", USER_DATA.id);

    await fetch(`${BASE_URL}php/.php`,{
        method : 'post',
        body : form
    })
    .then(response => response.json())
    .then(response => {
        const result = response.result;
        console.log({result});

    })
    .catch(err => console.log("ERROR: " + err));

    console.log("add evacluee", {form});
});

$("#modal-footer-add-house").on("click", async function(e){
    const form = new FormData(document.getElementById("modal-body-add-house"));
    form.append("uid", USER_DATA.id);

    await fetch(`${BASE_URL}php/.php`,{
        method : 'post',
        body : form
    })
    .then(response => response.json())
    .then(response => {
        const result = response.result;
        console.log({result});

    })
    .catch(err => console.log("ERROR: " + err));

    console.log("add representative", {form});
});

$("#modal-footer-edit-house").on("click", async function(e){
    const form = new FormData(document.getElementById("modal-body-edit-house"));
    form.append("uid", USER_DATA.id);

    await fetch(`${BASE_URL}php/evacuee_update.php`,{
        method : 'post',
        body : form
    })
    .then(response => response.json())
    .then(response => {
        const result = response.result;
        console.log({result});

    })
    .catch(err => console.log("ERROR: " + err));

    console.log("edit representative", {form});
});

$("#modal-footer-edit-house-member").on("click", async function(e){
    const form = new FormData(document.getElementById("modal-body-edit-house-member"));
    form.append("uid", USER_DATA.id);

    await fetch(`${BASE_URL}php/evacuee_update.php`,{
        method : 'post',
        body : form
    })
    .then(response => response.json())
    .then(response => {
        const result = response.result;
        console.log({result});

    })
    .catch(err => console.log("ERROR: " + err));

    console.log("edit house member", {form});
});



$(".modal-close-btn").on("click", function(e){
    $("#modal-holder").addClass("hidden");

    SELECTED_REP_ID = undefined;
    SELECTED_REP_NAME = undefined;
});

function openModal(id, body = []) {

    $("#modal-header").children().addClass("hidden");
    $("#modal-body").children().addClass("hidden");
    $("#modal-footer").children().addClass("hidden");

    $("#modal-close-btn").removeClass("hidden");

    $(`#modal-header-${id}`).removeClass("hidden");
    $(`#modal-body-${id}`).removeClass("hidden");
    $(`#modal-footer-${id}`).removeClass("hidden");

    switch(id) {
        case "add-evacuee" : {
            
        } break;
        case "add-house" : {
            
        } break;
        case "edit-house" : {
            $(`#modal-body-${id} input[name='vid']`).val(body[0]);
            $(`#modal-body-${id} input[name='address']`).val(body[1]);
            $(`#modal-body-${id} input[name='lname']`).val(body[2][0]);
            $(`#modal-body-${id} input[name='fname']`).val(body[2][1]);
            $(`#modal-body-${id} input[name='mname']`).val(body[2][2]);
            $(`#modal-body-${id} input[name='contact']`).val(body[3]);
            $(`#modal-body-${id} input[name='id']`).val(body[body.length - 1]);
        } break;
        case "edit-house-member" : {
            $(`#modal-body-${id} input[name='vid']`).val(body[0]);
            $(`#modal-body-${id} input[name='representative']`).val(body[1]);
            $(`#modal-body-${id} input[name='lname']`).val(body[2][0]);
            $(`#modal-body-${id} input[name='fname']`).val(body[2][1]);
            $(`#modal-body-${id} input[name='mname']`).val(body[2][2]);
            $(`#modal-body-${id} input[name='age']`).val(body[3]);
            $(`#modal-body-${id} input[name='contact']`).val(body[4]);
            $(`#modal-body-${id} input[name='gender']`).val(body[5]);
            $(`#modal-body-${id} input[name='civil_status']`).val(body[6]);
            $(`#modal-body-${id} input[name='id']`).val(body[body.length - 1]);
        } break;
        default : break;
    }

    $("#modal-holder").removeClass("hidden")
}


async function loadIncident() {
    const incident = $("#select-incident");
    await fetch(BASE_URL + `php/incident_get_all.php`)
    .then(response => response.json())
    .then(response => {
        const result = response.result;
        let options = ''
        for(let obj of result){
            options += `<option value="${obj.id}">${obj.name}</option>`
        }
        incident.append(options);
    })
    .catch(err => console.error("ERROR: " + err));
    
}

async function loadEvacuationCenter() {
    const evacCenter = $("#select-evac_center");
    await fetch(BASE_URL + `php/evac_center_get_all.php`)
    .then(response => response.json())
    .then(response => {
        const result = response.result;
        let options = ''
        for(let obj of result){
            options += `<option value="${obj.id}">${obj.name}</option>`
        }
        evacCenter.append(options);
    })
    .catch(err => console.error("ERROR: " + err));

}


async function viewHouseMeber(e) {
    await loadIncident();
    await loadEvacuationCenter();

    $("#btn-add-evacuee").addClass("hidden")
    $("#btn-add-house").addClass("hidden");
    $("#btn-cancel-view").removeClass("hidden");
    $("#add-incident-form").removeClass("hidden");
    $("#table-title").text("Incident report");

    const id = $(e).data("rep_id");
    loadAllHouseMember(id, "calamaityReport");

}

function updateHoseMember(e) {
    $("#btn-add-evacuee").removeClass("hidden")
    $("#btn-add-house").addClass("hidden");
    $("#btn-cancel-view").removeClass("hidden");
    $("#add-incident-form").addClass("hidden");
    $("#table-title").text("House Information");

    const id = $(e).data("rep_id");
    loadAllHouseMember(id, "houseInformation");

}

async function loadAllHouseMember(id, table){
    const form = new FormData();
    form.append("rep_id", id);
    await fetch(BASE_URL + "php/house_member_get.php", {
        method : 'post',
        body : form
    })
    .then(response => response.json())
    .then(response => {
        if ($.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').DataTable().destroy();
            $('#dataTable > tbody').html("");
            $('#dataTable > thead').html("");
        }

        const result = response.result;

        let thead = `<tr>
            <th>ID</th>
            <th>EVACUEE'S INFORMATION</th>
            <th>CIVIL STATUS</th>
            <th>REPRESENTATIVE</th>
            <th>ADDRESS</th>`;
        thead += (table === "calamaityReport") ? "" : `
            <th class="text-center">ACTION</th>
        </tr>`;
        
        let tbody = '';
        for(let row of result) {
            let name = `${row.lname}, ${row.fname} ${row.mname}`;

            tbody += `<tr>`;
            tbody += `<td style="white-space: nowrap;" class="member-list">${"M-"+row.id.padStart(6, "0")}</td>`;
            tbody += `<td>
                <div><b>Name:</b> ${name}</div>
                <div><b>Age:</b> ${row.age}</div>
                <div><b>Gnder:</b> ${row.gender}</div>
                <div><b>Contact:</b> ${row.contact}</div>
            </td>`;
            tbody += `<td>${row.civil_status}</td>`;
            tbody += `<td>${row.representative}</td>`;
            tbody += `<td>${row.address}</td>`;
            tbody += (table === "calamaityReport") ? "" : `<td class="d-flex justify-content-center" style="gap: .5rem;">
                <button class="btn btn-success" onclick="openModal('edit-house-member', 
                    [
                        '${"M-"+row.id.padStart(6, "0")}',
                        '${row.representative}',
                        ['${row.lname}','${row.fname}','${row.mname}'],
                        '${row.age}',
                        '${row.contact}',
                        '${row.gender}',
                        '${row.civil_status}', 
                        '${row.id}'
                    ])
                ">Edit</button>
                <button data-member_id="${row.id}" class="btn btn-danger" onclick="deleteMember(this)">Delete</button>
            </td>`;
            tbody += `</tr>`;
        }

        $('#dataTable > thead').html(thead);
        $('#dataTable > tbody').html(tbody);
        $('#dataTable').DataTable();    
    });
}

async function loadAllRepresentative() {
    $("#btn-add-evacuee").addClass("hidden")
    $("#btn-cancel-view").addClass("hidden");
    $("#btn-add-house").removeClass("hidden");
    $("#add-incident-form").addClass("hidden");
    $("#table-title").text("Representatives");

    await fetch(BASE_URL + "php/representative_get_all.php")
    .then(response => response.json())
    .then(response => {

        if ($.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').DataTable().destroy();
            $('#dataTable').html("");
        }

        const result = response.result;

        let thead = `<thead><tr>
            <th>ID</th>
            <th>ADDRESS</th>
            <th>REPRESENTATIVE</th>
            <th>CONTACT</th>
            <th class="text-center">ACTION</th>
        </tr></thead>`;
        
        let tbody = '<tbody>';
        for(let row of result) {
            let name = `${row.lname}, ${row.fname} ${row.mname}`;

            tbody += '<tr>';
            tbody += `<td><a onclick="viewHouseMeber(this)" data-rep_id="${row.id}" href="#" class="link-underline-opacity-0" style="white-space: nowrap;">${"H-"+row.id.padStart(6, "0")}</a></td>`;
            tbody += `<td>${row.address}</td>`;
            tbody += `<td>${name}</td>`;
            tbody += `<td>${row.contact}</td>`;
            tbody += `<td class="d-flex justify-content-center" style="gap: .5rem;">
            <button class="btn btn-success" onclick="openModal('edit-house', 
                [
                    '${"H-"+row.id.padStart(6, "0")}', 
                    '${row.address}', 
                    ['${row.lname}', '${row.fname}', '${row.mname}'], 
                    '${row.contact}', 
                    '${row.id}'
                ])
            ">Edit</button>
            <button data-rep_id="${row.id}" onclick="updateHoseMember(this)" class="btn btn-warning">Update</button>
            <button data-rep_id="${row.id}" class="btn btn-danger" onclick="deleteHouse(this)">Delete</button>
            </td>`;
            tbody += '</tr>';
        }
        tbody += '</tbody>';

        $('#dataTable').append(thead, tbody);
        $('#dataTable').DataTable();
    });
}

function deleteHouse(e) {
    const rep_id = String($(e).data("rep_id"));
    show_alert({
        title : `<img src="${BASE_URL}asset/img/sent.png" > Delete record`,
        body : `Are you sure you want to delete <b>${"H-"+rep_id.padStart(6, "0")}</b> data?`,
        buttons : ["Yes", "No"]
    }, function(ans) {
        console.log(ans)
    });
}

function deleteMember(e) {
    const rep_id = String($(e).data("member_id"));
    show_alert({
        title : `<img src="${BASE_URL}asset/img/sent.png" > Delete record`,
        body : `Are you sure you want to delete <b>${"M-"+rep_id.padStart(6, "0")}</b> data?`,
        buttons : ["Yes", "No"]
    }, function(ans) {
        console.log(ans)
    });
}

$("#add-incident-form").on("submit", async function(e){
    e.preventDefault();
    
    show_alert({
        title : "Report incident",
        body : "Report incident record for this family?",
        buttons : ["Yes", "No"]
    }, async function(ans){
        if(!ans) return;

        const form = new FormData(document.getElementById("add-incident-form"));
        form.append("uid", USER_DATA.id);
        let member_list = [];
        $(".member-list").each(function(){ 
            let mem_id = String($(this).html());
            mem_id = mem_id.replace('M-', '');
            mem_id = Number(mem_id);
            member_list.push(mem_id);
        });
        form.append("ids", JSON.stringify(member_list));
        
        await fetch(BASE_URL + 'php/evacuee_update.php', {
            method : 'post',
            body : form
        })
        .then(response => response.json())
        .then(response => {
            const result = response.result;
            console.log({result});
            window.location.href = `${BASE_URL}admin/manage-evacuees.html`;
        })
        .catch(err => console.error(err));
    
    });

});


loadAllRepresentative();