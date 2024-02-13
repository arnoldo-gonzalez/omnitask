import { showErrors } from "/assets/js/modules/errors.js";

export const addSubtask = (data, place, id_parent_task) => {
    const new_subtask = document.createElement("div");
    new_subtask.dataset.idParentTask = id_parent_task;
    new_subtask.classList.add("task__subtask");
    new_subtask.id = `subtask-num-${data["id"]}`;
    new_subtask.innerHTML = `
    <p><span>Titulo:</span> <span>${data["title"]}</span></p>
    <p><span>Inicio:</span> <span>${data["datetime_start"]}</span></p>
    <p><span>Fin:</span> <span>${data["datetime_finish"]}</span></p>
    <button data-subtask-delete>Borrar Subtarea</button>
    `;

    new_subtask.addEventListener("click", subtasksMainListener)
    place.appendChild(new_subtask)
}

export const generateSubtaskForm = (place, taskId) => {
    const new_subtask_form = document.createElement("form");
    new_subtask_form.innerHTML = `
    <label class="subtaskform__label">
        Titulo: 
        <input type="text" name="title" maxlength="25">
    </label>
    <label class="subtaskform__label">
        <span>Fecha de inicio:</span>
        <input required class="addtasks__input" type="datetime-local" name="datetime_start">
    </label>
    <label class="subtaskform__label">
        <span>Fecha de fin:</span>
        <input required class="addtasks__input" type="datetime-local" name="datetime_finish">
    </label>
    <input class="subtaskform__submit" type="submit" value="Añadir subtarea">
    `;

    new_subtask_form.addEventListener("submit", e => subtasksFormListener(e, new_subtask_form, taskId, place));
    place.appendChild(new_subtask_form);
}

async function subtasksMainListener({target}){
    const {token} = JSON.parse(localStorage.getItem("account_data")) || {};
    if (!("subtaskDelete" in target.dataset) || !token) return;

    const subtask = target.parentNode;
    const subtasks_place = subtask.parentNode;
    const id_subtask = parseInt((subtask.id).split("-")[2]);
    const id_parent_task = parseInt(subtask.dataset.idParentTask);

    const options = {
        method: "DELETE",
        headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
        },
        body: JSON.stringify({id_parent_task, id_subtask})
    };

    try {
        const json = await fetch("/user/tasks/subtasks/delete", options).then(res => res.json());
        if (!json["ok"]) return showErrors(json["errors"]);
        subtasks_place.removeChild(subtask);
    } catch (e) {
        console.log(e)
        return showErrors(["Algo salio mal, por favor, intentelo mas tarde, además, revice su conexión a internet"]);
    }
}

async function subtasksFormListener (e, form, id_parent, place){
    e.preventDefault();
    const {token} = JSON.parse(localStorage.getItem("account_data"));

    const data = Object.fromEntries(new FormData(form));
    data["datetime_start"] = data["datetime_start"].split("T").join(" ") + ":00";
    data["datetime_finish"] = data["datetime_finish"].split("T").join(" ") + ":00";
    data["id_parent_task"] = parseInt(id_parent);

    const options = {
        method: "POST",
        headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    };

    try {
        const json = await fetch("/user/tasks/subtasks/new", options).then(res => res.json());
        if (!json["ok"]) return showErrors(json["errors"]);
        data["id"] = json["id_task"];

        const subtasks_place = document.querySelector(`#task-num-${id_parent} div[data-subtasks-place]`);

        addSubtask(data, subtasks_place, data["id_parent_task"]);
        form.reset();
    } catch (e) {
        console.log(e)
        return showErrors(["Algo salio mal, por favor, intentelo mas tarde, además, revice su conexión a internet"]);
    }
}
