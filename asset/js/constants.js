const BASE_URL = "http://192.168.254.128/bbeims/";

let USER_DATA = JSON.parse(
    window.sessionStorage.getItem("user_data") ? 
    window.sessionStorage.getItem("user_data") : 
    '[]'
);