import { Core, CustomNotification } from "../../core/core.js";

Core.user_redirectToLogin();

CustomNotification.add("BBEIMS", `Welcome <b>${Core.user_getData().username}</b>`, "default");

(async () => {
  const data = (await Core.fetch_data(`${Core.base_url()}/php/dashboard_get.php`, "json")).result[0];

  Core.f("#number-of-family").innerHTML = data.family;
  Core.f("#number-of-evacuee").innerHTML = data.evacuees;
  Core.f("#number-of-female").innerHTML = data.female;
  Core.f("#number-of-male").innerHTML = data.male;
  Core.f("#number-of-brgy-population").innerHTML = data.brgy;
  Core.f("#number-of-evac_center").innerHTML = data.evac_center;
})();

Core.f("#btn-gegerateReport").addEventListener("click", async (e) => {
  e.preventDefault();

  console.log("Hello")
})


async function generateReport() {
  let incidentTypeOptions = '';
  let incidentDateOptions = '';
  let evacCenterOptions = '';

  let layout = await fetch_data("asset/templates/generateReport.html");

  const incidentsTypes = (await fetch_data("php/incident_get_all.php", "json")).result;
  // const incidentsDates = (await fetch_data("php/incident_get_dates.php", "json")).result;
  const evacCenter = (await fetch_data("php/evac_center_get_all.php", "json")).result;

  incidentsTypes.forEach(e => { incidentTypeOptions += `<option value="${e.id}" >${e.name}</option>`; });
  // incidentsDates.forEach(e => { incidentDateOptions += `<option value="${e.id}" >${e.name}</option>`; });
  evacCenter.forEach(e => { evacCenterOptions += `<option value="${e.id}" >${e.name}</option>`; });

  layout = layout.replaceAll("{select-incident-type}", incidentTypeOptions);
  layout = layout.replaceAll("{select-incident-date}", incidentDateOptions);
  layout = layout.replaceAll("{select-evac-center}", evacCenterOptions);

  show_alert({
    title: "Generate Report",
    body: layout,
    buttons: ["Generate", "Cancel"]
  }, async function (ans) {
    if (!ans) return;

    const form = new FormData(document.getElementById("report-form"));
    await generateReportBy(form);

    // addNotif("Generating report", "Report is being generated!", "g");
  });

  $(".report-row").on("click", function () {
    let radio = $(this).find("input[type='radio']").prop("checked", true);
  });
}

async function generateReportBy(form) {
  const type = form.get("type");
  if (type === "incident-type") { reportBy(form, "type"); return; }
  if (type === "incident-date") { reportBy(form, "date"); return; }
  if (type === "evac-center") { reportBy(form, "center"); return; }
  reportBy(form);
}

async function reportBy(form, condition = "") {
  form.append("uid", USER_DATA.id);
  form.append("condition", condition);

  const thead = `<thead><tr>
         <th>Fullname</th>
         <th>Contact</th>
         <th>Age</th>
         <th>gender</th>
         <th>Representative</th>
         <th>Center</th>
         <th>Incident</th>
         <th>Date</th>
      </tr></thead>`;
  let tbody = '<tbody>';

  const response = (await Core.fetch_data(
    'php/generate_report_all.php',
    "json",
    Core.createFormData({ uid: Core.user_getData().id, condition: condition }))
  ).result;
  for (const data of response) {
    tbody += `<tr>
            <td>${data.lname}, ${data.fname} ${data.mname}</td>
            <td>${data.contact}</td>
            <td>${getAge(data.birthday)}</td>
            <td>${data.gender}</td>
            <td>${data.repLname}, ${data.repFname} ${data.repMname}</td>
            <td>${data.center}</td>
            <td>${data.incident}</td>
            <td>${data.date}</td>
         </tr>`;
  }
  tbody += `</tbody>`;
  const table = `<table class="table mt-5">${thead}${tbody}</table>`;

  let s = `
         <javascript src="${BASE_URL}asset/jquery/jquery.min.js"></javascript>
         <javascript src="${BASE_URL}asset/js/adminlte.js"></javascript>
         <javascript> window.print(); window.close(); </javascript>
         `;

  const reportTab = window.open('about:blank', '_blank');
  reportTab.document.write(
    `<head> <link rel="stylesheet" href="${BASE_URL}asset/css/adminlte.min.css"> </head>`
    + `<div class="d-flex justify-content-center">` + table + `</div>` +
    s.replaceAll("javascript", "script")
  );
}  
