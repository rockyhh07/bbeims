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

function viewHouseMeber(id) {

}

function updateHoseMember(id) {

}

async function loadAllRepresentative() {
    await fetch(BASE_URL + "php/representative_get_all.php")
    .then(response => response.json())
    .then(response => {

        if ($.fn.DataTable.isDataTable('#dataTable')) {
            $('#dataTable').DataTable().destroy();
            $('#dataTable > tbody').html("");
        }

        const result = response.result;
        console.log(result);
        
        let tbody = '';
        for(let row of result) {
            tbody += '<tr>';
            tbody += `<td><a href="#" class="link-underline-opacity-0">${row.id}</a></td>`;
            tbody += `<td>${row.address}</td>`;
            tbody += `<td>${row.name}</td>`;
            tbody += `<td>${row.contact}</td>`;
            tbody += `<td class="d-flex justify-content-center" style="gap: .5rem;">
                <button class="btn btn-success" onclick="openModal('edit-house', ['${row.id}', '${row.address}', '${row.name}', '${row.contact}'])">Edit</button>
                <button class="btn btn-warning">Update</button>
                <button class="btn btn-danger">Delete</button>
            </td>`;
            tbody += '</tr>';
        }

        $('#dataTable > tbody').html(tbody);
        $('#dataTable').DataTable();
    });
}

function deleteHouse(e) {
    show_alert({
        title : "",
        body : "",
        buttons : ["Okay", "Cancel"]
    }, function(ans) {

    });
}