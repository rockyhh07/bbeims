import { Core } from "../../core/core.js";
import { CustomNotification } from "../../core/customNotification.js";
import { Helper } from "../../core/helper.js";
import { ModalHandler } from "../../core/modalHandler.js";

Core.user_redirectToAdmin();

Core.f("#login-form").addEventListener("submit", async function (e) {
  e.preventDefault();

  const form = new FormData(document.getElementById('login-form'));
  const result = ((await Core.fetch_data(`${Core.base_url()}/php/user_login.php`, "json", form)).result);

  if (!result.result) {
    CustomNotification.add('Login failed', result.message, 'danger');
    return;
  }

  localStorage.setItem("user_data", JSON.stringify(result));
  location.href = `${Core.base_url()}/home/dashboard/`
});