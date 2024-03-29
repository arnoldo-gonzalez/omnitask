import { changeNav } from "/assets/js/modules/header.js";
import { showErrors } from "/assets/js/modules/errors.js";
import { redirectLogged } from "/assets/js/modules/auth.js";

const form = document.getElementById("signin_form");

window.addEventListener("load", () => {
    changeNav("sign_in");
    redirectLogged();
    form.reset()
});

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
        const json = await fetch("/user/sign_in", options).then(res => res.json());
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
