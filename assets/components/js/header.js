import { Core } from "../../../core/core.js";

Core.user_redirectToLogin();

Core.f("#btn-logout").addEventListener("click", Core.user_Logout);

Core.f("#username").innerHTML += ` ${Core.user_getData().username}`;