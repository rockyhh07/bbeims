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
