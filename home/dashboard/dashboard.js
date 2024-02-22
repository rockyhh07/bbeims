import { Core } from "../../core/core.js";
import { CustomNotification } from "../../core/customNotification.js";
import { Helper } from "../../core/helper.js";
import { ModalHandler } from "../../core/modalHandler.js";

const generateModal = "#generate-report-modal";
CustomNotification.add("BBEIMS", `Welcome <b>${Core.user_getData().username}</b>`, "primary");

(async () => {
  const data = (await Core.fetch_data(`${Core.base_url()}/php/dashboard_get.php`, "json")).result[0];

  Core.f("#number-of-family").innerHTML = data.family;
  Core.f("#number-of-evacuee").innerHTML = data.evacuees;
  Core.f("#number-of-female").innerHTML = data.female;
  Core.f("#number-of-male").innerHTML = data.male;
  Core.f("#number-of-brgy-population").innerHTML = data.brgy;
  Core.f("#number-of-evac_center").innerHTML = data.evac_center;

  await Load_AgeGraph();
  await Load_GenderGraph();
  await Load_IncidentGraph();
  await Load_CenterGraph();
  await Load_EvacueesGraph();

})();


// --< Generate Report >--
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

Core.f(`${generateModal}-btn-generate`).addEventListener("click", async () => {
  Core.f(`${generateModal}-hide`).click();
  await generateReportBy(Core.createFormData({}, new FormData(Core.f("#report-form"))));
  CustomNotification.add("Generating report", "Report is being generated!", "success");
});

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
// --</ Generate Report >--


// --< Age Graph >--
async function Load_AgeGraph() {
  const report = (await Core.fetch_data(`${Core.base_url()}/php/report_by_age.php`, "json")).result;
  if (!report) { console.error("Error"); return; }

  let age_data = {
    "00-05": 0,
    "06-10": 0,
    "11-20": 0,
    "21-30": 0,
    "31-40": 0,
    "41-50": 0,
    "51-60": 0,
    "60 up": 0,
  };

  for (let row of report) {
    let age = Helper.getAge(row.birthday);
    if (age >= 0 && age <= 5) age_data["00-05"]++;
    if (age >= 6 && age <= 10) age_data["06-10"]++;
    if (age >= 11 && age <= 20) age_data["11-20"]++;
    if (age >= 21 && age <= 30) age_data["21-30"]++;
    if (age >= 31 && age <= 40) age_data["31-40"]++;
    if (age >= 41 && age <= 50) age_data["41-50"]++;
    if (age >= 51 && age <= 60) age_data["51-60"]++;
    if (age >= 60) age_data["60 up"]++;
  }

  load_chart([
    age_data["00-05"],
    age_data["06-10"],
    age_data["11-20"],
    age_data["21-30"],
    age_data["31-40"],
    age_data["41-50"],
    age_data["51-60"],
    age_data["60 up"]
  ]);

  const keys = Object.keys(age_data);
  for (let id in keys) document.getElementById(keys[id]).innerText = age_data[keys[id]];
}

function load_chart(age_data = undefined) {
  const barChartData = {
    labels: ["0 to 5", "6 to 10", "11 to 20", "21 to 30", "31 to 40", "41 to 50", "51 to 60", "60 up"],
    datasets: [{
      label: 'Evacuees',
      backgroundColor: 'rgb(79,129,189)',
      borderColor: 'rgba(0, 158, 251, 1)',
      borderWidth: 1,
      data: (age_data !== undefined) ? age_data : [0, 0, 0, 0, 0, 0, 0, 0]
    }]
  };

  const ctx = document.getElementById('age-graph').getContext('2d');
  window.myBar = new Chart(ctx, {
    type: 'bar',
    data: barChartData,
    options: {
      responsive: true,
      legend: {
        display: false,
      }
    }
  });
}
// --</ Age Graph >--


// --< Gender Graph >--
async function Load_GenderGraph() {
  const report = (await Core.fetch_data(`${Core.base_url()}/php/report_by_gender.php`, "json")).result[0];
  if (!report) { console.error("Error"); return; }

  load_chart([report.male, report.female]);

  Core.f("#gender-graph-male").innerHTML = report.male;
  Core.f("#gender-graph-female").innerHTML = report.female;

  function load_chart(gender_data = undefined) {
    // Pie chart
    new Chart(document.getElementById("gender-graph"), {
      type: "pie",
      data: {
        labels: ["Male", "Female"],
        datasets: [{
          data: (gender_data !== undefined) ? gender_data : [45, 55],
          backgroundColor: [
            window.theme.primary,
            window.theme.danger,
          ],
          borderColor: "transparent"
        }]
      },
      options: {
        maintainAspectRatio: true,
        legend: {
          display: true,
        }
      }
    });

  };
}
// --</ Gender Graph >--

// --< Incident Graph >--
async function Load_IncidentGraph() {

  const raw_report = (await Core.fetch_data(`${Core.base_url()}/php/report_by_incident.php`, "json")).result[0];
  if (!raw_report) { console.error("Error"); return; }

  const report = Helper.ObjectToArray(raw_report);
  let tbody = '';
  report.forEach(v => tbody += `<tr><td>${v.name}</td><td class="text-${Number(v.value) > 0 ? 'danger' : 'success'}">${v.value}</td></tr>`);
  Core.f("#incident-graph-table").innerHTML += tbody;

  load_chart(report.map(v => v.value));

  function load_chart(graph_data = undefined) {
    const barChartData = {
      labels: report ? report.map(v => v.name) : [],
      datasets: [{
        label: 'Evacuees',
        backgroundColor: '#FF7C89',
        borderColor: '#DC3545',
        borderWidth: 1,
        data: graph_data ?? [],
      }]
    };

    const ctx = document.getElementById('incident-graph').getContext('2d');
    window.myBar = new Chart(ctx, {
      type: 'bar',
      data: barChartData,
      options: {
        responsive: true,
        legend: {
          display: false,
        }
      }
    });
  }
}
// --</ Incident Graph >--


// --< Center Graph >--
async function Load_CenterGraph() {

  const raw_report = (await Core.fetch_data(`${Core.base_url()}/php/report_by_center.php`, "json")).result[0];
  if (!raw_report) { console.error("Error"); return; }

  const report = Helper.ObjectToArray(raw_report);
  let tbody = '';
  report.forEach(v => tbody += `
  <tr>
    <td><small>${v.name}</small></td>
    <td class="text-${Number(v.value) > 0 ? 'danger' : 'success'}">${v.value}</td>
  </tr>`);
  Core.f("#center-graph-table").innerHTML += tbody;

  load_chart(report.map(v => v.value));

  function load_chart(graph_data = undefined) {
    const barChartData = {
      labels: report ? report.map(v => v.name) : [],
      datasets: [{
        label: 'Evacuees',
        backgroundColor: '#FF7C89',
        borderColor: '#DC3545',
        borderWidth: 1,
        data: graph_data ?? [],
      }]
    };

    const ctx = document.getElementById('center-graph').getContext('2d');
    window.myBar = new Chart(ctx, {
      type: 'bar',
      data: barChartData,
      options: {
        responsive: true,
        legend: {
          display: false,
        }
      }
    });
  }

}
// --</ Center Graph >--


// --< Evacuees Graph >--
async function Load_EvacueesGraph() {
  const report = (await Core.fetch_data(`${Core.base_url()}/php/report_by_evacuee.php`, "json")).result;
  if (!report) { console.error("Error"); return; }

  Helper.DataTable_Reset('#evacuee-graph-table');

  const thead = `
  <thead>
    <tr>
      <th class="text-center">#</th>
      <th>Name</th>
      <th>Address</th>
      <th>Age</th>
    </tr>
  </thead>`;

  let tbody = '';
  report.forEach((v, i) => tbody += `
    <tr>
      <td class="text-center">${i + 1}</td>
      <td>${v.lname}, ${v.fname} ${v.mname}</td>
      <td>${v.address}</td>
      <td>${Helper.getAge(v.birthday)}</td>
    </tr>
  `);
  
  Helper.DataTable_Init('#evacuee-graph-table', thead + tbody);
}
// --</ Evacuees Graph >--





















