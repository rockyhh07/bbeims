export class Core {

  static base_url() {
    return '../..';
  }

  static f(element, mulitple = false) {
    return mulitple ? document.querySelectorAll(element) : document.querySelector(element);
  }

  static async fetch_data(url = '', type = "text", form = new FormData()) {
    const args = (form) ? { method: 'post', body: form } : null;

    return await fetch(url, args).then(response => {
      if (type === "raw") return response;
      if (type === "json") return response.json()
      return response.text();
    })
  }

  static isValidForm(form = new FormData(), required = []) {
    let invalids = [];
    form.forEach((val, key) => {
      if (required.includes(key) && !val) invalid.push(key);
    });
    return invalids;
  }


  static user_clearData = () => sessionStorage.clear();
  static user_getData = () => JSON.parse(sessionStorage.getItem("user_data") ?? '[]');

  static async user_redirectToAdmin() {
    const response = (await this.fetch_data(`${Core.base_url()}/php/session_status.php`, 'json')).result[0];
    if (response.result) {
      window.location.href = `${Core.base_url()}/home/dashboard/`;
    }
  }

  static async user_redirectToLogin() {
    const response = (await this.fetch_data(`${Core.base_url()}/php/session_status.php`, 'json')).result[0];
    if (!response.result) {
      window.location.href = `${Core.base_url()}/main/login/`;
    }
  }

  static user_Logout(e) {
    e.preventDefault();
    fetch(`${Core.base_url()}/php/user_logout.php`, {
      method: 'post',
      body: Core.createFormData({ user: '' })
    })
      .then(response => response.json())
      .then(data => {
        let result = data.result[0];
        if (result.result) { Core.user_clearData(); location.href = "."; }
        else alert('Something went wrong!');
      });
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

export class Helper {
  static getAge(dateString) {
    if (!dateString) return 0;
    var today = new Date();
    var birthDate = new Date(dateString);
    var age = today.getFullYear() - birthDate.getFullYear();
    var m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
      age--;
    }
    return age;
  }
}

export class CustomNotification {

  static add(title, message, type) {
    this.__start__();

    const notif_item = document.createElement("div");
    notif_item.setAttribute("class", `notif-item shadow slide-in-out notif-item-${type}`);
    notif_item.addEventListener("animationend", () => {
      notif_item.remove();
    });

    const notif_close = document.createElement("button");
    notif_close.setAttribute("class", "notif_close_btn");
    notif_close.innerHTML = '<i class="fas fa-times"></i>';
    notif_close.addEventListener("click", () => {
      notif_item.remove();
      if (this.notifHolder.children.length == 0) {
        this.notifHolder.classList.add("hide");
      }
    });

    const notif_title = document.createElement("div");
    notif_title.setAttribute("class", `notif-title fw-bold d-flex align-items-center notif-title-${type}`);

    const title_content = document.createElement('spam');
    title_content.innerHTML = title;
    notif_title.appendChild(title_content);

    const notif_body = document.createElement("section");
    notif_body.setAttribute("class", "notif-body");
    notif_body.innerHTML = message;

    notif_item.appendChild(notif_close);
    notif_item.appendChild(notif_title);
    notif_item.appendChild(notif_body);


    if (this.notifHolder.children.length < 3) {
      this.notifHolder.classList.remove("hide");
      this.notifHolder.appendChild(notif_item);
    }
    else {
      this.notifHolder.children[0].remove()
      this.notifHolder.appendChild(notif_item);
    }
  }

  static notifHolder = Core.f("#notification_holder");
  static __initialized__ = false;
  static __initialize__ = () => {
    const css = document.createElement("link");
    css.setAttribute("rel", "stylesheet")
    css.setAttribute("href", "../../assets/css/notification.css")
    Core.f("head").appendChild(css);
  }

  static __start__ = () => {
    if (!this.__initialized__) this.__initialize__();
  }
}