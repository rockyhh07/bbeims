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