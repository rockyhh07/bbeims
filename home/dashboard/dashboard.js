import { Core, CustomNotification, Helper } from "../../core/core.js";

const generateModal = "#generate-report-modal";

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

  let incidentTypeOptions = '';
  let incidentDateOptions = '';
  let evacCenterOptions = '';

  let layout = await Core.fetch_data("generateReport.html");

  // const incidentsDates = (await fetch_data("php/incident_get_dates.php", "json")).result;
  const incidentsTypes = (await Core.fetch_data(Core.base_url() + "/php/incident_get_all.php", "json")).result;
  const evacCenter = (await Core.fetch_data(Core.base_url() + "/php/evac_center_get_all.php", "json")).result;

  incidentsTypes.forEach(e => { incidentTypeOptions += `<option value="${e.id}" >${e.name}</option>`; });
  // incidentsDates.forEach(e => { incidentDateOptions += `<option value="${e.id}" >${e.name}</option>`; });
  evacCenter.forEach(e => { evacCenterOptions += `<option value="${e.id}" >${e.name}</option>`; });

  layout = layout.replaceAll("{select-incident-type}", incidentTypeOptions);
  layout = layout.replaceAll("{select-incident-date}", incidentDateOptions);
  layout = layout.replaceAll("{select-evac-center}", evacCenterOptions);

  Core.f(`${generateModal}-body`).innerHTML = layout;

  $(".report-row").on("click", function () {
    $(this).find("input[type='radio']").prop("checked", true);
  });
});

Core.f(`${generateModal}-submit`).addEventListener("click", async () => {
  Core.f(`${generateModal}-hide`).click();
  await generateReportBy(Core.createFormData({}, new FormData(Core.f("#report-form"))));
  CustomNotification.add("Generating report", "Report is being generated!", "success");
});


// ******************
// *  Semi Private  *
// ******************

async function generateReportBy(form) {
  const type = form.get("type");
  if (type === "incident-type") { reportBy(form, "type"); return; }
  if (type === "incident-date") { reportBy(form, "date"); return; }
  if (type === "evac-center") { reportBy(form, "center"); return; }
  reportBy(form);
}

async function reportBy(form, condition = "") {

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
    Core.base_url() + '/php/generate_report_all.php',
    "json",
    Core.createFormData({ uid: Core.user_getData().id, condition: condition }, form))
  ).result;
  for (const data of response) {
    tbody += `<tr>
            <td>${data.lname}, ${data.fname} ${data.mname}</td>
            <td>${data.contact}</td>
            <td>${Helper.getAge(data.birthday)}</td>
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
         <javascript src="${Core.base_url()}/assets/jquery/jquery.min.js"></javascript>
         <javascript src="${Core.base_url()}/assets/js/adminlte.js"></javascript>
         <javascript> (()=>{ setTimeout(() => { window.print(); }, 1000); })(); </javascript>
         `;

  const reportTab = window.open('', '_blank', 'width=500,height=400');
  reportTab.document.write(
    `<head> <link rel="stylesheet" href="${Core.base_url()}/assets/css/adminlte.min.css"> </head>`
    + `<div class="d-flex justify-content-center">` + table + `</div>` +
    s.replaceAll("javascript", "script")
  );
}  
