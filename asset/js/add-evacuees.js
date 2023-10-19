$(".modal-close-btn").on("click", function(e){
    $("#modal-holder").addClass("hidden");
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
            break;
        }
        case "add-house" : {
            
            break;
        }
        case "edit-house" : {
            $(`#input-${id}-id`).val(body[0]);
            $(`#input-${id}-address`).val(body[1]);
            $(`#input-${id}-name`).val(body[2]);
            $(`#input-${id}-contact`).val(body[3]);
            break;
        }
        case "edit-house-member" : {

            break;
        }
        default : break;
    }

    $("#modal-holder").removeClass("hidden")
}

async function loadAllHouseMember(id){
    const form = new FormData();
    form.append("house_id", id);
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
            tbody += `<tr>`;
            tbody += `<td>${row.id}</td>`;
            tbody += `<td>
                <div><b>Name</b> ${row.name}</div>
                <div><b>Age</b> ${row.age}</div>
                <div><b>Gnder</b> ${row.gender}</div>
                <div><b>Contact</b> ${row.contact}</div>
            </td>`;
            tbody += `<td>${row.civil_status}</td>`;
            tbody += `<td>${row.representative  }</td>`;
            tbody += `<td>${row.address}</td>`;
            tbody += `<td class="d-flex justify-content-center" style="gap: .5rem;">
                <button class="btn btn-success" onclick="openModal('edit-house-member', ['${row.id}', '${row.address}', '${row.name}', '${row.contact}'])">Edit</button>
                <button data-member_id="${row.id}" class="btn btn-danger" onclick="deleteMember(this)">Delete</button>
            </td>`;
            tbody += `</tr>`;
        }

        $('#dataTable > thead').html(thead);
        $('#dataTable > tbody').html(tbody);
        $('#dataTable').DataTable();    
    });
}

function viewHouseMeber(e) {
    $("#btn-add-evacuee").addClass("hidden")
    $("#btn-add-house").addClass("hidden");
    $("#btn-cancel-view").removeClass("hidden");
    $("#table-title").text("Update House Information...");

    const id = $(e).data("house_id");
    loadAllHouseMember(id);

}

function updateHoseMember(e) {
    $("#btn-add-evacuee").removeClass("hidden")
    $("#btn-add-house").addClass("hidden");
    $("#btn-cancel-view").removeClass("hidden");
    $("#table-title").text("Update House Information...");

    const id = $(e).data("house_id");
    loadAllHouseMember(id);

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
            $('#dataTable > tbody').html("");
            $('#dataTable > thead').html("");
        }

        const result = response.result;

        let thead = `<tr>
            <th>ID</th>
            <th>ADDRESS</th>
            <th>REPRESENTATIVE</th>
            <th>CONTACT</th>
            <th class="text-center">ACTION</th>
        </tr>`;
        
        let tbody = '';
        for(let row of result) {
            tbody += '<tr>';
            tbody += `<td><a onclick="viewHouseMeber(this)" href="#" class="link-underline-opacity-0">${row.id}</a></td>`;
            tbody += `<td>${row.address}</td>`;
            tbody += `<td>${row.name}</td>`;
            tbody += `<td>${row.contact}</td>`;
            tbody += `<td class="d-flex justify-content-center" style="gap: .5rem;">
                <button class="btn btn-success" onclick="openModal('edit-house', ['${row.id}', '${row.address}', '${row.name}', '${row.contact}'])">Edit</button>
                <button onclick="updateHoseMember(this)" class="btn btn-warning">Update</button>
                <button data-house_id="${row.id}" class="btn btn-danger" onclick="deleteHouse(this)">Delete</button>
            </td>`;
            tbody += '</tr>';
        }

        $('#dataTable > thead').html(thead);
        $('#dataTable > tbody').html(tbody);
        $('#dataTable').DataTable();
    });
}

function deleteHouse(e) {
    const house_id = $(e).data("house_id");
    show_alert({
        title : `<img src="${BASE_URL}asset/img/sent.png" > Delete record`,
        body : `Are you sure you want to delete <b>${house_id}</b> data?`,
        buttons : ["Yes", "No"]
    }, function(ans) {
        console.log(ans)
    });
}

function deleteMember(e) {
    const house_id = $(e).data("member_id");
    show_alert({
        title : `<img src="${BASE_URL}asset/img/sent.png" > Delete record`,
        body : `Are you sure you want to delete <b>${house_id}</b> data?`,
        buttons : ["Yes", "No"]
    }, function(ans) {
        console.log(ans)
    });
}