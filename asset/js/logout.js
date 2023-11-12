function logout() {
   const form = new FormData();
   form.append('user', JSON.stringify(USER_DATA));
   fetch(BASE_URL + 'php/user_logout.php', {
      method: 'post',
      body: form
   })
   .then(response => response.json())
   .then(response => {
      let result = response.result[0];
      if (result.result) {

         window.sessionStorage.clear();
         USER_DATA = [];
         window.location.href = BASE_URL;
      }
      else alert('Something went wrong!');
   });
}

if(USER_DATA.category !== 'A'){
   $("#user-management").remove();
   $(".report-generator").remove();
}

function validForm(form) {
   let error = 0;
   let invalid = [];
   form.forEach((val, key) => {
      if(val == "") {
         error++;
         invalid.push(key);
      }
   });

   if(error > 0) {
      show_alert({
         title : `<i class="fas fa-exclamation-triangle"></i> Error occurred!`,
         body : "Fields must not be empty.",
         buttons : ["Okay"]
      });
   }
   return (error === 0);
}

function getAge(dateString) {
   if(!dateString) return 0;
   var today = new Date();
   var birthDate = new Date(dateString);
   var age = today.getFullYear() - birthDate.getFullYear();
   var m = today.getMonth() - birthDate.getMonth();
   if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
       age--;
   }
   return age;
}

function dateToInputDate(date){
   if(!date) return '1234-01-01';
   let today = new Date(date);
   let day = (today.getDate() < 10) ? "0" + today.getDate() : today.getDate() ;
   let month = (today.getMonth() + 1 < 10) ? "0" + (today.getMonth() + 1) : (today.getMonth() + 1);    
   let year = today.getFullYear();
}


async function loadColors(length) {
   try {
      const response = await fetch(`${BASE_URL}asset/templates/colors.json`);
      const colorsData = await response.json();

      let newColors = colorsData.slice(0, length);

      return newColors;
   } catch (error) {
      console.error('Error loading colors:', error);
      throw error;
   }
}


/**
 * For Notifications
 */
const notifHolder = document.createElement("div");
(function(){
   notifHolder.style.position = "fixed";
   notifHolder.style.width = "300px";
   notifHolder.style.bottom = "5px";
   notifHolder.style.right = "0";
   notifHolder.style.zIndex = "100";
   notifHolder.style.display = "flex";
   notifHolder.style.flexDirection = "column-reverse";
   document.querySelector("body").append(notifHolder);
})();

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