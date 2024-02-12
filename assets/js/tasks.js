import { changeNav } from "/assets/js/modules/header.js";
import { showErrors } from "/assets/js/modules/errors.js";
import { redirectNotLogged } from "/assets/js/modules/auth.js";

window.addEventListener("load", () => {
    changeNav("tasks");
    redirectNotLogged();
});
