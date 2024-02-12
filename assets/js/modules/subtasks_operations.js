export const addSubtask = (data, place) => {
    const new_subtask = document.createElement("div");
    new_subtask.id = data["id"]
    new_subtask.innerHTML = `
    <h6>${data["title"]}</h6>
    <p><span>Inicio:</span> <span>${data["datetime_start"]}</span></p>
    <p><span>Fin:</span> <span>${data["datetime_finish"]}</span></p>
    `;

    place.appendChild(new_subtask)
}

export const generateSubtaskForm = (place, taskId) => {
    const new_subtask_form = document.createElement("form");
    new_subtask_form.dataset.id = taskId;
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

    place.appendChild(new_subtask_form);
}
