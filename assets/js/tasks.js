import { changeNav } from "/assets/js/modules/header.js";
import { showErrors } from "/assets/js/modules/errors.js";
import { redirectNotLogged } from "/assets/js/modules/auth.js";
import { addTask, addUserTasks } from "/assets/js/modules/tasks_dom_ops.js";

const form = document.getElementById("addtask-form");

window.addEventListener("load", () => {
    changeNav("tasks");
    redirectNotLogged();
    addUserTasks();
});

form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const {token} = JSON.parse(localStorage.getItem("account_data"));

    const data = Object.fromEntries(new FormData(form));
    data["datetime_start"] = data["datetime_start"].split("T").join(" ") + ":00";
    data["datetime_finish"] = data["datetime_finish"].split("T").join(" ") + ":00";

    const options = {
        method: "POST",
        headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    };

    try {
        const json = await fetch("/user/tasks/new", options).then(res => res.json());
        console.log(json);
        if (!json["ok"]) return showErrors(json["errors"]);
        data["id"] = json["id_task"];
        data["subtasks"] = [];

        addTask(data);
        form.reset();
    } catch {
        return showErrors(["Algo salio mal, por favor, intentelo mas tarde, además, revice su conexión a internet"]);
    }
})
