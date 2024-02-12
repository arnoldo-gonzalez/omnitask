const errors_place = document.getElementById("errors_place");
let i = 0;

const listener = ({currentTarget}) => {
    const parent_error = currentTarget.parentElement;
    errors_place.removeChild(parent_error);
}

export const showErrors = errors => {
    const fragment = document.createDocumentFragment();
    for (const error of errors) {
        const new_task = document.createElement("article");
        const new_task_btn = document.createElement("button");

        new_task.classList.add("errors__error");
        new_task.innerHTML = `<p class="errors__p">${error}</p>`;

        new_task_btn.classList.add("errors__btn");
        new_task_btn.innerHTML = `<i class="fa-solid fa-xmark"></i>`;
        new_task_btn.addEventListener("click", listener);

        new_task.appendChild(new_task_btn);
        fragment.appendChild(new_task);
    }
    errors_place.appendChild(fragment);
};


