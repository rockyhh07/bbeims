import { Core, CustomNotification } from "../../core/core.js";

Core.user_redirectToAdmin();

Core.f("#login-form").addEventListener("submit", async function (e) {
  e.preventDefault();

  const form = new FormData(document.getElementById('login-form'));
  const result = ((await Core.fetch_data(`${Core.base_url()}/php/user_login.php`, "json", form)).result);

  if (!result.result) {
    CustomNotification.add('Login failed', result.message, 'danger');
    return;
  }

  sessionStorage.setItem("user_data", JSON.stringify(result));
  location.href = `${Core.base_url()}/home/dashboard/`
});