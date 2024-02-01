class CustomNotification {

  static add(title, message, type) {
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

  static notifHolder = document.querySelector("#notification_holder");
}