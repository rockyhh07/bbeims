const BASE_URL = "http://localhost/bbeims/";

let USER_DATA = JSON.parse(
    window.sessionStorage.getItem("user_data") ? 
    window.sessionStorage.getItem("user_data") : 
    '[]'
);


/**
 * For Notifications
 */
notifHolder = document.createElement("div");
(function(){
   notifHolder.style.position = "fixed";
   notifHolder.style.width = "300px";
   notifHolder.style.bottom = "5px";
   notifHolder.style.right = "0";
   notifHolder.style.zIndex = "100";
   notifHolder.style.display = "flex";
   notifHolder.style.flexDirection = "column-reverse";
})();



/**
 * Add notification
 * @param {String} title 
 * @param {String} message 
 * @param {Character} flag 
 */
function addNotif(title, message, flag){

   let tColor = 
       (flag == "g") ? "#C8FEE0" :
       (flag == "r") ? "#FFAFAA" :
       "#ECECEC";

   let bColor = 
       (flag == "g") ? "#EAFAF1" :
       (flag == "r") ? "#FDEDEC" :
       "#F8F9F9";

   const notif = document.createElement("section");
   notif.setAttribute("class", "notif shadow slide-in-out");
   notif.addEventListener("animationend", ()=>{
       notif.remove();
   })
   // notif.style.border = "1px solid " + tColor;
   notif.style.backgroundColor = bColor;

   const notif_close = document.createElement("button");
   notif_close.setAttribute("class", "notif_close_btn");
   notif_close.innerHTML = '<i class="fas fa-times"></i>';
   notif_close.style.position = "absolute";
   notif_close.style.top = "5px";
   notif_close.style.right = "5px";
   notif_close.style.background = "none";
   notif_close.style.border = "none";
   // notif_close.style.border = `1px solid ${tColor}`;
   // notif_close.style.backgroundColor = bColor;
   notif_close.addEventListener("click", ()=>{
       notif.remove();
       if(notifHolder.children.length == 0){
           notifHolder.classList.add("hide");
       }
   });
   
   const notif_title = document.createElement("section");
   notif_title.setAttribute("class", "notif-title fw-bold d-flex align-items-center");
   notif_title.style.gap = ".5rem";
   notif_title.style.backgroundColor = tColor;

   const title_content = document.createElement('spam');
   title_content.innerHTML = title;
   notif_title.appendChild(title_content);

   const notif_body = document.createElement("section");
   notif_body.setAttribute("class", "notif-body");
   notif_body.innerHTML = message;

   notif.appendChild(notif_close);
   notif.appendChild(notif_title);
   notif.appendChild(notif_body);

   
   if(notifHolder.children.length < 3){
       notifHolder.classList.remove("hide");
       notifHolder.appendChild(notif);
   }
   else{
       notifHolder.children[0].remove()
       notifHolder.appendChild(notif);
   }
}

async function fetch_data(url, type = "text", form = null) {
    const args = (form) ? {method:'post', body : form} : null;

   return await fetch(`${BASE_URL}${url}`, args).then(response => {
      if(type === "json") return response.json()
      return response.text();
   })
   .catch(err => {
      addNotif("Server error", "Contact system administrators. <b>Reference: E2002</b>", "r");
      console.log(err);
   });
}

show_alert = undefined;

window.addEventListener("load", ()=>{
    document.querySelector("body").append(notifHolder);

    show_alert = (content = {}, callback = undefined) => {
        const holder = $(`<div 
            id="alert-holder" 
            class="d-flex justify-content-center align-items-center" 
            style="position: fixed; top: 0; left: 0; z-index: 11000; width: 100%; height: 100vh; background-color: rgba(0,0,0,0.8);"
        ></div>`);
        const container = $(`<div class="card p-3 m-2" style="max-height: 90vh; overflow-y: auto;"></div>`);
        const header = $(`<h3 class="p-2" style="border-bottom: 1px solid rgba(0,0,0,0.6);">${content.title}</h3>`);
        const body = $(`<div class="mb-3 p-2">${content.body}</div>`);
        const footer = $(`<div class="d-flex justify-content-end" style="gap: 1rem;"></div>`);
     
        const btn_okay = $(`<button class="btn btn-primary">${content.buttons[0] ? content.buttons[0] : "Okay"}</button>`);
        const btn_cancel = content.buttons[1] ? $(`<button class="btn btn-danger">${content.buttons[1]}</button>`) : undefined;
     
        btn_okay.on("click", ()=>{
            callback ? callback(true) : null;
            holder.remove();
        });
        btn_cancel?btn_cancel.on("click", ()=>{
            callback ? callback(false) : null;
            holder.remove();
        }):null;
     
        footer.append(btn_okay, btn_cancel);
        container.append(header, body, footer);
        holder.append(container);
        $("body").append(holder);
     }
})