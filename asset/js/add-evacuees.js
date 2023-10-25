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
            $(`#modal-body-${id} input[name='id']`).val(body[0]);
            $(`#modal-body-${id} input[name='address']`).val(body[1]);
            $(`#modal-body-${id} input[name='lname']`).val(body[2][0]);
            $(`#modal-body-${id} input[name='fname']`).val(body[2][1]);
            $(`#modal-body-${id} input[name='mname']`).val(body[2][2]);
            $(`#modal-body-${id} input[name='contact']`).val(body[3]);
        } break;
        case "edit-house-member" : {
            $(`#modal-body-${id} input[name='id']`).val(body[0]);
            $(`#modal-body-${id} input[name='representative']`).val(body[1]);
            $(`#modal-body-${id} input[name='lname']`).val(body[2][0]);
            $(`#modal-body-${id} input[name='fname']`).val(body[2][1]);
            $(`#modal-body-${id} input[name='mname']`).val(body[2][2]);
            $(`#modal-body-${id} input[name='age']`).val(body[3]);
            $(`#modal-body-${id} input[name='contact']`).val(body[4]);
            $(`#modal-body-${id} input[name='gender']`).val(body[5]);
            $(`#modal-body-${id} input[name='civil_status']`).val(body[6]);
        } break;
        default : break;
    }

    $("#modal-holder").removeClass("hidden")
}



function viewHouseMeber(e) {
    $("#btn-add-evacuee").addClass("hidden")
    $("#btn-add-house").addClass("hidden");
    $("#btn-cancel-view").removeClass("hidden");
    $("#table-title").text("Update House Information...");

    const id = $(e).data("rep_id");
    loadAllHouseMember(id);

}

function updateHoseMember(e) {
    $("#btn-add-evacuee").removeClass("hidden")
    $("#btn-add-house").addClass("hidden");
    $("#btn-cancel-view").removeClass("hidden");
    $("#table-title").text("Update House Information...");

    const id = $(e).data("rep_id");
    loadAllHouseMember(id);

}

async function loadAllHouseMember(id){
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
            <th>ADDRESS</th>
            <th class="text-center">ACTION</th>
        </tr>`;
        
        let tbody = '';
        for(let row of result) {
            let name = `${row.lname}, ${row.fname} ${row.mname}`;

            tbody += `<tr>`;
            tbody += `<td style="white-space: nowrap;">${"M-"+row.id.padStart(6, "0")}</td>`;
            tbody += `<td>
                <div><b>Name:</b> ${name}</div>
                <div><b>Age:</b> ${row.age}</div>
                <div><b>Gnder:</b> ${row.gender}</div>
                <div><b>Contact:</b> ${row.contact}</div>
            </td>`;
            tbody += `<td>${row.civil_status}</td>`;
            tbody += `<td>${row.representative}</td>`;
            tbody += `<td>${row.address}</td>`;
            tbody += `<td class="d-flex justify-content-center" style="gap: .5rem;">
                <button class="btn btn-success" onclick="openModal('edit-house-member', [
                    '${"M-"+row.id.padStart(6, "0")}',
                    '${row.representative}',
                    ['${row.lname}','${row.fname}','${row.mname}'],
                    '${row.age}',
                    '${row.contact}',
                    '${row.gender}',
                    '${row.civil_status}'
                ])">Edit</button>
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
    $("#table-title").text("Overall Population");

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
            <button class="btn btn-success" onclick="openModal('edit-house', ['${"H-"+row.id.padStart(6, "0")}', '${row.address}', ['${row.lname}', '${row.fname}', '${row.mname}'], '${row.contact}'])">Edit</button>
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


loadAllRepresentative();