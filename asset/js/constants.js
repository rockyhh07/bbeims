const BASE_URL = "http://localhost/bbeims/";

let USER_DATA = JSON.parse(
    window.sessionStorage.getItem("user_data") ? 
    window.sessionStorage.getItem("user_data") : 
    '[]'
);