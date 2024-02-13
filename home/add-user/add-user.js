import { Core, CustomNotification, Helper } from "../../core/core.js";
Core.user_redirectToLogin();

Core.onChange("#input-show-pass", () => {
  Core.f("#show-pass").style.display = "none";
  Core.f("#hide-pass").style.display = "none";

  if (Core.checked("#input-show-pass")) {
    Core.f("#input-password").type = "text";
    Core.f("#hide-pass").style.display = "block";
  } else {
    Core.f("#input-password").type = "password";
    Core.f("#show-pass").style.display = "block";
  }
});

Core.onClick("#new-user-save", async () => {
  const form_data = Core.createFormData({ uid: Core.user_getData().id }, new FormData(Core.f("#new-user-form")))

  if (!Core.isValidForm(form_data, ["fullname", "contact", "birthday", "category", "username", "password"])) {
    Helper.Promt_Error("Please fill required fields.");
    return;
  }
  Helper.Promt_Clear();

  await Core.fetch_data(`${Core.base_url()}/php/user_new.php`, "json", form_data).then(data => {
    const result = data.result;

    if (!result) {
      CustomNotification.add("Error", "Username already exist", "danger");
      return;
    }

    if (result[0].result) {
      CustomNotification.add("Success!", "New user added!", "success");
      Core.f("#new-user-form").reset();
    } else
      CustomNotification.add("Error", "Try again later.", "danger");
  });

});