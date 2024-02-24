export class Core {

  static base_url() {
    return '../..';
  }

  static async api(url = '', type = null, form_data = null) {
    return await Core.fetch_data(`${Core.base_url()}/php${url}.php`, type, form_data);
  }

  static checked = (element) => Core.f(element).checked;

  static replaceLayout(html_layout = '', replaces = {}) {
    for (const key in replaces) html_layout = html_layout.replaceAll(`{{${key}}}`, replaces[key]);
    return html_layout;
  }

  static data = (element_reference, data_name) => element_reference.getAttribute(`data-${data_name}`);

  static onChange = (element, callback) => Core.f(element).addEventListener("change", callback);
  static onSubmit = (element, callback) => Core.f(element).addEventListener("submit", callback);

  static onClick = (element, callback, mulitple = false) =>
    mulitple ?
      Core.f(element, mulitple)().forEach(el => el.addEventListener("click", callback)) :
      Core.f(element, mulitple).addEventListener("click", callback);

  static clearClick = (element, callback, mulitple = false) =>
    mulitple ?
      Core.f(element, mulitple)().forEach(el => el.removeEventListener("click", callback)) :
      Core.f(element, mulitple).removeEventListener("click", callback);

  static f = (element, mulitple = false) =>
    !mulitple ? document.querySelector(element) : () => {
      const elems = [];
      document.querySelectorAll(element).forEach(el => elems.push(el));
      return elems;
    };


  static async fetch_data(url = '', type = "text", form = new FormData()) {
    const args = (form) ? { method: 'post', body: form } : null;

    return await fetch(url, args).then(async response => {
      if (type === "text") return await response.text();
      if (type === "json") return await response.json()
      return await response;
    })
  }

  static isValidForm(form = new FormData(), required = ['']) {
    let invalids = [];
    form.forEach((val, key) => {
      Core.f(`.form-control[name="${key}"]`, true)().forEach(e => e.style.border = "1px solid #CED4DA");
      if (required.includes(key) && !val) invalids.push(key);
    });
    invalids.forEach(v => Core.f(`.form-control[name="${v}"]`, true)().forEach(e => e.style.border = "1px solid #DC3545"));
    return invalids.length === 0;
  }

  static formValidator(form = new FormData(), fields = [''], condition = () => false) {
    let invalids = [];
    form.forEach((val, key) => {
      Core.f(`.form-control[name="${key}"]`, true)().forEach(e => e.style.border = "1px solid #CED4DA");
      if (fields.includes(key) && val) {
        if(!condition(val, key)) invalids.push(key);
      }
    });
    invalids.forEach(v => Core.f(`.form-control[name="${v}"]`, true)().forEach(e => e.style.border = "1px solid #DC3545"));
    return invalids
  }


  static user_clearData = () => localStorage.clear();
  static user_getData = () => JSON.parse(localStorage.getItem("user_data") ?? '[]');

  static async user_redirectToAdmin() {
    if (Core.user_getData().length > 0) location.href = `${Core.base_url()}/home/dashboard/`;
  }

  static async user_redirectToLogin() {
    if (Core.user_getData().length === 0) location.href = Core.base_url();
  }

  static async user_Logout(e) {
    Core.user_clearData();
    location.href = Core.base_url();
  }

  static createFormData(object = {}, passedData = new FormData()) {
    for (const key in object) passedData.append(key, object[key]);
    return passedData;
  }

  static getDataFromFormData(formData) {
    const data = {};
    formData.forEach((val, key) => data[key] = val);
    return data;
  }


}