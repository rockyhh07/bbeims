import { Core } from "../../core/core.js";
import { CustomNotification } from "../../core/customNotification.js";
import { Helper } from "../../core/helper.js";
import { ModalHandler } from "../../core/modalHandler.js";

(await Load_Barangay)();

async function Load_Barangay() {
  const barangay_list = (await Core.fetch_data(`${Core.base_url()}/php/barangay_get_all.php`, "json")).result;
  let barangay_selectTag = '';
  barangay_list.forEach(v => barangay_selectTag += `<option value="${v.id}">${v.name}</option>`);
  Core.f("#barangay_list").innerHTML += barangay_selectTag;
}

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

  if (!Core.isValidForm(form_data, ["fullname", "contact", "barangay", "birthday", "category", "username", "password"])) {
    Helper.Promt_Error("Please fill required fields.");
    return;
  }

  if (Core.formValidator(form_data, ["birthday"], v => Helper.getAge(v) >= 18).length > 0) {
    Helper.Promt_Error("Invalid value. Age must be 18 or older.");
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