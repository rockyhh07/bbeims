import { Core } from "../../core/core.js";
import { CustomNotification } from "../../core/customNotification.js";
import { Helper } from "../../core/helper.js";
import { ModalHandler } from "../../core/modalHandler.js";

const setPasswordModal = "#setPassword";
const generateModal = "#generate-report-modal";
CustomNotification.add("BBEIMS", `Welcome <b>${Core.user_getData().username}</b>`, "primary");
const archived_list_options = (await Core.api('/incident_archived_getAll_options', "json", Core.createFormData({ uid: Core.user_getData().id }))).result;

let show_password = false;
let show_cpassword = false;

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

  const _protected = Core.user_getData().protected == "1";
  console.log({ _protected, p: Core.user_getData().protected })
  if (!_protected) {

    Core.f(`${setPasswordModal}-body`).innerHTML = await Core.fetch_data(`setPassword.html`, "text");
    Core.onClick("#show_password", () => {
      show_password = !show_password;
      Core.f('#show_password').innerHTML = show_password ? '<i class="fas fa-eye-slash">' : '<i class="fas fa-eye">';
      Core.f("#password").type = show_password ? 'text' : 'password';
    });
    Core.onClick("#show_cpassword", () => {
      show_cpassword = !show_cpassword;
      Core.f('#show_cpassword').innerHTML = show_cpassword ? '<i class="fas fa-eye-slash">' : '<i class="fas fa-eye">';
      Core.f("#cpassword").type = show_cpassword ? 'text' : 'password';
    });
    Core.onClick(`${setPasswordModal}-btn-setPassword`, async () => {
      const form_data = new FormData(Core.f("#setPassword-form"));
      const data = Core.getDataFromFormData(form_data);

      if (!Core.isValidForm(form_data, ["password", "confirm_password"])) {
        Helper.Promt_Error('Error: Passowrd must not be empty.')
        return;
      }

      if (Core.formValidator(form_data, ["password", "confirm_password"], v => v.length >= 8).length > 0) {
        Helper.Promt_Error('Error: Password must bne longer than 8 characters.')
        return;
      }

      if (data.password != data.confirm_password) {
        Helper.Promt_Error('Error: Password did not match.')
        return;
      }

      Helper.Promt_Clear();

      const resp = (await Core.api('/user_updatePassword', "json", Core.createFormData({ uid: Core.user_getData().id, password: data.password, username: Core.user_getData().username }))).result;
      // console.log({ resp })
      localStorage.setItem('user_data', JSON.stringify(resp));
      location.reload()

    });

    Core.f("#btn-setPassword").click();
  }

})();


// --< Generate Report >--
Core.f("#btn-generateReport").addEventListener("click", async (e) => {
  e.preventDefault();

  const replace = {
    tbody: '',
  }
  archived_list_options.forEach(v => {
    const date = new Date(v.incident_date);

    replace.tbody += `
    <tr>
      <td>
        <input type="checkbox" name="incident_date" value="${v.incident_date},${v.name}" />
      </td>
      <td>
        ${v.name}
      </td>
      <td>
        ${date.toDateString()}
      </td>
      <td>
        ${date.toLocaleTimeString()}
      </td>
    </tr>`
  });

  let layout = (await Core.fetch_data("generateReport.html", "text"));
  layout = Core.replaceLayout(layout, replace)
  Core.f(`${generateModal}-body`).innerHTML = layout;
  Core.onClick("#check-all", () => {
    Core.fm('input[name="incident_date"]', v => v).forEach(e => e.checked = true);
  })
});

Core.f(`${generateModal}-btn-generate`).addEventListener("click", async () => {
  Core.f(`${generateModal}-hide`).click();

  let incident_dates_list = [];
  let incident_types_list = [];
  let incident_dates = '';
  let incident_types = '';
  Core.fm('input[name="incident_date"]', v => v)
    .filter(v => v.checked)
    .map(v => v.value)
    .forEach(v => {
      const [date, type] = String(v).split(',');
      incident_dates_list.push(date);
      incident_types_list.push(type);
    });
  incident_dates_list = new Set(incident_dates_list)
  incident_dates_list.forEach(v => incident_dates += `'${v}',`);
  incident_dates = incident_dates.substring(0, incident_dates.length - 1);

  incident_types_list = new Set(incident_types_list)
  incident_types_list.forEach(v => incident_types += `'${v}',`);
  incident_types = incident_types.substring(0, incident_types.length - 1);

  if (incident_dates_list.length == 0 || incident_types_list.length == 0 || incident_dates == '' || incident_types == '') {
    CustomNotification.add("Error Generating", "Please select at least 1 disaster.", "danger");
    Core.f("#report-container").innerHTML = '';
    return;
  }

  CustomNotification.add("Generating report", "Report is being generated!", "secondary");

  const resp = (await Core.api('/generate_report_by_incident', "json", Core.createFormData({
    incident_date: incident_dates
  }))).result;

  if (!resp) {
    CustomNotification.add("Error", "An error occured. Try again later.", "danger");
    Core.f("#report-container").innerHTML = '';
    return;
  }

  const data = resp.map(v => ({
    fullname: `${v.lname}, ${v.fname} ${v.mname}`,
    age: Helper.getAge(v.birthday),
    gender: v.gender,
    contact: v.contact,
    representative: `${v.rep_lname}, ${v.rep_fname} ${v.rep_mname}`,
    address: v.address,
    center: v.center,
  }));

  let tbody = '';
  data.forEach(v => tbody += `
    <tr>
      <td class="text-center"><small>${v.fullname}</small></td>
      <td class="text-center"><small>${v.age}</small></td>
      <td class="text-center"><small>${v.gender}</small></td>
      <td class="text-center"><small>${v.contact}</small></td>
      <td class="text-center"><small>${v.representative}</small></td>
      <td class="text-center"><small>${v.address}</small></td>
      <td class="text-center"><small>${v.center}</small></td>
    </tr>
  `);

  let layout = (await Core.fetch_data('reportcontent.html', "text"));
  layout = Core.replaceLayout(layout, {
    incident_type: String(incident_types).replaceAll("'", ''),
    incident_date: String(incident_dates).replaceAll("'", ''),
    total: data.length,
    tbody: tbody,
  });

  Core.f("#report-container").innerHTML = layout;

  const margin = 5;
  const pageW = 210;
  const pageH = 297;

  const canvas = await html2canvas(Core.f("#report-container"), { allowTaint: true }).then((canvas) => canvas);
  let canvas_width = Helper.px_to_mm(canvas.width);
  let canvas_height = Helper.px_to_mm(canvas.height);

  if (canvas_width > (pageW - (margin * 2))) {
    const scale = (pageW - (margin * 2)) / canvas_width;
    canvas_width *= scale;
    canvas_height *= scale;
  }
  let x_offsest = 0;
  if (canvas_height > (pageH - (margin * 2))) {
    const scale = (pageH - (margin * 2)) / canvas_height;
    canvas_width *= scale;
    canvas_height *= scale;
    x_offsest = ((pageW - (margin * 2)) / 2) - canvas_width;
  }

  const pdf_File = new jsPDF("p", "mm", "a4", true);
  pdf_File.addImage(canvas.toDataURL('image/png'), 'PNG', x_offsest + margin, margin, canvas_width, canvas_height);
  pdf_File.save(`incident_report-${new Date().getTime()}.pdf`);

  CustomNotification.add("Generating report complete!", "Report is ready to download.", "success");
  Core.f("#report-container").innerHTML = '';
});
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





















