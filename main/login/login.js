Core.redirect_to_admin();

// document.getElementById('login-form').addEventListener("submit", async function (e) {
//   e.preventDefault();

//   const form = new FormData(document.getElementById('login-form'));
//   const result = ((await Core.fetch_data(`${Core.base_url()}/php/user_login.php`, "json", form)).result);

//   if (!result.result) { Core.addNotif('Login failed', `${result.message}`, 'r'); return; }

//   window.sessionStorage.setItem("user_data", JSON.stringify(result));
//   window.location.href = `${Core.base_url()}/home/admin/`
// });

function submit_login(e) {
  e.preventDefault();

  console.log({ event: e })
}


function submit_modal() {
  console.log("SUBMIT");
}