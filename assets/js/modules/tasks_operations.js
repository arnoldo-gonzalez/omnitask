import { addSubtask, generateSubtaskForm } from "/assets/js/modules/subtasks_operations.js";

const tasks_place = document.getElementById("tasks-section");

const addSubtaskForm = (taskId) => {
    const new_subtaskform = document.createElement("div");
    new_subtaskform.classList.add("subtaskform")
    new_subtaskform.innerHTML = `
    <h5>Añadir Subtarea:</h5>
    `
    generateSubtaskForm(new_subtaskform, taskId);
    tasks_place.appendChild(new_subtaskform)
}

export const addTask = (data) => {
    const new_task = document.createElement("article");
    new_task.id = data["id"];
    new_task.classList.add("task");

    new_task.innerHTML += `
    <div class="task__content">
        <p><span>Titulo:</span><span>${data["title"]}</span></p>
        <p><span>Inicio:</span><span>${data["datetime_start"]}</span></p>
        <p><span>Fin:</span> <span>${data["datetime_finish"]}</span></p>
    </div>
    <div class="task__description">
        <button class="task__btn">Mostrar/Ocultar descripcion</button>
        <p class="task__description--p" data-description>${data["description"]}</p>
    </div>
    `;


    const subtasks_place = document.createElement("div");
    subtasks_place.classList.add("task__subtasks")
    subtasks_place.innerHTML = `
    <button class="task__btn">Mostrar/Ocultar subtareas</button>
    `;
    const subtasks_container = document.createElement("div");
    data["subtasks"].forEach(subtask => addSubtask(subtask, subtasks_container));

    subtasks_place.appendChild(subtasks_container);
    new_task.appendChild(subtasks_place);

    tasks_place.appendChild(new_task);
    addSubtaskForm(data["id"]);
}

export const addUserTasks = async () => {
    const {token} = JSON.parse(localStorage.getItem("account_data"));
    const options = {
        method: "GET",
        headers: {
            "Authorization": `Bearer ${token}`
        }
    };

    try {
        const tasks = await fetch("/user/tasks/get", options).then(res => res.json());
        tasks.forEach(task => addTask(task));
        return true;
    } catch {
        return false;
    }
}
