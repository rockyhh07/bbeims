fetch(BASE_URL + '/php/get/session_status.php')
.then(response=>response.json())
.then(response=>{
    session = response.result[0];
    if(!session.result) window.location.href = BASE_URL;
});