const BASE_URL = "https://172.0.3.79/ojt/bbeims/";

let USER_DATA = JSON.parse(
    window.sessionStorage.getItem("user_data") ? 
    window.sessionStorage.getItem("user_data") : 
    '[]'
);