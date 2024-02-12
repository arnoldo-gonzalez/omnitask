import { changeNav } from "/assets/js/modules/header.js";
import { showErrors } from "/assets/js/modules/errors.js";
import { redirectLogged } from "/assets/js/modules/auth.js";

const premium_btn = document.getElementById("premium-btn");
const basic_btn = document.getElementById("basic-btn");
const paymethod_div = document.getElementById("paymethod-div");
const form = document.getElementById("signup_form");

const changeClasses = (elemt, class_remove, class_add) => {
    elemt.classList.remove(class_remove);
    elemt.classList.add(class_add);
};

window.addEventListener("load", () => {
    changeNav("sign_up");
    redirectLogged();
    form.reset()
    changeClasses(paymethod_div, "hidden", "visible");
});

premium_btn.addEventListener("change", () => changeClasses(paymethod_div, "hidden", "visible"));
basic_btn.addEventListener("change", () => changeClasses(paymethod_div, "visible", "hidden"));

form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const data = Object.fromEntries(new FormData(form));
    const options = {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    };

    try {
        const json = await fetch("/user/sign_up", options).then(res => res.json());
        if (!json["ok"]) return showErrors(json["errors"]);
        const account_data = {
            token: json["token"],
            id: json["id"],
            name: json["name"]
        };

        localStorage.setItem("account_data", JSON.stringify(account_data));
        window.location.href = json["next_url"];
    } catch {
        return showErrors(["Algo salio mal, por favor, intentelo mas tarde, además, revice su conexión a internet"]);
    }
});
