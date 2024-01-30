import Core from "../../Core/Core.js";

async function privacy() {
  const layout = await Core.fetch_data(`${Core.base_url()}/assets/templates/privacyPolicy.html`);
  show_alert({
    title: "<div style='font-size: large; font-weight: bold;'>Privacy policy</div>",
    body: layout,
    buttons: ["Close"]
  });
}

document.getElementById('login-button').addEventListener("click", async function (e) {
  e.preventDefault();

  const form = new FormData(document.getElementById('login-form'));
  const result = ((await Core.fetch_data(`${Core.base_url()}/php/user_login.php`, "json", form)).result);

  if (!result.result) { Core.addNotif('Login failed', `${result.message}`, 'r'); return; }

  window.sessionStorage.setItem("user_data", JSON.stringify(result));
  window.location.href = `${Core.base_url()}/home/admin/`
});